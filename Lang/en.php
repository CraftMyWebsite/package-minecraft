<?php

return [
    "servers" => [
        "title" => "Manage your servers",
        "desc" => "Manage your minecraftservers",
        "modal" => [
            "editing" => "Editing :",
            "delete" => "Delete :",
            "deletealert" => "Deleting your server is permanent",
            "add" => [
                "title" => "Add a new server",
                "name" => "Server name",
                "ip" => "Server IP",
                "port" => "Server port (optional)",
                "cmwl_port" => "CMW Link port (optional)",
            ],
        ],
        "list" => [
            "title" => "List of your servers",
            "fav" => "Favorite",
            "name" => "Name",
            "players" => "Online Players",
            "ip" => "IP : PORT",
            "state" => "State",
            "action" => "Edit",
            "noplayer" => "No player",
        ],
        "add" => "Add a server",
        "test_cmwl" => "Try CMW Link config",
        "hint_cmwl_port" => 'Si vous n\'avez pas d\'autres ports de libre, utilisez le port par défaut de votre serveur, et pensez à activer l\'option "bindToDefaultPort" dans votre fichier settings.json du plugin CMWLink',
        "status" => [
            "title" => "Status of this server",
            "maintenance" => "Maintenance",
            "offline" => "Offline",
            "online" => "Online",
        ],
        "toasters" => [
            "test_cmw_response_success" => "The CMW Link conf work",
            "test_cmw_response_error" => "The CMW Link conf don't work",
            "delete_server" => "Server deleted",
            "server_add" => "Server added !",
            "server_edit" => "Server edited !",
            "server_fav" => "Fav defined",
            "cmwl_first_install" => [
                "200" => "CMW Link config with success",
                "401" => "Non-authorized connexion",
                "404" => "Url not found",
                "418" => "Internal error",
                "other" => "Undefined error",
            ],
        ],
    ],
    "permissions" => [
        "minecraft" => [
            "servers" => [
                "delete" => "Delete a server",
                "list" => "Show a server",
                "fav" => "Add/Delete a server to favorite",
                "add" => "Add a server",
                "edit" => "Edit a server",
            ],
        ],
    ],
];
