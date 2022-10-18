<?php

return [
    [
        'id' => 'ocms-menu-appearance',
        'priority' => 2,
        'parent_id' => null,
        'name' => 'appearance::messages.appearance',
        'icon' => 'fas fa-paint-brush',
        'url' => '#'
    ],
    [
        'id' => 'ocms-menu-appearance-themes',
        'priority' => 0,
        'parent_id'=> 'ocms-menu-appearance',
        'name' => 'appearance::messages.themes',
        'url'  => route('admin.appearance.themes')
    ]
];
