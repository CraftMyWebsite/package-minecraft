<?php

use CMW\Controller\Core\PackageController;
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
        <label for="<?= $varName ?>_commands">Commandes :</label>
        <input value="<?= $command ?>" class="input" type="text" id="<?= $varName ?>_commands" name="<?= $varName ?>_commands" placeholder="say {player} is the best !" required>
    </div>
    <div class="form-group">
        <b>Serveurs :</b><br>
        <?php foreach ($servers as $server):
            $serverId = ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName . '_server' . $server->getServerId() . '_', $itemId); ?>
        <div>
            <label class="toggle">
                <p class="toggle-label"><?= $server->getServerName() ?> - <?= $server->getFormattedServerCMLStatus() ?> <?= $server->getServerCMLStatus() ? '' : 'CMW - Link Inactif !' ?></p>
                <input type="checkbox" class="toggle-input" value="<?= $server->getServerId() ?>" name="<?= $varName ?>_server<?= $server->getServerId() ?>_"
                    <?= $server->getServerId() === (int) $serverId ? 'checked' : '' ?>>
                <div class="toggle-slider"></div>
            </label>
        </div>
        <?php endforeach; ?>
    </div>
    <hr>
    <b>A savoir.</b>
    <p>- Séparez vos commandes avec un pipe : | (Alt Gr + 6) si vous souhaitez en exécuter plusieurs<br>
        - Ne pas utiliser "/" dans les commandes<br>
        - Utilisez {player} pour récupérez le nom du joueur qui vote.<br>
        - CTRL+CLIQUE pour sélectionner plusieurs serveurs</p>
</div>