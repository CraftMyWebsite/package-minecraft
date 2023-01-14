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
        "list" => [
            "title" => "Liste de vos serveurs",
            "name" => "Nom",
            "players" => "Joueurs en ligne",
            "ip" => "IP : PORT",
            "state" => "État",
            "action" => "Action",
            "noplayer" => "Aucun joueur",
        ],
        "add" => "Ajouter un serveur",
        "test_cmwl" => "Tester la liaison CMW Link",
        "hint_cmwl_port" => 'Si vous n\'avez pas d\'autres ports de libre, utilisez le port par défaut de votre serveur, et pensez à activer l\'option "bindToDefaultPort" dans votre fichier settings.json du plugin CMWLink',
        "status" => [
            "title" => "Status du serveur",
            "maintenance" => "Maintenance",
            "offline" => "Hors-Ligne",
            "online" => "En ligne",
        ],
        "toasters" => [
            "test_cmw_response_success" => "Configuration CMW Link fonctionnelle",
            "test_cmw_response_error" => "Configuration CMW Link non fonctionnelle",
            "server_delete" => "Serveur supprimé !",
            "server_add" => "Serveur ajouté !",
            "server_edit" => "Serveur modifié !",
            "cmwl_first_install" => [
                "200" => "Configuration de CMW Link réussie",
                "401" => "Connexion non autorisée",
                "404" => "Route non trouvée",
                "418" => "Erreur interne",
                "other" => "Erreur non identifiée",
            ],
        ],
    ],

];
