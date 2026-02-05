<?php

use CMW\Controller\Minecraft\MinecraftController;
use CMW\Entity\Minecraft\MinecraftServerEntity;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;

$title = LangManager::translate('minecraft.servers.title');
$description = LangManager::translate('minecraft.servers.desc');

/* @var MinecraftServerEntity[] $servers */
?>

<div class="page-title">
    <h3><i class="fas fa-cube"></i> <?= LangManager::translate('minecraft.menu.servers') ?></h3>
    <a href="https://craftmywebsite.fr/market/details/cmw-link" target="_blank" class="btn-primary"><?= LangManager::translate('minecraft.servers.download') ?></a>
</div>


<div class="grid-3">
    <div class="card">
        <h6><?= LangManager::translate('minecraft.servers.modal.add.title') ?></h6>
        <form method="post" action="servers/add">
            <?php SecurityManager::getInstance()->insertHiddenToken() ?>
            <label for="name"><?= LangManager::translate('minecraft.servers.modal.add.name') ?> :</label>
            <div class="input-group">
                <i class="fas fa-heading"></i>
                <input type="text" id="name" name="name" required
                       placeholder="CraftMySkyBlock">
            </div>
            <label for="ip"><?= LangManager::translate('minecraft.servers.modal.add.ip') ?> :</label>
            <div class="input-group">
                <i class="fa-solid fa-at"></i>
                <input type="text" id="ip" name="ip" required
                       placeholder="mc.craftmywebsite.fr">
            </div>
            <label for="port"><?= LangManager::translate('minecraft.servers.modal.add.port') ?> :</label>
            <div class="input-group">
                <i class="fa-solid fa-door-open"></i>
                <input type="number" id="port" name="port"
                       placeholder="25565">
            </div>
            <label for="cmwlPort"><?= LangManager::translate('minecraft.servers.modal.add.cmwl_port') ?> :</label>
            <div class="input-group">
                <i class="fa-solid fa-door-open"></i>
                <input type="number" id="cmwlPort" name="cmwlPort"
                       placeholder="24102">
            </div>
            <small><?= LangManager::translate('minecraft.servers.hint_cmwl_port') ?></small>
            <label for="status" class="mt-2"><?= LangManager::translate('minecraft.servers.status.title') ?></label>
            <select name="status" id="status">
                <option value="-1" selected>
                <?= LangManager::translate('minecraft.servers.status.maintenance') ?>
                </option>
                <option value="0"><?= LangManager::translate('minecraft.servers.status.offline') ?></option>
                <option value="1"><?= LangManager::translate('minecraft.servers.status.online') ?></option>
            </select>
            <button type="submit" class="btn-primary btn-center loading-btn mt-3" data-loading-btn="<?= LangManager::translate('core.btn.add') ?>..."><?= LangManager::translate('core.btn.add') ?></button>
        </form>
    </div>
    <div class="card col-span-2">
        <h6><?= LangManager::translate('minecraft.servers.list.title') ?></h6>
        <div class="table-container">
            <table id="table1">
                <thead>
                <tr>
                    <th><?= LangManager::translate('minecraft.servers.list.fav') ?></th>
                    <th><?= LangManager::translate('minecraft.servers.list.name') ?></th>
                    <th><?= LangManager::translate('minecraft.servers.list.players') ?></th>
                    <th><?= LangManager::translate('minecraft.servers.list.ip') ?></th>
                    <th><?= LangManager::translate('minecraft.servers.list.state') ?></th>
                    <th class="text-center">CMW Link</th>
                    <th class="text-center">CMW PORT</th>
                    <th class="text-center"><?= LangManager::translate('minecraft.servers.list.action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($servers as $server): ?>
                    <tr>
                        <td>
                            <a href="servers/fav/<?= $server->getServerId() ?>" class="servFav">
                                <i class="fa-solid fa-star"
                                    <?= $server->getIsServerFav() ? 'style="color: goldenrod"' : '' ?>>
                                </i>

                            </a>
                        </td>
                        <td><?= $server->getServerName() ?></td>
                        <td>
                            <?= (MinecraftController::pingServer($server->getServerIp(), $server->getServerPort())->getPlayersOnline() === 0)
                                ? LangManager::translate('minecraft.servers.list.noplayer')
                                : "<b class='text-success'>" . MinecraftController::pingServer($server->getServerIp(), $server->getServerPort())->getPlayersOnline() . '</b>' ?>
                        </td>
                        <td><?= $server->getServerIp() ?><?= !is_null($server->getServerPortFormatted()) ? ':' : '' ?><?= $server->getServerPortFormatted() ?></td>
                        <td>
                            <?= $server->showServerStatusFormatted() ?>
                        </td>
                        <td class="text-center">
                            <?= $server->getFormattedServerCMLStatus() ?>
                        </td>
                        <td class="text-center">
                            <b><?= $server->getServerCMWLPort() ?></b>
                        </td>
                        <td class="text-center space-x-2">
                            <a onclick="showLoadingIcon(this); return true;" href="servers/cmwl/test/<?= $server->getServerId() ?>" ><i class="fa-solid fa-link text-success"></i></a>
                            <button data-modal-toggle="modal-edit-<?= $server->getServerId() ?>" type="button"><i class="text-info fas fa-edit"></i></button>
                            <button data-modal-toggle="modal-delete-<?= $server->getServerId() ?>" type="button"><i class="text-danger fas fa-trash-alt"></i></button>
                        </td>
                    </tr>

                    <div id="modal-edit-<?= $server->getServerId() ?>" class="modal-container">
                        <div class="modal-xl">
                            <div class="modal-header">
                                <h6><?= LangManager::translate('minecraft.servers.modal.editing') ?> <?= $server->getServerName() ?></h6>
                                <button type="button" data-modal-hide="modal-edit-<?= $server->getServerId() ?>"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <form id="serveredit-<?= $server->getServerId() ?>" method="post" action="">
                                <?php SecurityManager::getInstance()->insertHiddenToken() ?>
                            <div class="modal-body">
                                <div class="grid-2">
                                    <div>
                                        <input type="text" name="serverId" value="<?= $server->getServerId() ?>"
                                               hidden>
                                        <label for="name"><?= LangManager::translate('minecraft.servers.modal.add.name') ?> :</label>
                                        <div class="input-group">
                                            <i class="fas fa-heading"></i>
                                            <input type="text" id="name" name="name" required
                                                   placeholder="CraftMySkyBlock"
                                                   value="<?= $server->getServerName() ?>">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="ip"><?= LangManager::translate('minecraft.servers.modal.add.ip') ?> :</label>
                                        <div class="input-group">
                                            <i class="fa-solid fa-at"></i>
                                            <input type="text" id="ip" name="ip" required
                                                   placeholder="mc.craftmywebsite.fr"
                                                   value="<?= $server->getServerIp() ?>">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="port"><?= LangManager::translate('minecraft.servers.modal.add.port') ?> :</label>
                                        <div class="input-group">
                                            <i class="fa-solid fa-door-open"></i>
                                            <input type="number" id="port" name="port"
                                                   placeholder="25565" value="<?= $server->getServerPortFormatted() ?>">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="cmwlPort"><?= LangManager::translate('minecraft.servers.modal.add.cmwl_port') ?> :</label>
                                        <div class="input-group">
                                            <i class="fa-solid fa-door-open"></i>
                                            <input type="number" id="cmwlPort" name="cmwlPort"
                                                   placeholder="24102" value="<?= $server->getServerCMWLPort() ?>">
                                        </div>
                                    </div>
                                </div>
                                <label for="status" class="mt-2"><?= LangManager::translate('minecraft.servers.status.title') ?></label>
                                <select name="status" id="status">
                                    <option value="-1" <?= $server->getServerStatus() === -1 ? 'selected' : '' ?>>
                                        <?= LangManager::translate('minecraft.servers.status.maintenance') ?>
                                    </option>
                                    <option value="0" <?= $server->getServerStatus() === 0 ? 'selected' : '' ?>>
                                        <?= LangManager::translate('minecraft.servers.status.offline') ?>
                                    </option>
                                    <option value="1" <?= $server->getServerStatus() === 1 ? 'selected' : '' ?>>
                                        <?= LangManager::translate('minecraft.servers.status.online') ?>
                                    </option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="submit"
                                        class="btn-primary"><?= LangManager::translate('core.btn.save') ?>
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                    <div id="modal-delete-<?= $server->getServerId() ?>" class="modal-container">
                        <div class="modal">
                            <div class="modal-header-danger">
                                <h6><?= LangManager::translate('minecraft.servers.modal.delete') ?> <?= $server->getServerName() ?></h6>
                                <button type="button" data-modal-hide="modal-delete-<?= $server->getServerId() ?>"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="modal-body">
                                <?= LangManager::translate('minecraft.servers.modal.deletealert') ?>
                            </div>
                            <div class="modal-footer">
                                <form method="post" action="servers/delete">
                                    <?php SecurityManager::getInstance()->insertHiddenToken() ?>
                                    <input type="hidden" name="serverId" value="<?= $server->getServerId() ?>">
                                    <button type="submit" class="btn-danger">
                                        <?= LangManager::translate('core.btn.delete') ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function showLoadingIcon(element) {
        element.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
    }
</script>