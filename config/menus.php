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
            ],
        ]
    ],


    'menu_3'                 =>  [
        'name'                 => 'Initiatives',
        'menu_icon'           => 'fa-tasks',
        'permissions'           => ['draft-initiative: list', 'implementation-initiative: list', 'shelf-initiative: list'],

        'menu_item'            =>
        [
            [
                'title'      =>  'Drafting stage',
                'url'        =>  'admin.draft-initiatives.index',
                'permission' => 'draft-initiative: list'
            ],
             [
                'title'      =>  'Shelfing stage',
                'url'        =>  'admin.shelf-initiatives.index',
                'permission' => 'shelf-initiative: list'
             ],
            [
                'title'      =>  'Implementation stage',
                'url'        =>  'admin.implementation-initiatives.index',
                'permission' => 'implementation-initiative: list'
            ],

        ]
    ],

    'menu_2'                 =>  [
        'name'                 => 'Initiative setup',
        'menu_icon'           => 'fa-list-alt',
        'permissions'           => ['directorate: list', 'theme: list', 'objective: list', 'initiative-status: list', 'implementation-status: list', 'partner: list', 'request-status: list', 'support-request: list'],

        'menu_item'            =>
        [
            [
                'title'      =>  'Directorates',
                'url'        =>  'admin.directorates.index',
                'permission' => 'directorate: list'
            ],
            [
                'title'      =>  'Themes',
                'url'        =>  'admin.themes.index',
                'permission' => 'theme: list'
            ],
            [
                'title'      =>  'Objectives',
                'url'        =>  'admin.objectives.index',
                'permission' => 'objective: list'
            ],
            [
                'title'      =>  'Initiative Statuses',
                'url'        =>  'admin.initiative-statuses.index',
                'permission' => 'initiative-status: list'
            ],
            [
                'title'      =>  'Implementation Statuses',
                'url'        =>  'admin.implementation-statuses.index',
                'permission' => 'implementation-status: list'
            ],
            [
                'title'      =>  'Partners',
                'url'        =>  'admin.partners.index',
                'permission' => 'partner: list'
            ],
            [
                'title'      =>  'Request Statuses',
                'url'        =>  'admin.request-statuses.index',
                'permission' => 'request-status: list'
            ],
            [
                'title'      =>  'Support Requests',
                'url'        =>  'admin.support-requests.index',
                'permission' => 'support-request: list'
            ]
        ]
    ],

    'menu_5'                 =>  [
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
/*
    'menu_6'                 =>  [
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

    ],*/
];
