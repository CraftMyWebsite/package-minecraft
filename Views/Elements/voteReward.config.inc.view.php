<?php

/* @var string $varName */

?>
<div class="mt-3 mb-3">
    <div class="form-group">
        <label for="<?=$varName?>_commands">Commandes :</label>
        <input class="form-control" type="text" id="<?=$varName?>_commands" name="<?=$varName?>_commands" placeholder="say {player} is the best !" required>
    </div>
    <div class="form-group">
        <label for="<?=$varName?>_servers">Serveurs :</label>
        <select id="<?=$varName?>_servers" name="<?=$varName?>_servers[]" class="form-control" required multiple>

        </select>
    </div>
    <h6>A savoir.</h6>
    <p>- Séparez vos commandes avec un pipe : | (Alt Gr + 6) si vous souhaitez en exécuter plusieurs<br>
    - Ne pas utiliser "/" dans les commandes<br>
    - Utilisez {player} pour récupérez le nom du joueur qui vote.<br>
    - CTRL+CLIQUE pour sélectionner plusieurs serveurs</p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectElement = document.getElementById('<?=$varName?>_servers');
        const selectedServers = []; // Ajoutez ici les IDs des serveurs déjà sélectionnés si nécessaire
        getServers(selectElement, selectedServers);
    });

    async function getServers(select_server, servers) {
        try {
            let response = await fetch('../minecraft/servers/list/');
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