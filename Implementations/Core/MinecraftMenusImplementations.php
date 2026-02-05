<?php

namespace CMW\Implementation\Minecraft\Core;

use CMW\Interface\Core\IMenus;
use CMW\Manager\Lang\LangManager;

class MinecraftMenusImplementations implements IMenus
{
    public function getRoutes(): array
    {
        return [
            LangManager::translate('minecraft.menu.public') => 'minecraft/history',
        ];
    }

    public function getPackageName(): string
    {
        return 'Minecraft';
    }
}
