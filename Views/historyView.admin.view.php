<?php

use CMW\Controller\Minecraft\MinecraftController;
use CMW\Entity\Minecraft\MinecraftServerEntity;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;

$title = LangManager::translate('minecraft.servers.title');
$description = LangManager::translate('minecraft.servers.desc');

/* @var \CMW\Entity\Users\UserEntity $user */
/* @var \CMW\Entity\Minecraft\MinecraftHistoryEntity[] $userHistories */
?>

<div class="page-title">
    <h3><i class="fas fa-cube"></i> <?= $user->getPseudo() ?></h3>
</div>


<div class="center-flex">
    <div class="card flex-content-xl">
        <div class="table-container">
            <table id="table1">
                <thead>
                <tr>
                    <th>Serveur</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($userHistories as $history): ?>
                    <tr>
                        <td>
                            <?= $history->getServerName() ?>
                        </td>
                        <td><?= $history->getTitle() ?></td>
                        <td><?= $history->getDescription() ?></td>
                        <td><?= $history->getCreatedAt() ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>