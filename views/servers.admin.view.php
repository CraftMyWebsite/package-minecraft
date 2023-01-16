<?php

use CMW\Controller\Minecraft\MinecraftController;
use CMW\Entity\Minecraft\MinecraftServerEntity;
use CMW\Manager\Lang\LangManager;
use CMW\Utils\SecurityService;

$title = LangManager::translate("minecraft.servers.title");
$description = LangManager::translate("minecraft.servers.desc");

/* @var MinecraftServerEntity[] $servers */
?>

<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fas fa-cube"></i> <span class="m-lg-auto">Minecraft</span></h3>
</div>


<div class="row">
    <div class="col-12 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("minecraft.servers.modal.add.title") ?></h4>
            </div>
            <div class="card-body">
                <form method="post" action="servers/add">
                    <?php (new SecurityService())->insertHiddenToken() ?>
                            <h6><?= LangManager::translate("minecraft.servers.modal.add.name") ?> :</h6>
                            <div class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" name="name" required
                                       placeholder="CraftMySkyBlock">
                                <div class="form-control-icon">
                                    <i class="fas fa-heading"></i>
                                </div>
                            </div>
                            <h6><?= LangManager::translate("minecraft.servers.modal.add.ip") ?> :</h6>
                            <div class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" name="ip" required
                                       placeholder="mc.craftmywebsite.fr">
                                <div class="form-control-icon">
                                    <i class="fa-solid fa-at"></i>
                                </div>
                            </div>
                            <h6><?= LangManager::translate("minecraft.servers.modal.add.port") ?> :</h6>
                            <div class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" name="port" 
                                       placeholder="25565">
                                <div class="form-control-icon">
                                    <i class="fa-solid fa-door-open"></i>
                                </div>
                            </div>
                            <h6><?= LangManager::translate("minecraft.servers.modal.add.cmwl_port") ?> :</h6>
                            <div class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" name="cmwlPort" 
                                       placeholder="24102">
                                <div class="form-control-icon">
                                    <i class="fa-solid fa-door-open"></i>
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
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary"><?= LangManager::translate("core.btn.add") ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("minecraft.servers.list.title") ?></h4>
            </div>
            <div class="card-body">
                <table class="table" id="table1">
                    <thead>
                    <tr>
                        <th class="text-center"><?= LangManager::translate("minecraft.servers.list.name") ?></th>
                        <th class="text-center"><?= LangManager::translate("minecraft.servers.list.players") ?></th>
                        <th class="text-center"><?= LangManager::translate("minecraft.servers.list.ip") ?></th>
                        <th class="text-center"><?= LangManager::translate("minecraft.servers.list.state") ?></th>
                        <th class="text-center"><?= LangManager::translate("minecraft.servers.list.action") ?></th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    <?php foreach ($servers as $server) : ?>
                        <tr>
                            <td><?= $server->getServerName() ?></td>
                            <td>
                                <?php if (MinecraftController::pingServer($server->getServerIp(), $server->getServerPort())->getPlayersOnline()==0) {
                                        echo LangManager::translate("minecraft.servers.list.noplayer");
                                    } else {
                                        echo "<b class='text-success'>" . MinecraftController::pingServer($server->getServerIp(), $server->getServerPort())->getPlayersOnline(). "</b>";
                                    }?>
                            </td>
                            <td><?= $server->getServerIp() ?>:<?= $server->getServerPort()?></td>
                            <td>
                                <?php if ($server->getServerStatus()==1) {
                                    echo "<span class='text-success'>" . LangManager::translate("minecraft.servers.status.online") . "</span>";
                                }?>
                                <?php if ($server->getServerStatus()==0) {
                                    echo "<span class='text-danger'>" . LangManager::translate("minecraft.servers.status.offline") . "</span>";
                                }?>
                                <?php if ($server->getServerStatus()==-1) {
                                    echo "<span class='text-warning'>" . LangManager::translate("minecraft.servers.status.maintenance") . "</span>";
                                }?>
                            </td>
                            <td>
                                <a type="button" data-bs-toggle="modal" data-bs-target="#edit-<?= $server->getServerId() ?>">
                                    <i class="text-primary me-3 fas fa-edit"></i>
                                </a>
                                <a type="button" data-bs-toggle="modal" data-bs-target="#delete-<?= $server->getServerId() ?>">
                                    <i class="text-danger fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <div class="modal modal-lg fade text-left" id="edit-<?= $server->getServerId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title white" id="myModalLabel160"><?= LangManager::translate("minecraft.servers.modal.editing") ?> <?= $server->getServerName() ?></h5>
                                    </div>
                                    <div class="modal-body">
                                        <form id="serveredit-<?= $server->getServerId() ?>" method="post" action="">
                                            <?php (new SecurityService())->insertHiddenToken() ?>
                                                <input type="text" name="serverId" value="<?= $server->getServerId() ?>" hidden>
                                            <h6><?= LangManager::translate("minecraft.servers.modal.add.name") ?> :</h6>
                                            <div class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control" name="name" required placeholder="CraftMySkyBlock" value="<?= $server->getServerName() ?>">
                                                <div class="form-control-icon">
                                                    <i class="fas fa-heading"></i>
                                                </div>
                                            </div>
                                            <h6><?= LangManager::translate("minecraft.servers.modal.add.ip") ?> :</h6>
                                            <div class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control" name="ip" required
                                                       placeholder="mc.craftmywebsite.fr" value="<?= $server->getServerIp() ?>">
                                                <div class="form-control-icon">
                                                    <i class="fa-solid fa-at"></i>
                                                </div>
                                            </div>
                                            <h6><?= LangManager::translate("minecraft.servers.modal.add.port") ?> :</h6>
                                            <div class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control" name="port" 
                                                       placeholder="25565" value="<?= $server->getServerPort() ?>">
                                                <div class="form-control-icon">
                                                    <i class="fa-solid fa-door-open"></i>
                                                </div>
                                            </div>
                                            <h6><?= LangManager::translate("minecraft.servers.modal.add.cmwl_port") ?> :</h6>
                                            <div class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control" name="cmwlPort" 
                                                       placeholder="24102" value="<?= $server->getServerCMWLPort() ?>">
                                                <div class="form-control-icon">
                                                    <i class="fa-solid fa-door-open"></i>
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
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                        <i class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.close") ?></span>
                                    </button>
                                    <button onclick="checkCMWLConfig(<?= $server->getServerId() ?>)" class="btn btn-light-primary">
                                        <i class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block"><?= LangManager::translate('minecraft.servers.test_cmwl') ?></span>
                                    </button>
                                    <button type="submit" form="serveredit-<?= $server->getServerId() ?>" class="btn btn-success ml-1" data-bs-dismiss="modal">
                                        <i class="bx bx-check d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.save") ?></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="modal fade text-left" id="delete-<?= $server->getServerId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h5 class="modal-title white" id="myModalLabel160"><?= LangManager::translate("minecraft.servers.modal.delete") ?> <?= $server->getServerName() ?></h5>
                                    </div>
                                    <div class="modal-body">
                                        <?= LangManager::translate("minecraft.servers.modal.deletealert") ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.close") ?></span>
                                        </button>
                                            <form method="post" action="servers/delete">
                                            <?php (new SecurityService())->insertHiddenToken() ?>
                                                <input type="hidden" name="serverId" value="<?= $server->getServerId() ?>">
                                                <button type="submit" class="btn btn-danger ml-1" data-bs-dismiss="modal">
                                                    <i class="bx bx-check d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.delete") ?></span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>