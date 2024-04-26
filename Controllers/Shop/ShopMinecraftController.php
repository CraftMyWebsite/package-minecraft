<?php

namespace CMW\Controller\Minecraft\Shop;

use CMW\Controller\Core\MailController;
use CMW\Entity\Shop\Items\ShopItemEntity;
use CMW\Entity\Users\UserEntity;
use CMW\Manager\Api\APIManager;
use CMW\Manager\Package\AbstractController;
use CMW\Model\Minecraft\MinecraftModel;
use CMW\Model\Shop\Item\ShopItemsVirtualRequirementModel;
use CMW\Model\Votes\VotesConfigModel;

/**
 * Class: @ShopMinecraftController
 * @package Shop
 * @author Zomblard
 * @version 1.0
 */
class ShopMinecraftController extends AbstractController
{
    /**
     * @param ShopItemEntity $item
     * @param UserEntity $user
     */
    public function execMcNeeds(string $varName, ShopItemEntity $item, UserEntity $user): void
    {
        $command = ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName.'_commands',$item->getId());
        $userPseudo = $user->getPseudo();
        $servers = MinecraftModel::getInstance()->getServers();

        $activeServers = [];
        foreach ($servers as $server) {
            $activeServer = ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName.'_server'.$server->getServerId().'_',$item->getId());

            $activeServers[] = $activeServer;
        }

        foreach ($activeServers as $serverSelected) {
            if (!is_null($serverSelected)) {
                if (VotesConfigModel::getInstance()->getConfig()?->isEnableApi()) {
                    $this->sendItemsToCmwLink($serverSelected, $command, $userPseudo);
                } else {
                    //TODO @Teyir SEND MC NEEDS WITHOUT API
                }
            }

        }
    }

    private function sendItemsToCmwLink(int $serverId, string $command, string $userPseudo) :void
    {
        $serverEntity = MinecraftModel::getInstance()?->getServerById($serverId);

        $command = str_replace("{player}", $userPseudo, $command);
        $command = base64_encode($command);

        //TODO : Change URL FOR SHOP !!
        APIManager::getRequest("http://{$serverEntity?->getServerIp()}:{$serverEntity?->getServerCMWLPort()}/votes/send/reward/$userPseudo/$command",
            cmwlToken: $serverEntity?->getServerCMWToken());
    }
}