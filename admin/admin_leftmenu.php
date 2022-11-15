<?php

e107::lan('forum', 'global', true);
e107::lan('forum', 'admin', true);
e107::lan('forum', 'front', true);

e107::lan('forum_extended', true);
e107::coreLan('db', true);

class forum_extended_adminArea extends e_admin_dispatcher
{
    protected $modes = [
        'main' => [
            'controller' => 'forum_thread_ui',
            'path' => null,
            'ui' => 'e_admin_form_ui',
            'uipath' => null,
        ],

        'tools' => [
            'controller' => 'forum_tools_ui',
            'path' => null,
            'ui' => 'e_admin_form_ui',
            'uipath' => null,
        ],
    ];

    protected $adminMenu = [
        'main/list' => ['caption' => LAN_FORUM_1007, 'perm' => 'P', 'url' => 'admin_config.php'],
        'main/div0' => ['divider' => true],
        'main/shortcodes' => ['caption' => 'Available shortcodes', 'perm' => 'P', 'url' => 'admin_config.php'],
        'main/div1' => ['divider' => true]
    ];

    protected $adminMenuAliases = [
        'main/edit' => 'main/list',
    ];

    protected $menuTitle = 'Forum Extended';
}
