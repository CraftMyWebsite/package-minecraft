<?php

use CMW\Controller\Core\PackageController;
use CMW\Model\Minecraft\MinecraftModel;
use CMW\Model\Shop\Item\ShopItemsVirtualRequirementModel;

/* @var ?int $itemId */
/* @var string $varName */

$servers = minecraftModel::getInstance()->getServers();

?>
<div class="card-in-card p-3">
    <h5>Message : </h5>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" value="1" id="<?=$varName?>active_message" name="<?=$varName?>active_message" <?= ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName.'active_message',$itemId) ? 'checked' : '' ?>>
        <label class="form-check-label" for="<?=$varName?>active_message">Envoyer un message : <i data-bs-toggle="tooltip" title="Vous pouvez envoyer un message à tout le serveur quand un joueur à acheter cet article" class="fa-sharp fa-solid fa-circle-question"></i></label>
    </div>
    <div class="row">
        <!--TODO : Permettre d'en ajouter autant qu'on veut-->
        <div class="col-12 col-lg-2">
            <label for="<?=$varName?>message_server">Serveur :</label>
            <select class="form-select" id="<?=$varName?>message_server" name="<?=$varName?>message_server">
                <?php foreach ($servers as $server) : ?>
                <option value="<?= $server->getServerId() ?>"><?= $server->getServerName() ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 col-lg-10">
            <label for="<?=$varName?>message">Message à envoyer :</label>
            <input value="<?= ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName.'message',$itemId) ?>"
                   placeholder="%user% viens d'acheter %item% sur la boutique"
                   type="text"
                   name="<?=$varName?>message"
                   id="<?=$varName?>message"
                   class="form-control"
            >
        </div>
    </div>
</div>

<div class="card-in-card p-3 mt-4">
    <h5>Exécution en jeu : </h5>
    <div class="row">
        <!--TODO : Permettre d'en ajouter autant qu'on veut-->
        <div class="col-12 col-lg-2">
            <label for="<?=$varName?>command_server">Serveur :</label>
            <select class="form-select" id="<?=$varName?>command_server" name="<?=$varName?>command_server">
                <?php foreach ($servers as $server) : ?>
                    <option value="<?= $server->getServerId() ?>"><?= $server->getServerName() ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 col-lg-10">
            <label for="<?=$varName?>command">Commande :</label>
            <input value="<?= ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName.'command',$itemId) ?>"
                   placeholder="say bonjour tout le monde"
                   type="text"
                   name="<?=$varName?>command"
                   id="<?=$varName?>command"
                   class="form-control"
                   required
            >
        </div>
    </div>
</div>

<!-- TODO : A faire plus tard
<div class="card-in-card p-3 mt-4">
    <h5>Exécution sur le site : </h5>
    <div>
        <label for="<?=$varName?>command">Attribué un rôle sur le site :</label>
        <input value="<?= ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName.'command',$itemId) ?>"
               placeholder="say bonjour tout le monde"
               type="text"
               name="<?=$varName?>command"
               id="<?=$varName?>command"
               class="form-control"
        >
    </div>
    <div class="mt-2">
        <?php if (PackageController::isInstalled("Forum")): ?>
            <label for="<?=$varName?>command">Attribué un rôle sur le forum :</label>
            <input value="<?= ShopItemsVirtualRequirementModel::getInstance()->getSetting($varName.'command',$itemId) ?>"
                   placeholder="say bonjour tout le monde"
                   type="text"
                   name="<?=$varName?>command"
                   id="<?=$varName?>command"
                   class="form-control"
            >
        <?php endif; ?>
    </div>

</div>
-->