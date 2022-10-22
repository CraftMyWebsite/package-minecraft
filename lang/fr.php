<?php

return [
    "servers" => [
        "title" => "Gérez vos serveurs",
        "desc" => "Gérez vos serveurs Minecraft",
        "modal" => [
            "delete" => [
                "title" => "Suppression de votre serveur ",
                "body" => "La suppression de votre serveur est définitive"
            ],
            "add" => [
                "title" => "Ajouter un nouveau serveur",
                "name" => "Nom de votre serveur",
                "ip" => "IP de votre serveur",
                "port" => "Port de votre serveur (optionnel)",
                "cmwl_port" => "Port de CMW Link (optionnel)",
            ],
        ],
        "add" => "Ajouter un serveur",
        "test_cmwl" => "Tester la liaison CMW Link",
        "test_cmw_response" => "Configuration CMW Link fonctionnelle",
        "hint_cmwl_port" => 'Si vous n\'avez pas d\'autres ports de libre, utilisez le port par défaut de votre serveur, et pensez à activer l\'option "bindToDefaultPort" dans votre fichier settings.json du plugin CMWLink',
        "status" => [
            "title" => "Status du serveur",
            "maintenance" => "Maintenance",
            "offline" => "Hors-Ligne",
            "online" => "En ligne",
        ],
    ],

];
