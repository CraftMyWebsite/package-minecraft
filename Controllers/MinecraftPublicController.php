<?php

namespace CMW\Controller\Minecraft;

use CMW\Controller\Users\UsersController;
use CMW\Controller\Users\UsersSessionsController;
use CMW\Entity\Minecraft\MinecraftPingEntity;
use CMW\Entity\Minecraft\MinecraftPingPlayersEntity;
use CMW\Manager\Api\APIManager;
use CMW\Manager\Env\EnvManager;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Router\Link;
use CMW\Manager\Views\View;
use CMW\Model\Minecraft\MinecraftHistoryModel;
use CMW\Model\Minecraft\MinecraftModel;
use CMW\Model\Users\UsersModel;
use CMW\Utils\Redirect;
use CMW\Utils\Utils;
use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;
use JsonException;

/**
 * Class: @MinecraftController
 * @package Minecraft
 * @author CraftMyWebsite Team <contact@craftmywebsite.fr>
 * @version 0.0.1
 */
class MinecraftPublicController extends AbstractController
{
    #[Link('/history', Link::GET, [], '/minecraft')]
    private function adminHistory(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'minecraft.history');

        $userId = UsersSessionsController::getInstance()->getCurrentUser()?->getId();
        if (!$userId) {
            Redirect::redirect(EnvManager::getInstance()->getValue('PATH_SUBFOLDER') . 'login');
        }

        $userHistories = MinecraftHistoryModel::getInstance()->getFullHistoryByUserId($userId);

        View::createPublicView('Minecraft', 'history')
            ->addVariableList(compact('userHistories'))
            ->view();
    }

}
