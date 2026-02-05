<?php

namespace CMW\Permissions\Minecraft;

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Permission\IPermissionInit;
use CMW\Manager\Permission\PermissionInitType;

class Permissions implements IPermissionInit
{
    public function permissions(): array
    {
        return [
            new PermissionInitType(
                code: 'minecraft.list',
                description: LangManager::translate('minecraft.permissions.minecraft.servers.list'),
            ),
            new PermissionInitType(
                code: 'minecraft.fav',
                description: LangManager::translate('minecraft.permissions.minecraft.servers.fav'),
            ),
            new PermissionInitType(
                code: 'minecraft.add',
                description: LangManager::translate('minecraft.permissions.minecraft.servers.add'),
            ),
            new PermissionInitType(
                code: 'minecraft.edit',
                description: LangManager::translate('minecraft.permissions.minecraft.servers.edit'),
            ),
            new PermissionInitType(
                code: 'minecraft.delete',
                description: LangManager::translate('minecraft.permissions.minecraft.servers.delete'),
            ),
            new PermissionInitType(
                code: 'minecraft.history',
                description: LangManager::translate('minecraft.permissions.minecraft.history'),
            ),
        ];
    }
}
