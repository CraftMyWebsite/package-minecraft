<?php

namespace CMW\Controller\Minecraft\Shop;

use CMW\Entity\Shop\Items\ShopItemEntity;
use CMW\Entity\Users\UserEntity;
use CMW\Manager\Api\APIManager;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;
use CMW\Model\Minecraft\MinecraftHistoryModel;
use CMW\Model\Minecraft\MinecraftModel;
use CMW\Model\Shop\Item\ShopItemsVirtualRequirementModel;
use function base64_encode;
use function is_null;

/**
 * Class: @ShopMinecraftController
 * @package Shop
 * @author Zomblard
 * @version 0.0.1
 */
class ShopMinecraftController extends AbstractController
{
    /**
     * @param ShopItemEntity $item
     * @param UserEntity $user
     */
    public function execMcNeeds(string $varName, ShopItemEntity $item, UserEntity $user): void
    {
        $command = ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName . '_commands', $item->getId());
        $userPseudo = $user->getPseudo();
        $servers = MinecraftModel::getInstance()->getServers();

        $activeServers = [];
        foreach ($servers as $server) {
            $isActive = ShopItemsVirtualRequirementModel::getInstance()->getSetting(
                $varName . '_server' . $server->getServerId() . '_',
                $item->getId()
            );

            if (!is_null($isActive)) {
                $activeServers[] = $server;
            }
        }

        foreach ($activeServers as $server) {
            $this->sendItemsToCmwLink(
                $server->getServerId(),
                $command,
                $userPseudo,
                $item->getName()
            );

            MinecraftHistoryModel::getInstance()->addHistory(
                $user->getId(),
                $server->getServerName(),
                'Achat sur la boutique',
                'Vous avez acheté ' . $item->getName() . ' sur la boutique'
            );
        }
    }

    private function sendItemsToCmwLink(int $serverId, string $command, string $userPseudo, string $itemName): void
    {
        $serverEntity = MinecraftModel::getInstance()?->getServerById($serverId);

        if (is_null($serverEntity)) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate('core.toaster.error'),
                LangManager::translate('core.toaster.internal_error'),
            );
            return;
        }

        $command = str_replace('{player}', $userPseudo, $command);
        $command = base64_encode($command);
        $itemName = base64_encode($itemName);

        APIManager::getRequest("http://{$serverEntity?->getServerIp()}:{$serverEntity?->getServerCMWLPort()}/shop/send/reward/$userPseudo/$command/$itemName",
            cmwlToken: $serverEntity?->getServerCMWToken());
    }
}
