<?php

use CMW\Controller\Core\PackageController;
use CMW\Manager\Lang\LangManager;
use CMW\Model\Minecraft\MinecraftModel;
use CMW\Model\Shop\Item\ShopItemsVirtualRequirementModel;
use CMW\Utils\Website;

/* @var ?int $itemId */
/* @var string $varName */

$servers = minecraftModel::getInstance()->getServers();
$command = ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName . '_commands', $itemId);
?>
<div class="">
    <div class="form-group">
        <label for="<?= $varName ?>_commands"><?= LangManager::translate('minecraft.implementations.shop.commands') ?></label>
        <input value="<?= $command ?>" class="input" type="text" id="<?= $varName ?>_commands" name="<?= $varName ?>_commands" placeholder="say {player} is the best !" required>
    </div>
    <div class="form-group">
        <b><?= LangManager::translate('minecraft.implementations.shop.server') ?></b><br>
        <?php foreach ($servers as $server):
            $serverId = ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName . '_server' . $server->getServerId() . '_', $itemId); ?>
        <div>
            <label class="toggle">
                <p class="toggle-label"><?= $server->getServerName() ?> - <?= $server->getFormattedServerCMLStatus() ?> <?= $server->getServerCMLStatus() ? '' : LangManager::translate('minecraft.implementations.shop.link-down') ?></p>
                <input type="checkbox" class="toggle-input" value="<?= $server->getServerId() ?>" name="<?= $varName ?>_server<?= $server->getServerId() ?>_"
                    <?= $server->getServerId() === (int) $serverId ? 'checked' : '' ?>>
                <div class="toggle-slider"></div>
            </label>
        </div>
        <?php endforeach; ?>
    </div>
    <hr>
    <?= LangManager::translate('minecraft.implementations.shop.know') ?>
</div>