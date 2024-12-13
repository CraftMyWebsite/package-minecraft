<?php

namespace CMW\Package\Minecraft;

use CMW\Manager\Package\IPackageConfig;
use CMW\Manager\Package\PackageMenuType;

class Package implements IPackageConfig
{
    public function name(): string
    {
        return 'Minecraft';
    }

    public function version(): string
    {
        return '1.1.0';
    }

    public function authors(): array
    {
        return ['Teyir'];
    }

    public function isGame(): bool
    {
        return true;
    }

    public function isCore(): bool
    {
        return false;
    }

    public function menus(): ?array
    {
        return [
            new PackageMenuType(
                icon: 'fas fa-cube',
                title: 'Minecraft',
                url: 'minecraft/servers',
                permission: 'minecraft.list'
            ),
        ];
    }

    public function requiredPackages(): array
    {
        return ['Core'];
    }

    public function uninstall(): bool
    {
        // Return true, we don't need other operations for uninstall.
        return true;
    }
}
