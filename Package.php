<?php

namespace CMW\Package\Minecraft;

use CMW\Manager\Package\IPackageConfig;
use CMW\Manager\Package\PackageMenuType;
use CMW\Manager\Package\PackageSubMenuType;

class Package implements IPackageConfig
{
    public function name(): string
    {
        return "Minecraft";
    }

    public function version(): string
    {
        return "0.0.1";
    }

    public function authors(): array
    {
        return ["Teyir"];
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
                lang: "fr",
                icon: "fas fa-cube",
                title: "Minecraft",
                url: "minecraft/servers",
                permission: "minecraft.servers.list",
                subMenus: []
            ),
            new PackageMenuType(
                lang: "en",
                icon: "fas fa-cube",
                title: "Minecraft",
                url: "minecraft/servers",
                permission: "minecraft.servers.list",
                subMenus: []
            ),
        ];
    }

    public function requiredPackages(): array
    {
        return ["Core"];
    }
}