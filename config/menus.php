<?php

use Illuminate\Support\Facades\Auth;

return [
    'menu_1'                 =>  [
        'name'                 => 'User Managments',
        'menu_icon'           => 'fa-user',
        'permissions'           => ['role: list', 'user: list'],

        'menu_item'            =>
        [
            [
                'title'        => 'Roles',
                'url'          => 'admin.roles.index',
                'permission'   => 'role: list'

            ],

            [
                'title'      =>  'Users',
                'url'        =>  'admin.users.index',
                'permission' => 'user: list'
            ]
        ]
    ],

    'menu_2'                 =>  [
        'name'                 => 'Countries & Regions',
        'menu_icon'           => 'fas fa-globe',
        'permissions'           => ['region: list', 'zone: list', 'country: list'],
        'menu_item'            =>
        [
            [
                'title'      =>  'Countries',
                'url'        =>  'admin.countries.index',
                'permission'   => 'country: list'
            ],
            [
                'title'      =>  'Regions',
                'url'        =>  'admin.regions.index',
                'permission'   => 'region: list'
            ],
            [
                'title'      =>  'Zones',
                'url'        =>  'admin.zones.index',
                'permission'   => 'zone: list'
            ],

        ]
    ],

    'menu_3'                 =>  [
        'name'                 => 'Settings',
        'menu_icon'           => 'fas fa-cog',
        'permissions'           => [
             'email: list', 'setting: list', 'help: list',  'site-admin: list','notification: list',
        ],
        'menu_item'            =>
        [

            [
                'title'      =>  'Email Templates',
                'url'        =>  'admin.emails.index',
                'permission'   => 'email: list'
            ],

            [
                'title'      =>  'Configuration',
                'url'        =>  'admin.settings.index',
                'permission'   => 'setting: list'
            ],

            [
                'title'      =>  'Helps',
                'url'        =>  'admin.helps.index',
                'permission'   => 'help: list'
            ],
            [
                'title'      =>  'Site Admin',
                'url'        =>  'admin.siteAdmins.index',
                'permission'   => 'site-admin: list'
            ],
            [
                'title'      =>  'Notifications',
                'url'        =>  'admin.notifications.index',
                'permission'   => 'notification: list'
            ],
           

        ]
    ],
   
    'menu_4'                 =>  [
        'name'                 => 'Log & Exceptions',
        'menu_icon'           => 'fa-bug',
        'permissions'           => ['audit: list', 'custom-exception: list'],
        'menu_item'            =>
        [
            [
                'title'      =>  'Activity Log',
                'url'        =>  'admin.audit.index',
                'permission'   => 'audit: list'
            ],
            [
                'title'      =>  'Login Attempts',
                'url'        =>  'admin.login-attempts.index',
                'permission'   => 'login-attempt: list'
            ],
            [
                'title'      =>  'Custom Exception',
                'url'        =>  'admin.custom-exceptions.index',
                'permission'   => 'custom-exception: list'
            ],
        ],

    ],
];
