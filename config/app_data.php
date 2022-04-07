<?php


return [
    'apps' => [
            0 => [
                'id' => 0,
                'name' => 'Ticketing',
                'subdomain' => 'ticket',
                'activeByDefault' => true
            ],
            1 => [
                'id' => 1,
                'name' => 'Support',
                'subdomain' => 'support',
                'activeByDefault' => false
            ]
        ],
    'user_types' => [
        1 => [
            'user_type' => 'admin',
            'user_group' => 1,
            'permissions' => 'all'
        ],
        2 => [
            'user_type' => 'secondary_admin',
            'user_group' => 2,
            'permissions' => 'restricted'
        ],
        3 => [
            'user_type' => 'agent',
            'user_group' => 3,
            'permissions' => 'user_defined'
        ],
        4 => [
            'user_type' => 'end_user',
            'user_group' => 4,
            'permissions' => 'read_only'
        ]
    ]
];