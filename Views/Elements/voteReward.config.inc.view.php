<?php

/* @var string $varName */
/* @var ?int $rewardId */

use CMW\Utils\Website;
use CMW\Model\Votes\VotesRewardsModel;

if (!is_null($rewardId)) {
    $reward = VotesRewardsModel::getInstance()->getRewardById($rewardId);
    $action = $reward->getAction();

    try {
        $servers = json_decode($action, false, 512, JSON_THROW_ON_ERROR)->servers;
        if (!empty($servers)) {
            $jsonServers = json_encode($servers);
        } else {
            $jsonServers = empty(true);
        }

        $cmd = json_decode($action, false, 512, JSON_THROW_ON_ERROR)->commands;
    } catch (JsonException $e) {
        echo "Internal Error. " . $e;
    }
}
?>
<div class="mt-3">
    <div class="form-group">
        <label for="<?=$varName?>_commands">Commandes :</label>
        <input value="<?=$cmd ?? ""?>" class="input" type="text" id="<?=$varName?>_commands" name="<?=$varName?>_commands" placeholder="say {player} is the best !" required>
    </div>
    <div class="form-group">
        <label for="<?=$varName?>_servers">Serveurs :</label>
        <select id="<?=$varName?>_servers" name="<?=$varName?>_servers[]" class="input" required multiple>
        </select>
    </div>
    <b>A savoir.</b>
    <p>- Séparez vos commandes avec un pipe : | (Alt Gr + 6) si vous souhaitez en exécuter plusieurs<br>
    - Ne pas utiliser "/" dans les commandes<br>
    - Utilisez {player} pour récupérez le nom du joueur qui vote.<br>
    - CTRL+CLIQUE pour sélectionner plusieurs serveurs</p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectElement = document.getElementById('<?=$varName?>_servers');
        const selectedServers =
            <?php if (empty($jsonServers)) {
                echo '[""]';
            } else {
                echo $jsonServers;
            } ?>; // Ajoutez ici les IDs des serveurs déjà sélectionnés si nécessaire
        getServers(selectElement, selectedServers);
    });

    async function getServers(select_server, servers) {
        try {
            let response = await fetch('<?= Website::getUrl() ?>cmw-admin/minecraft/servers/list');
            let jsonData = await response.json();

            for (const [serverId, serverName] of Object.entries(jsonData)) {
                let option = document.createElement("option");
                option.value = serverId;
                option.innerText = serverName;
                option.selected = servers.includes(serverId);
                select_server.appendChild(option);
            }
        } catch (error) {
            console.error('Failed to fetch server list:', error);
        }
    }
</script>