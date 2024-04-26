<?php

namespace CMW\Controller\Minecraft\Rewards;

use CMW\Entity\Votes\VotesRewardsEntity;
use CMW\Entity\Votes\VotesSitesEntity;
use CMW\Manager\Api\APIManager;
use CMW\Manager\Package\AbstractController;
use CMW\Model\Minecraft\MinecraftModel;
use CMW\Model\Users\UsersModel;
use CMW\Model\Votes\VotesConfigModel;
use JsonException;


/**
 * Class: @VoteMinecraftController
 * @package Minecraft
 * @author Zomblard
 * @version 1.0
 */
class VoteMinecraftController extends AbstractController
{
    /**
     * @param \CMW\Entity\Votes\VotesRewardsEntity $reward
     * @param int $userId
     */
    public function execMcNeeds(VotesRewardsEntity $reward, VotesSitesEntity $site, int $userId): void
    {
        $rewardAction = $reward->getAction();
        $rewardTitle = $reward->getTitle();
        $siteName = $site->getTitle();
        $userPseudo = UsersModel::getInstance()->getUserById($userId)?->getPseudo();

        if (VotesConfigModel::getInstance()->getConfig()?->isEnableApi())
        {
            $this->sendRewardsToCmwLink($rewardAction, $userPseudo);
            $this->sendVoteToCmwLink($rewardAction, $rewardTitle, $siteName, $userPseudo);
        } else {
            //TODO @Teyir SEND MC NEEDS WITHOUT API
        }
    }

    private function sendRewardsToCmwLink(string $rewardAction, string $userPseudo): void
    {
        try {
            foreach (json_decode($rewardAction, false, 512, JSON_THROW_ON_ERROR)->servers as $serverId) {
                $server = MinecraftModel::getInstance()->getServerById($serverId);

                $cmd = json_decode($rewardAction, false, 512, JSON_THROW_ON_ERROR)->commands;
                $cmd = str_replace("{player}", $userPseudo, $cmd);
                $cmd = base64_encode($cmd);

                echo APIManager::getRequest("http://{$server?->getServerIp()}:{$server?->getServerCMWLPort()}/votes/send/reward/$userPseudo/$cmd",
                    cmwlToken: $server?->getServerCMWToken());
            }
        } catch (JsonException $e) {
            echo "Internal Error. " . $e;
        }
    }

    private function sendVoteToCmwLink(string $rewardAction, string $rewardTitle, string $siteName, string $userPseudo): void
    {
        try {
            foreach (json_decode($rewardAction, false, 512, JSON_THROW_ON_ERROR)->servers as $serverId) {
                $rewardName = base64_encode($rewardTitle);
                $siteName = base64_encode($siteName);

                $server = MinecraftModel::getInstance()->getServerById($serverId);

                echo APIManager::getRequest("http://{$server?->getServerIp()}:{$server?->getServerCMWLPort()}/votes/send/validate/$userPseudo/$siteName/$rewardName",
                    cmwlToken: $server?->getServerCMWToken());
            }
        } catch (JsonException $e) {
            echo "Internal Error. " . $e;
        }
    }
}