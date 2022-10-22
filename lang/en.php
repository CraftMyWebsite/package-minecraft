<?php

return [
    "servers" => [
        "title" => "Manage your servers",
        "desc" => "Manage your minecraftservers",
        "modal" => [
            "delete" => [
                "title" => "Delete your server ",
                "body" => "The delete is permanent"
            ],
            "add" => [
                "title" => "Add a new server",
                "name" => "Server name",
                "ip" => "Server IP",
                "port" => "Server port (optional)",
                "cmwl_port" => "CMW Link port (optional)",
            ],
        ],
        "add" => "Add a server",
        "test_cmwl" => "Try CMW Link config",
        "test_cmw_response" => "The CMW Link conf work",
        "hint_cmwl_port" => 'Si vous n\'avez pas d\'autres ports de libre, utilisez le port par défaut de votre serveur, et pensez à activer l\'option "bindToDefaultPort" dans votre fichier settings.json du plugin CMWLink',
        "status" => [
            "title" => "Status of this server",
            "maintenance" => "Maintenance",
            "offline" => "Offline",
            "online" => "Online",
        ],
    ],

];
