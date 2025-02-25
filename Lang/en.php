<?php

return [
    'servers' => [
        'title' => 'Manage your servers',
        'desc' => 'Manage your minecraftservers',
        'download' => 'Download CMW-Link plugin',
        'modal' => [
            'editing' => 'Editing :',
            'delete' => 'Delete :',
            'deletealert' => 'Deleting your server is permanent',
            'add' => [
                'title' => 'Add a new server',
                'name' => 'Server name',
                'ip' => 'Server IP',
                'port' => 'Server port (optional)',
                'cmwl_port' => 'CMW Link port (optional)',
            ],
        ],
        'list' => [
            'title' => 'List of your servers',
            'fav' => 'Favorite',
            'name' => 'Name',
            'players' => 'Online Players',
            'ip' => 'IP : PORT',
            'state' => 'State',
            'action' => 'Edit',
            'noplayer' => 'No player',
        ],
        'add' => 'Add a server',
        'test_cmwl' => 'Try CMW Link config',
        'hint_cmwl_port' => "If you have no other free ports, use your server's default port and make sure to enable the bindToDefaultPort option in your settings.json file of the CMWLink plugin.",
        'status' => [
            'title' => 'Status of this server',
            'maintenance' => 'Maintenance',
            'offline' => 'Offline',
            'online' => 'Online',
        ],
        'toasters' => [
            'test_cmw_response_success' => 'The CMW Link conf work',
            'test_cmw_response_error' => "The CMW Link conf don't work",
            'delete_server' => 'Server deleted',
            'server_add' => 'Server added !',
            'server_edit' => 'Server edited !',
            'server_fav' => 'Fav defined',
            'cmwl_first_install' => [
                '200' => 'CMW Link config with success',
                '401' => 'Non-authorized connexion',
                '404' => 'Url not found',
                '418' => 'Internal error',
                'other' => 'Undefined error',
            ],
        ],
    ],
    'permissions' => [
        'minecraft' => [
            'servers' => [
                'delete' => 'Delete',
                'list' => 'Show',
                'fav' => 'Manage favorite',
                'add' => 'Add',
                'edit' => 'Edit',
            ],
        ],
    ],
    'implementations' => [
        'shop' => [
            'desc' => 'Executes in-game commands when your players purchase an item',
            'commands' => 'Commands:',
            'server' => 'Servers:',
            'link-down' => 'CMW - Link Inactive!',
            'know' => '<b>Important Information.</b><p>- Separate your commands with a pipe: | (Alt Gr + 6) if you want to execute multiple commands<br>- Do not use "/" in the commands<br>- Use {player} to retrieve the player\'s name.<br></p>',
        ],
        'votes' => [
            'commands' => 'Commands:',
            'server' => 'Servers:',
            'know' => '<b>Important Information.</b><p>- Do not use "/" in the commands<br>- Use {player} to retrieve the player\'s name.<br>- CTRL+CLICK to select multiple servers</p>',
        ],
    ],
];
