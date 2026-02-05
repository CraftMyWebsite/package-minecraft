<?php

use CMW\Controller\Minecraft\MinecraftController;
use CMW\Entity\Minecraft\MinecraftServerEntity;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;

$title = LangManager::translate('minecraft.servers.title');
$description = LangManager::translate('minecraft.servers.desc');

/* @var \CMW\Entity\Users\UserEntity[] $users */
/* @var \CMW\Model\Minecraft\MinecraftHistoryModel $historyModel */
?>

<div class="page-title">
    <h3><i class="fas fa-cube"></i> <?= LangManager::translate('minecraft.menu.history') ?></h3>
</div>


<div class="center-flex">
    <div class="card flex-content-lg">
        <div class="table-container">
            <table id="table1">
                <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Récompenses</th>
                    <th class="text-center"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <div class="avatar-text">
                                <img class="avatar-rounded" src="<?= $user->getUserPicture()->getImage() ?>" alt="">
                                <div>
                                    <b><?= $user->getPseudo() ?></b>
                                    <p class="text-sm"><?= $user->getCreated() ?></p>
                                </div>
                            </div>
                        </td>
                        <td><?= $historyModel->countRewardForUserId($user->getId()) ?></td>

                        <td class="text-center space-x-2">
                            <a href="history/<?= $user->getId() ?>" class="text-info"><i class="fa-solid fa-eye"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>