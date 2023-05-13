<?php

namespace CMW\Controller\Minecraft;

use CMW\Controller\Users\UsersController;
use CMW\Entity\Minecraft\MinecraftPingEntity;
use CMW\Entity\Minecraft\MinecraftPingPlayersEntity;
use CMW\Manager\Api\APIManager;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Requests\Request;
use CMW\Model\Minecraft\MinecraftModel;
use CMW\Manager\Router\Link;
use CMW\Utils\Utils;
use CMW\Manager\Views\View;
use CMW\Utils\Redirect;
use JsonException;
use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;

require_once(getenv("DIR") . 'App/Package/Minecraft/Vendors/MinecraftPing/MinecraftPing.php');
require_once(getenv("DIR") . 'App/Package/Minecraft/Vendors/MinecraftPing/MinecraftPingException.php');


/**
 * Class: @MinecraftController
 * @package Minecraft
 * @author CraftMyWebsite Team <contact@craftmywebsite.fr>
 * @version 1.0
 */
class MinecraftController extends AbstractController
{

    private static function pingServerOld(string $host, ?int $port = 25565): bool|array
    {
        $query = new MinecraftPing($host, $port, 2);

        try {
            return $query->queryOldPre17();
        } finally {
            $query?->close();
        }

    }

    private static function getTotalOnlinePlayers(): int
    {
        $toReturn = 0;
        foreach ((new MinecraftModel())->getServers() as $server) {
            $toReturn += self::pingServer($server->getServerIp(), $server->getServerPort())?->getPlayersOnline();
        }
        return $toReturn;
    }

    /**
     * @param string $host
     * @param int|null $port
     * @return \CMW\Entity\Minecraft\MinecraftPingEntity|null
     * @desc Ping server and get some data
     */
    public static function pingServer(string $host, ?int $port = 25565): ?MinecraftPingEntity
    {
        $query = new MinecraftPing($host, $port, 2);

        try {
            if ($query->connect()) {
                return $query->query();
            }

            /* If we can't reach the server, we return an empty MinecraftPingEntity */
            $playersEntity[] = new MinecraftPingPlayersEntity("None", 0);
            return new MinecraftPingEntity(
                null,
                null,
                0,
                0,
                $playersEntity,
                null
            );

        } catch (MinecraftPingException|JsonException $e) {
            echo $e->getMessage();
        } finally {
            $query?->close();
        }

        return null;
    }

    #[Link("/servers/delete", Link::POST, [], "/cmw-admin/minecraft")]
    private function adminServersDelete(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "minecraft.servers.delete");

        minecraftModel::getInstance()->deleteServer(filter_input(INPUT_POST, "serverId"));

        Flash::send(Alert::SUCCESS, "", LangManager::translate('minecraft.servers.toasters.server_delete'), true);

        Redirect::redirectPreviousRoute();
    }


    /*
     *
     * TOOLS FUNCTIONS
     *
     */

    #[Link(path: "/minecraft", method: Link::GET, scope: "/cmw-admin")]
    #[Link("/servers", Link::GET, [], "/cmw-admin/minecraft")]
    private function adminServers(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "minecraft.servers.list");

        $servers = minecraftModel::getInstance()->getServers();

        View::createAdminView("Minecraft", "servers")
            ->addVariableList(["servers" => $servers])
            ->addStyle("App/Package/Minecraft/Views/Resources/Css/main.css")
            ->addScriptBefore("App/Package/Minecraft/Views/Resources/Js/main.js")
            ->addStyle("Admin/Resources/Vendors/Simple-datatables/style.css","Admin/Resources/Assets/Css/Pages/simple-datatables.css")
            ->addScriptAfter("Admin/Resources/Vendors/Simple-datatables/Umd/simple-datatables.js",
                "Admin/Resources/Assets/Js/Pages/simple-datatables.js")
            ->view();
    }

    #[Link("/servers/fav/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/minecraft")]
    private function adminServersFav(Request $request, int $serverId): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "minecraft.servers.fav");

        minecraftModel::getInstance()->setFav($serverId);

        Flash::send(Alert::SUCCESS, "", LangManager::translate('minecraft.servers.toasters.server_fav'), true);

        Redirect::redirectPreviousRoute();
    }

    #[Link("/servers/add", Link::POST, [], "/cmw-admin/minecraft")]
    private function adminServersAdd(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "minecraft.servers.add");

        [$name, $ip, $status, $port, $cmwlPort] = Utils::filterInput("name", "ip", "status", "port", "cmwlPort");

        $server = minecraftModel::getInstance()->addServer($name, $ip, $status, ($port === "" ? null : $port), ($cmwlPort === "" ? null : $cmwlPort));

        if (!empty($cmwlPort)) {
            $this->sendFirstKeyRequest($server?->getServerId());
        }

        Flash::send(Alert::SUCCESS, "", LangManager::translate('minecraft.servers.toasters.server_add'), true);

        Redirect::redirectPreviousRoute();
    }

    #[Link("/servers", Link::POST, [], "/cmw-admin/minecraft")]
    private function adminServersEdit(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "minecraft.servers.edit");

        [$id, $name, $ip, $status, $port, $cmwlPort] = Utils::filterInput("serverId", "name", "ip", "status", "port", "cmwlPort");

        minecraftModel::getInstance()->updateServer($id, $name, $ip, $status, ($port === "" ? null : $port), ($cmwlPort === "" ? null : $cmwlPort));

        Flash::send(Alert::SUCCESS, "", LangManager::translate('minecraft.servers.toasters.server_edit'), true);

        Redirect::redirectPreviousRoute();
    }

    /**
     * @throws \JsonException
     */
    #[Link("/servers/cmwl/test/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/minecraft")]
    private function checkCmwLConfig(Request $request, int $serverId): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "minecraft.servers.edit");

        try {
            $req = APIManager::getRequest("http://" . MinecraftModel::getInstance()->getServerById($serverId)?->getServerIp() . ":" . MinecraftModel::getInstance()->getServerById($serverId)?->getServerCMWLPort(),
                cmwlToken: minecraftModel::getInstance()->getServerById($serverId)?->getServerCMWToken());
            if (empty($req)){
                print(json_encode("false", JSON_THROW_ON_ERROR));
                die();
            }
            $result = json_decode($req, true, 512, JSON_THROW_ON_ERROR);
            if ((int)$result['CODE'] === 200) {
                try {
                    print(json_encode("true", JSON_THROW_ON_ERROR));
                    Flash::send(Alert::SUCCESS, "", LangManager::translate('minecraft.servers.toasters.test_cmw_response_success'), true);
                } catch (JsonException) {
                }
            } else {
                try {
                    print(json_encode("false", JSON_THROW_ON_ERROR));
                    Flash::send(Alert::ERROR, "", LangManager::translate('minecraft.servers.toasters.test_cmw_response_error'), true);
                } catch (JsonException) {
                }
            }
        } catch (JsonException $e) {
            print json_encode($e, JSON_THROW_ON_ERROR);
        }
    }

    /**
     * @throws \JsonException
     */
    #[Link("/servers/list/", Link::GET, [], "/cmw-admin/minecraft")]
    private function getServersIdAndName(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "minecraft.servers.list");

        $toReturn = [];

        foreach (minecraftModel::getInstance()->getServers() as $server) {
            $toReturn[$server->getServerId()][] = $server->getServerName();
        }

        try {
            print (json_encode($toReturn, JSON_THROW_ON_ERROR));
        } catch (JsonException $e) {
            print (json_encode($e, JSON_THROW_ON_ERROR));
        }
    }

    /**
     * @param int $serverId
     * @return void
     * @desc Send the first key request to instantiate the connexion between server and website.
     */
    private function sendFirstKeyRequest(int $serverId): void
    {
        if(!UsersController::hasPermission("core.dashboard", "minecraft.servers.add")){
            return;
        }

        try {
            $privateKey = $this->generateCmwLinkPrivateKey();

            $res = @APIManager::postRequest("http://" . MinecraftModel::getInstance()->getServerById($serverId)?->getServerIp() . ":" . MinecraftModel::getInstance()->getServerById($serverId)?->getServerCMWLPort() . "/core/generate/firstKey",
                ["key" => $privateKey, "domain" => $_SERVER['HTTP_HOST']], cmwlToken: $privateKey);

            minecraftModel::getInstance()->setServerToken($serverId, $privateKey);

            $code = json_decode($res, JSON_THROW_ON_ERROR, 512, JSON_THROW_ON_ERROR)['CODE'];

            switch ($code) {
                case "200":
                    // Success
                    Flash::send(Alert::SUCCESS, "", LangManager::translate('minecraft.servers.toasters.cmwl_first_install.200'), true);
                    break;
                case "401":
                    // Non-Authorized
                    Flash::send(Alert::ERROR, "", LangManager::translate('minecraft.servers.toasters.cmwl_first_install.401'), true);
                    break;
                case "404":
                    // Undefined url
                    Flash::send(Alert::ERROR, "", LangManager::translate('minecraft.servers.toasters.cmwl_first_install.404'), true);
                    break;
                case "418":
                    // Internal error
                    Flash::send(Alert::ERROR, "", LangManager::translate('minecraft.servers.toasters.cmwl_first_install.418'), true);
                    break;
                default:
                    // Undefined error
                    Flash::send(Alert::ERROR, "", LangManager::translate('minecraft.servers.toasters.cmwl_first_install.other'), true);
                    break;
            }

        } catch (JsonException $e) {
            Flash::send(Alert::ERROR, "", $e->getMessage(), true);
            return;
        }
    }

    /**
     * @return string
     * @desc Generate unique private key for your cmw link plugin (base64 encoded for better url support)
     */
    private function generateCmwLinkPrivateKey(): string
    {
        return base64_encode(password_hash(Utils::genId(15) . uniqid('', true), PASSWORD_BCRYPT));
    }


}