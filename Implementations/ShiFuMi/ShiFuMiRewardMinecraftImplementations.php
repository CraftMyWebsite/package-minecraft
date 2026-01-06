<?php

namespace CMW\Implementation\Minecraft\ShiFuMi;

use CMW\Controller\Minecraft\Rewards\shiFuMIMinecraftController;
use CMW\Entity\ShiFuMi\ShiFuMiRewardsEntity;
use CMW\Interface\ShiFuMi\IShiFuMiRewardMethod;
use CMW\Manager\Env\EnvManager;
use JsonException;

class ShiFuMiRewardMinecraftImplementations implements IShiFuMiRewardMethod
{
    public function name(): string
    {
        return 'Minecraft';
    }

    public function varName(): string
    {
        return 'shiFuMiMinecraft';
    }

    public function includeRewardConfigWidgets(?int $rewardId): void
    {
        $varName = $this->varName();
        require_once EnvManager::getInstance()->getValue('DIR') . 'App/Package/Minecraft/Views/Elements/shiFuMiReward.config.inc.view.php';
    }

    public function execRewardActionLogic(): ?string
    {
        try {
            $action = json_encode([
                'commands' => $_POST[$this->varName() . '_commands'],
                'servers' => $_POST[$this->varName() . '_servers'],
            ], JSON_THROW_ON_ERROR);
        } catch (JsonException) {
        }
        return $action;
    }

    public function execReward(ShiFuMiRewardsEntity $reward, int $userId): void
    {
        shiFuMIMinecraftController::getInstance()->execMcNeeds($reward, $userId);
    }
}
