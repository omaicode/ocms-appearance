<?php

return [
    [
        'id' => 'ocms-menu-partials',
        'priority' => 96,
        'parent_id' => null,
        'name' => 'appearance::messages.partials',
        'icon' => 'fas fa-cubes',
        'url' => route('admin.appearance.partials.index'),
        'permissions' => ['appearance.partials.view']
    ],
    [
        'id' => 'ocms-menu-appearance',
        'priority' => 97,
        'parent_id' => null,
        'name' => 'appearance::messages.appearance',
        'icon' => 'fas fa-paint-brush',
        'url' => '#',
        'permissions' => ['appearance.view']
    ],
    [
        'id' => 'ocms-menu-appearance-themes',
        'priority' => 0,
        'parent_id'=> 'ocms-menu-appearance',
        'name' => 'appearance::messages.themes',
        'url'  => route('admin.appearance.themes'),
        'permissions' => ['appearance.view']
    ],
    [
        'id' => 'ocms-menu-appearance-theme-options',
        'priority' => 1,
        'parent_id'=> 'ocms-menu-appearance',
        'name' => 'appearance::messages.theme_options',
        'url'  => route('admin.appearance.theme-options'),
        'permissions' => ['appearance.view']
    ],
    [
        'id' => 'ocms-menu-appearance-menu',
        'priority' => 2,
        'parent_id'=> 'ocms-menu-appearance',
        'name' => 'appearance::messages.menus',
        'url'  => route('admin.appearance.menus.index'),
        'permissions' => ['appearance.view']
    ],
];
