<?php

// Generated e107 Plugin Admin Area

require_once '../../../class2.php';
if (!getperms('P')) {
    e107::redirect('admin');
    exit;
}

require_once 'admin_leftmenu.php';

class forum_thread_ui extends e_admin_ui
{
    protected $pluginTitle = 'Forum Extended';
    protected $pluginName = 'forum_extended';
    //	protected $eventName		= 'forum_extended-forum_thread'; // remove comment to enable event triggers in admin.
    protected $table = 'forum_thread';
    protected $pid = 'thread_id';
    protected $perPage = 50;
    protected $batchDelete = true;
    protected $batchExport = true;
    protected $batchCopy = true;

    //	protected $sortField		= 'somefield_order';
    //	protected $sortParent      = 'somefield_parent';
    //	protected $treePrefix      = 'somefield_title';

    //	protected $tabs				= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable.

    //	protected $listQry      	= "SELECT * FROM `#tableName` WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.

    protected $listOrder = 'thread_id DESC';

    protected $fields = [
            'checkboxes' => ['title' => '',  'type' => null,  'data' => null,  'width' => '5%',  'thclass' => 'center',  'forced' => 'value',  'class' => 'center',  'toggle' => 'e-multiselect',  'readParms' => [],  'writeParms' => []],
            'thread_id' => ['title' => LAN_ID,  'data' => 'int',  'width' => '5%',  'help' => '',  'readParms' => [],  'writeParms' => [],  'class' => 'left',  'thclass' => 'left'],
            'thread_name' => ['title' => LAN_FORUM_1003,  'type' => 'text',  'data' => 'safestr',  'width' => 'auto',  'inline' => 'value',  'help' => '',  'readParms' => [],  'writeParms' => [],  'class' => 'left',  'thclass' => 'left'],
            'thread_forum_id' => ['title' => LAN_PLUGIN_FORUM_NAME,  'type' => 'dropdown',  'data' => 'int',  'width' => '5%',  'help' => '',  'readParms' => [],  'writeParms' => [],  'class' => 'left',  'thclass' => 'left',  'filter' => false,  'batch' => false],

            'thread_datestamp' => ['title' => LAN_DATESTAMP,  'type' => 'datestamp',  'data' => 'int',  'width' => 'auto',  'filter' => 'value',  'help' => '',  'readParms' => [],  'writeParms' => [],  'class' => 'left',  'thclass' => 'left'],
            'thread_user' => ['title' => LAN_AUTHOR,  'type' => 'user',  'data' => 'int',  'width' => 'auto',  'help' => '',  'readParms' => [],  'writeParms' => [],  'class' => 'left',  'thclass' => 'left', 'filter' => true],
            'thread_user_anon' => ['title' => 'Anon',  'type' => 'text',  'data' => 'safestr',  'width' => 'auto',  'help' => '',  'readParms' => [],  'writeParms' => [],  'class' => 'left',  'thclass' => 'left'],

            'thread_views' => ['title' => 'Views',  'type' => 'number',  'data' => 'int',  'width' => 'auto',  'help' => '',  'readParms' => [],  'writeParms' => [],  'class' => 'left',  'thclass' => 'left'],

            'thread_active' => ['title' => 'Active',  'type' => 'boolean',  'data' => 'int',  'width' => 'auto',  'help' => '', 'batch' => true,   'readParms' => [],
            'writeParms' => 'enabled=LAN_ACTIVE&disabled=Locked',  'class' => 'left',  'thclass' => 'left', 'filter' => true,  ],

            'thread_sticky' => ['title' => 'Sticky',  'type' => 'boolean',  'data' => 'int',  'width' => 'auto', 'filter' => true,  'help' => '', 'batch' => true,  'readParms' => [],
            'writeParms' => 'enabled=LAN_FORUM_1011&disabled=LAN_NO',  'class' => 'left',  'thclass' => 'left', ],

            'thread_lastpost' => ['title' => 'Lastpost',  'type' => 'datestamp',  'data' => 'int',  'width' => 'auto',  'help' => '',  'readParms' => [],  'writeParms' => [],  'class' => 'left',  'thclass' => 'left',  'filter' => false,  'batch' => false],

            'thread_lastuser' => ['title' => 'Lastuser',  'type' => 'user',  'data' => 'int',  'width' => 'auto',  'help' => '',  'readParms' => [],  'writeParms' => [],  'class' => 'left',  'thclass' => 'left'],
            'thread_lastuser_anon' => ['title' => 'Anon',  'type' => 'text',  'data' => 'safestr',  'width' => 'auto',  'help' => '',  'readParms' => [],  'writeParms' => [],  'class' => 'left',  'thclass' => 'left'],

            'thread_total_replies' => ['title' => 'Replies',  'type' => 'number',  'data' => 'int',  'width' => 'auto',  'help' => '',  'readParms' => [],  'writeParms' => [],  'class' => 'left',  'thclass' => 'left'],

            'thread_options' => ['title' => 'Options',  'type' => 'textarea',  'data' => 'str',  'width' => 'auto',  'help' => '',  'readParms' => [],  'writeParms' => [],  'class' => 'left',  'thclass' => 'left',  'filter' => false,  'batch' => false],

            'options' => ['title' => LAN_OPTIONS,  'type' => null,  'data' => null,  'width' => '10%',  'thclass' => 'center last',  'class' => 'center last',  'forced' => 'value',  'readParms' => [],  'writeParms' => []],
        ];

    protected $fieldpref = ['thread_id', 'thread_name', 'thread_forum_id', 'thread_views', 'thread_active', 'thread_lastpost', 'thread_sticky', 'thread_datestamp', 'thread_user', 'thread_user_anon', 'thread_lastuser', 'thread_lastuser_anon', 'thread_total_replies'];

    public function init()
    {
        $data = e107::getDb()->retrieve('forum', 'forum_id,forum_name,forum_parent', 'forum_id != 0', true);
        $this->forumParents[0] = FORLAN_216;
        $forumSubParents = [];

        foreach ($data as $val) {
            $id = $val['forum_id'];

            if ($val['thread_forum_id'] == 0) {
                $this->forumParents[$id] = $val['forum_name'];
            } else {
                $forumSubParents[$id] = $val['forum_name'];
            }
        }

        $this->fields['thread_forum_id']['writeParms']['optArray'] = $this->forumParents;
    }

    public function beforeDelete($data, $id)
    {
        $mes = e107::getMessage();
        $threadId = $id;
        require_once e_PLUGIN.'forum/forum_class.php';
        $f = new e107forum();
        $ret = $f->threadDelete($threadId);

        if ($ret) {
            $mes->addSuccess('Thread deleted');
        } else {
            $mes->addError("Thread couldn't be deleted");
        }

        $this->redirect();
    }

    // ------- Customize Create --------

    public function beforeCreate($new_data, $old_data)
    {
        return $new_data;
    }

    public function afterCreate($new_data, $old_data, $id)
    {
        // do something
    }

    public function onCreateError($new_data, $old_data)
    {
        // do something
    }

    // ------- Customize Update --------

    public function beforeUpdate($new_data, $old_data, $id)
    {
        return $new_data;
    }

    public function afterUpdate($new_data, $old_data, $id)
    {
        // do something
    }

    public function onUpdateError($new_data, $old_data, $id)
    {
        // do something
    }

    public function renderHelp()
    {
        $caption = LAN_HELP;

        switch ($this->getAction()) {
                case 'list':
                    $text = 'Overview of existing topics. Be aware of what you do! The main purpose is <b>searching</b>, nothing more. ';
                break;

                case 'edit':
                    $text = '<b>Carefully!</b> There is no check of related data. Only delete functionality works like on the frontend. ';
                break;

                case 'shortcodes':
                    $text = 'Very simple version. Just replacement for forum local shortcode.';
                break;

                default:
                break;
            }

        return ['caption' => $caption, 'text' => $text];
    }

    public function shortcodesPage()
    {
        $text = 'These shortcodes are available sitewide - use them in the page menu (tinymce) or any template. ';

        $text .= '<code><h3>{FORUM_WELCOME}</h3></code>';
        $text .= "<div class='well'>Result for you:<br>";
        $text .= e107::getParser()->parseTemplate('{FORUM_WELCOME}');
        $text .= '</div>';

        $text .= '<code><h3>{FORUM_WELCOME_INFO}</h3>';
        $text .= "<div class='well'>Result for you:<br>";
        $text .= e107::getParser()->parseTemplate('{FORUM_WELCOME_INFO}');
        $text .= '</div>';
        e107::getRender()->tablerender('<h2>Available shortcodes</h2>', $text);
    }
}

new forum_extended_adminArea();

require_once e_ADMIN.'auth.php';
e107::getAdminUI()->runPage();

require_once e_ADMIN.'footer.php';
exit;
