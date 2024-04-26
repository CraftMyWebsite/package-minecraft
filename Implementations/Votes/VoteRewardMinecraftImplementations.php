<?php

namespace CMW\Implementation\Minecraft\Votes;

use CMW\Controller\Minecraft\Rewards\VoteMinecraftController;
use CMW\Entity\Votes\VotesRewardsEntity;
use CMW\Entity\Votes\VotesSitesEntity;
use CMW\Interface\Votes\IRewardMethod;
use CMW\Manager\Env\EnvManager;
use JsonException;

class VoteRewardMinecraftImplementations implements IRewardMethod
{
    public function name(): string
    {
        return "Minecraft";
    }

    public function varName(): string
    {
        return "minecraft";
    }

    public function includeRewardConfigWidgets(?int $rewardId): void
    {
        $varName = $this->varName();
        require_once EnvManager::getInstance()->getValue("DIR") . "App/Package/Minecraft/Views/Elements/voteReward.config.inc.view.php";
    }

    public function execRewardActionLogic(): ?string
    {
        try {
            $action = json_encode([
                "commands" => filter_input(INPUT_POST, $this->varName()."_commands"),
                "servers" => $_POST[$this->varName().'_servers']
            ], JSON_THROW_ON_ERROR);
        } catch (JsonException) {
        }
        return $action;
    }

    public function execReward(VotesRewardsEntity $reward, VotesSitesEntity $site, int $userId): void
    {
        VoteMinecraftController::getInstance()->execMcNeeds($reward, $site, $userId);
    }
}