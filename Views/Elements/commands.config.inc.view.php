<?php

use CMW\Controller\Core\PackageController;
use CMW\Model\Minecraft\MinecraftModel;
use CMW\Model\Shop\Item\ShopItemsVirtualRequirementModel;
use CMW\Utils\Website;

/* @var ?int $itemId */
/* @var string $varName */

$servers = minecraftModel::getInstance()->getServers();

?>
<div class="card-in-card p-3">
    <div class="form-group">
        <label for="<?=$varName?>_commands">Commandes :</label>
        <input value="<?=$cmd ?? ""?>" class="form-control" type="text" id="<?=$varName?>_commands" name="<?=$varName?>_commands" placeholder="say {player} is the best !" required>
    </div>
    <div class="form-group">
        <h6>Serveurs ou sera executé la commande :</h6>
        <?php foreach ($servers as $server): ?>
            <div class="form-check form-switch">
                <label class="form-check-label"
                       for="server<?= $server->getServerId() ?>"><?= $server->getServerName() ?></label>
                <input class="form-check-input" value="<?= $server->getServerId() ?>" type="checkbox" id="server<?= $server->getServerId() ?>"
                       name="<?= $varName ?>_server<?= $server->getServerId() ?>_">
            </div>
        <?php endforeach; ?>
    </div>
    <h6>A savoir.</h6>
    <p>- Séparez vos commandes avec un pipe : | (Alt Gr + 6) si vous souhaitez en exécuter plusieurs<br>
        - Ne pas utiliser "/" dans les commandes<br>
        - Utilisez {player} pour récupérez le nom du joueur qui vote.<br>
        - CTRL+CLIQUE pour sélectionner plusieurs serveurs</p>
</div>
