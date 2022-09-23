<?php

namespace CMW\Controller\Minecraft;

use CMW\Controller\Core\CoreController;
use CMW\Model\Minecraft\MinecraftModel;
use CMW\Router\Link;
use CMW\Utils\Utils;
use CMW\Utils\View;

/**
 * Class: @MinecraftController
 * @package Minecraft
 * @author CraftMyWebsite Team <contact@craftmywebsite.fr>
 * @version 1.0
 */
class MinecraftController extends CoreController
{

    private MinecraftModel $minecraftModel;

    public function __construct($theme_path = null)
    {
        parent::__construct($theme_path);
        $this->minecraftModel = new MinecraftModel();
    }

    #[Link(path: "/minecraft", method: Link::GET, scope: "/cmw-admin")]
    #[Link("/servers", Link::GET, [], "/cmw-admin/minecraft")]
    public function adminServers(): void
    {

        $servers = $this->minecraftModel->getServers();

        View::createAdminView("minecraft", "servers")
            ->addVariableList(["servers" => $servers])
            ->view();
    }

    #[Link("/servers/add", Link::POST, [], "/cmw-admin/minecraft")]
    public function adminServersAdd(): void
    {
        [$name, $ip, $port] = Utils::filterInput("name", "ip", "port");

        $this->minecraftModel->addServer($name, $ip, ($port === "" ? null : $port));

        header("Location: ../servers");
    }

    #[Link("/servers", Link::POST, [], "/cmw-admin/minecraft")]
    public function adminServersEdit(): void
    {
        [$id, $name, $ip, $port] = Utils::filterInput("serverId", "name", "ip", "port");

        $this->minecraftModel->updateServer($id, $name, $ip, ($port === "" ? null : $port));

        header("Location: servers");
    }

    #[Link("/servers/delete/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/minecraft")]
    public function adminServersDelete(int $id): void
    {
        $this->minecraftModel->deleteServer($id);

        header("Location: ../../servers");
    }

}