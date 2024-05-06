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
                code: 'minecraft.servers.delete',
                description: LangManager::translate('minecraft.permissions.minecraft.servers.delete'),
            ),
            new PermissionInitType(
                code: 'minecraft.servers.list',
                description: LangManager::translate('minecraft.permissions.minecraft.servers.list'),
            ),
            new PermissionInitType(
                code: 'minecraft.servers.fav',
                description: LangManager::translate('minecraft.permissions.minecraft.servers.fav'),
            ),
            new PermissionInitType(
                code: 'minecraft.servers.add',
                description: LangManager::translate('minecraft.permissions.minecraft.servers.add'),
            ),
            new PermissionInitType(
                code: 'minecraft.servers.edit',
                description: LangManager::translate('minecraft.permissions.minecraft.servers.edit'),
            ),
        ];
    }

}