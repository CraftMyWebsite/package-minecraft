<?php

namespace CMW\Controller\Minecraft;

use CMW\Controller\Core\MailController;
use CMW\Entity\Shop\Items\ShopItemEntity;
use CMW\Entity\Users\UserEntity;
use CMW\Manager\Package\AbstractController;
use CMW\Model\Shop\Item\ShopItemsVirtualRequirementModel;


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
    public function sendCommands(string $varName, ShopItemEntity $item, UserEntity $user): void
    {
        //TODO : Uniquement à des fin de test :
        $activeMessage = ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName.'active_message',$item->getId());
        $serverMessage = ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName.'message_server',$item->getId());
        $message = ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName.'message',$item->getId());

        $serverCommand = ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName.'command_server',$item->getId());
        $command = ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName.'command',$item->getId());

        $body = "Doit envoyer un message à des serveurs : $activeMessage<br>Le message est à envoyé sur : $serverMessage<br>Le message est : $message<br><br>La commande est à executer sur : $serverCommand<br>La commande est $command";

        MailController::getInstance()->sendMail($user->getMail(), "Choses à faire suite à l'achat de type Minecraft", $body);
    }
}