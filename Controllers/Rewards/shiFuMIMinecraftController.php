<?php

namespace CMW\Controller\Minecraft\Rewards;

use CMW\Entity\ShiFuMi\ShiFuMiRewardsEntity;
use CMW\Manager\Api\APIManager;
use CMW\Manager\Package\AbstractController;
use CMW\Model\Minecraft\MinecraftHistoryModel;
use CMW\Model\Minecraft\MinecraftModel;
use CMW\Model\Users\UsersModel;

/**
 * Class: @shiFuMIMinecraftController
 * @package Minecraft
 * @author Zomblard
 * @version 0.0.1
 */
class shiFuMIMinecraftController extends AbstractController
{
    /**
     * @param ShiFuMiRewardsEntity $reward
     * @param int $userId
     */
    public function execMcNeeds(ShiFuMiRewardsEntity $reward, int $userId): void
    {
        $rewardAction = $reward->getAction();
        $userPseudo = UsersModel::getInstance()->getUserById($userId)?->getPseudo();

        $this->sendRewardsToCmwLink($rewardAction, $userPseudo, $userId, $reward->getTitle());
    }

    private function sendRewardsToCmwLink(string $rewardAction, string $userPseudo, int $userId, string $rewardTitle): void
    {
            foreach (json_decode($rewardAction, false, 512, JSON_THROW_ON_ERROR)->servers as $serverId) {
                $server = MinecraftModel::getInstance()->getServerById($serverId);

                $commands = json_decode($rewardAction, false, 512, JSON_THROW_ON_ERROR)->commands;

                $formattedCommands = '';

                // Split commands with pipes
                foreach ($commands as $command) {
                    $formattedCommands .= $command . '|';
                }

                // Remove last pipe
                if ($formattedCommands[-1] === '|') {
                    $formattedCommands = substr($formattedCommands, 0, -1);
                }

                // Replace {player} by the user pseudo
                $formattedCommands = str_replace('{player}', $userPseudo, $formattedCommands);

                // Encode the commands before sending to CMWLink
                $formattedCommands = base64_encode($formattedCommands);

                // Send the commands to the server
                //TODO : ShiFuMi
                APIManager::getRequest("http://{$server?->getServerIp()}:{$server?->getServerCMWLPort()}/votes/send/reward/$userPseudo/$formattedCommands",
                    cmwlToken: $server?->getServerCMWToken());

                MinecraftHistoryModel::getInstance()->addHistory(
                    $userId,
                    $server?->getServerName() ?? 'N/A',
                    'Victoire au ShiFuMi',
                    'Vous avez obtenus ' . $rewardTitle . ' en gagnant une partie'
                );
            }
    }
}
