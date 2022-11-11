<?php

use CMW\Controller\Minecraft\MinecraftController;
use CMW\Entity\Minecraft\MinecraftServerEntity;
use CMW\Manager\Lang\LangManager;
use CMW\Utils\SecurityService;

$title = LangManager::translate("minecraft.servers.title");
$description = LangManager::translate("minecraft.servers.desc");

/* @var MinecraftServerEntity[] $servers */
?>

<div class="content">

    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= LangManager::translate("minecraft.servers.title") ?></h3>

                        <div class="text-center">
                            <button class="btn btn-success" data-toggle="modal" data-target="#serverAdd">
                                <?= LangManager::translate("minecraft.servers.add") ?>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">

                        <div id="accordion">

                            <?php foreach ($servers as $server) : ?>
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block w-100 collapsed" data-toggle="collapse"
                                               href="#collapse<?= $server->getServerId() ?>" aria-expanded="false">
                                                <?= $server->getServerName() ?>

                                                <span class="badge badge-<?= ($server->getServerStatus() === -1) ? 'warning' :
                                                    (($server->getServerStatus() === 0) ? 'danger' : 'success') ?>">
                                                </span>

                                                <small class="float-right">
                                                    <i class="fa-solid fa-users mr-1"></i>
                                                    <?= MinecraftController::pingServer($server->getServerIp(), $server->getServerPort())->getPlayersOnline() ?>
                                                </small>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse<?= $server->getServerId() ?>" class="collapse"
                                         data-parent="#accordion">
                                        <div class="card-body">
                                            <form action="" method="post">
                                                <?php (new SecurityService())->insertHiddenToken() ?>

                                                <input type="text" name="serverId" value="<?= $server->getServerId() ?>"
                                                       hidden>

                                                <div class="input-form mb-3">
                                                    <label for="cmwlPort"><?= LangManager::translate("minecraft.servers.modal.add.name") ?></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-heading"></i></span>
                                                        </div>
                                                        <input type="text" name="name" class="form-control"
                                                               placeholder="CraftMySkyBlock"
                                                               value="<?= $server->getServerName() ?>"
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="input-form mb-3">
                                                    <label for="cmwlPort"><?= LangManager::translate("minecraft.servers.modal.add.ip") ?></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-at"></i></span>
                                                        </div>
                                                        <input type="text" name="ip" class="form-control"
                                                               placeholder="mc.craftmywebsite.fr"
                                                               value="<?= $server->getServerIp() ?>"
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="input-form mb-3">
                                                    <label for="cmwlPort"><?= LangManager::translate("minecraft.servers.modal.add.port") ?></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-flag"></i></span>
                                                        </div>
                                                        <input type="number" name="port" id="port" class="form-control"
                                                               placeholder="25565"
                                                               value="<?= $server->getServerPort() ?>">
                                                    </div>
                                                </div>

                                                <div class="input-form mb-3">
                                                    <label for="cmwlPort"><?= LangManager::translate("minecraft.servers.modal.add.cmwl_port") ?></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-flag"></i></span>
                                                        </div>
                                                        <input type="number" name="cmwlPort" id="cmwlPort"
                                                               class="form-control"
                                                               placeholder="24102"
                                                               value="<?= $server->getServerCMWLPort() ?>">

                                                    </div>
                                                    <small><?= LangManager::translate("minecraft.servers.hint_cmwl_port") ?></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="status"><?= LangManager::translate("minecraft.servers.status.title") ?></label>
                                                    <select name="status" id="status" class="form-control">
                                                        <option value="-1" <?= $server->getServerStatus() === -1 ? "selected" : "" ?>>
                                                            <?= LangManager::translate("minecraft.servers.status.maintenance") ?>
                                                        </option>

                                                        <option value="0" <?= $server->getServerStatus() === 0 ? "selected" : "" ?>>
                                                            <?= LangManager::translate("minecraft.servers.status.offline") ?>
                                                        </option>

                                                        <option value="1" <?= $server->getServerStatus() === 1 ? "selected" : "" ?>>
                                                            <?= LangManager::translate("minecraft.servers.status.online") ?>
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="float-right">
                                                    <button onclick="checkCMWLConfig(<?= $server->getServerId() ?>)"
                                                            type="button" class="btn btn-primary mr-5">
                                                        <?= LangManager::translate('minecraft.servers.test_cmwl') ?>
                                                    </button>

                                                    <input type="submit"
                                                           value="<?= LangManager::translate('core.btn.save') ?>"
                                                           class="btn btn-primary">
                                                </div>
                                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#serverDel<?= $server->getServerId() ?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                <!-- Modal Delete verif -->
                                <div class="modal fade" id="serverDel<?= $server->getServerId() ?>"
                                     tabindex="-1" role="dialog"
                                     aria-labelledby="serverDelLabel<?= $server->getServerId() ?>"
                                     aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <?= LangManager::translate("minecraft.servers.modal.delete.title") ?>
                                                    <strong><?= $server->getServerName() ?></strong>
                                                </h5>
                                                <button type="button" class="close"
                                                        data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <?= LangManager::translate("minecraft.servers.modal.delete.body") ?>
                                            </div>

                                            <div class="modal-footer">
                                                <form method="post" action="servers/delete">
                                                    <?php (new SecurityService())->insertHiddenToken() ?>
                                                    <input type="hidden" name="serverId"
                                                           value="<?= $server->getServerId() ?>">
                                                    <button type="submit" class="btn btn-danger">
                                                        <?= LangManager::translate("core.btn.delete") ?>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">
                                                    <?= LangManager::translate("core.btn.close") ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- MODAL ADD SERVER  -->

<div class="modal fade" id="serverAdd"
     tabindex="-1" role="dialog"
     aria-labelledby="serverAddLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="servers/add">
            <?php (new SecurityService())->insertHiddenToken() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <?= LangManager::translate("minecraft.servers.modal.add.title") ?>
                    </h5>
                    <button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="name"><?= LangManager::translate("minecraft.servers.modal.add.name") ?></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-heading"></i></span>
                            </div>
                            <input type="text" name="name" class="form-control"
                                   placeholder="CraftMySkyBlock" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="ip"><?= LangManager::translate("minecraft.servers.modal.add.ip") ?></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-at"></i></span>
                            </div>
                            <input type="text" name="ip" class="form-control"
                                   placeholder="mc.craftmywebsite.fr" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="port"><?= LangManager::translate("minecraft.servers.modal.add.port") ?></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-flag"></i></span>
                            </div>
                            <input type="number" name="port" class="form-control"
                                   placeholder="25565" minlength="1" maxlength="5">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cmwlPort"><?= LangManager::translate("minecraft.servers.modal.add.cmwl_port") ?></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-flag"></i></span>
                            </div>
                            <input type="number" name="cmwlPort" class="form-control"
                                   placeholder="24102" minlength="1" maxlength="5">
                        </div>
                        <small><?= LangManager::translate("minecraft.servers.hint_cmwl_port") ?></small>
                    </div>

                    <div class="form-group">
                        <label for="status"><?= LangManager::translate("minecraft.servers.status.title") ?></label>
                        <select name="status" id="status" class="form-control">
                            <option value="-1" selected>
                                <?= LangManager::translate("minecraft.servers.status.maintenance") ?>
                            </option>
                            <option value="0"><?= LangManager::translate("minecraft.servers.status.offline") ?></option>
                            <option value="1"><?= LangManager::translate("minecraft.servers.status.online") ?></option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <?= LangManager::translate("core.btn.save") ?>
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <?= LangManager::translate("core.btn.close") ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>