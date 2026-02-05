<?php

return [
    'servers' => [
        'title' => 'Gérez vos serveurs',
        'desc' => 'Gérez vos serveurs Minecraft',
        'download' => 'Télécharger le plugin CMW-Link',
        'modal' => [
            'editing' => 'Édition de :',
            'delete' => 'Supression de :',
            'deletealert' => 'La suppression de votre serveur est définitive.<br>Si CMW-Link était actif, vous devrez supprimer le fichier settings.json dans votre plugin afin de pouvoir recréer un nouveau lien.',
            'add' => [
                'title' => 'Ajouter un nouveau serveur',
                'name' => 'Nom de votre serveur',
                'ip' => 'IP de votre serveur',
                'port' => 'Port de votre serveur (optionnel)',
                'cmwl_port' => 'Port de CMW Link (optionnel)',
            ],
        ],
        'list' => [
            'title' => 'Liste de vos serveurs',
            'fav' => 'Favoris',
            'name' => 'Nom',
            'players' => 'Joueurs en ligne',
            'ip' => 'IP : PORT',
            'state' => 'État',
            'action' => 'Action',
            'noplayer' => 'Aucun joueur',
        ],
        'add' => 'Ajouter un serveur',
        'test_cmwl' => 'Tester la liaison CMW Link',
        'hint_cmwl_port' => "Si vous n'avez pas d'autres ports de libre, utilisez le port par défaut de votre serveur, et pensez à activer l'option \"bindToDefaultPort\" dans votre fichier settings.json du plugin CMWLink",
        'status' => [
            'title' => 'Statut du serveur',
            'maintenance' => 'Maintenance',
            'offline' => 'Hors-Ligne',
            'online' => 'En ligne',
        ],
        'toasters' => [
            'test_cmw_response_success' => 'Configuration CMW Link fonctionnelle',
            'test_cmw_response_error' => 'Configuration CMW Link non fonctionnelle',
            'server_delete' => 'Serveur supprimé !',
            'server_add' => 'Serveur ajouté !',
            'server_edit' => 'Serveur modifié !',
            'server_fav' => 'Favoris modifié',
            'cmwl_first_install' => [
                '200' => 'Configuration de CMW Link réussie',
                '401' => 'Connexion non autorisée',
                '404' => 'Route non trouvée',
                '418' => 'Erreur interne',
                'other' => 'Erreur non identifiée',
            ],
        ],
    ],
    'permissions' => [
        'minecraft' => [
            'servers' => [
                'delete' => 'Supprimer',
                'list' => 'Afficher',
                'fav' => 'Gérer les favoris',
                'add' => 'Ajouter',
                'edit' => 'Modifier',
            ],
            'history' => 'Consulter l\'historique',
        ],
    ],
    'implementations' => [
        'shop' => [
            'desc' => 'Executes des commandes en jeu quand vos joueurs achète un article',
            'commands' => 'Commandes :',
            'server' => 'Serveurs :',
            'link-down' => 'CMW - Link Inactif !',
            'know' => '<b>A savoir.</b><p>- Séparez vos commandes avec un pipe : | (Alt Gr + 6) si vous souhaitez en exécuter plusieurs<br>- Ne pas utiliser "/" dans les commandes<br>- Utilisez {player} pour récupérer le nom du joueur.<br></p>',
        ],
        'votes' => [
            'commands' => 'Commandes :',
            'server' => 'Serveurs :',
            'know' => '<b>A savoir.</b><p>- Ne pas utiliser "/" dans les commandes<br>- Utilisez {player} pour récupérer le nom du joueur.<br>- CTRL+CLIQUE pour sélectionner plusieurs serveurs</p>',
        ],
    ],
    'menu' => [
        'servers' => 'Gestion des serveurs',
        'history' => 'Historique des récompenses',
        'public' => 'Historique utilisateur des récompenses',
    ],
];
