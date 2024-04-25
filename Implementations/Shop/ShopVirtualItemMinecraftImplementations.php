<?php

namespace CMW\Implementation\Minecraft\Shop;

use CMW\Controller\Minecraft\ShopMinecraftController;
use CMW\Entity\Shop\Items\ShopItemEntity;
use CMW\Entity\Users\UserEntity;
use CMW\Interface\Shop\IVirtualItems;
use CMW\Manager\Env\EnvManager;

class ShopVirtualItemMinecraftImplementations implements IVirtualItems
{
    public function name(): string
    {
        return "Minecraft";
    }

    public function varName(): string
    {
        return "minecraft";
    }

    public function documentationURL(): ?string
    {
        return "";
    }

    public function description(): string
    {
        return "Executes des commandes en jeu quand vos joueurs achète un article";
    }

    public function includeItemConfigWidgets(?int $itemId): void
    {
        $varName = $this->varName();
        require_once EnvManager::getInstance()->getValue("DIR") . "App/Package/Minecraft/Views/Elements/commands.config.inc.view.php";
    }

    public function includeGlobalConfigWidgets(): void {}

    public function useGlobalConfigWidgetsInShopConfig(): bool
    {
        return false;
    }

    public function execOnBuy(string $varName, ShopItemEntity $item, UserEntity $user): void
    {
        ShopMinecraftController::getInstance()->sendCommands($varName, $item, $user);
    }

    public function execOnCancel(string $varName, ShopItemEntity $item, UserEntity $user): void
    {
        ShopMinecraftController::getInstance()->sendCommands($varName, $item, $user);
    }

}