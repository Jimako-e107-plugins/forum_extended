<?php
/*
 * FILE: e107_plugins/forum_extended/admin/admin_threads.php
 *
 * e107 website system
 *
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * forum_extended - flat topic list entry point.
 *
 * One searchable list of every forum topic (no forum tree), with quick edits
 * of the topic name, its forum, sticky and locked state. Kept from the old
 * forum_extended admin, which moderators used for exactly that.
 *
 * Deleting goes through e107forum::threadDelete(), as on the front end, so
 * posts and counters are cleaned up. Editing writes the forum_thread row
 * directly with no check of related data - moving a topic does not recount
 * the forums' totals.
 */

require_once '../../../class2.php';   // admin/ -> forum_extended -> eplugins -> root

if(!getperms('P'))
{
	e107::redirect('admin');
	exit;
}

include 'admin_menu.php';

class forum_extended_threads_ui extends e_admin_ui
{
	protected $pluginTitle = LAN_FORUM_EXT_NAME;
	protected $pluginName  = 'forum_extended';

	protected $table       = 'forum_thread';
	protected $pid         = 'thread_id';
	protected $perPage     = 50;
	protected $batchDelete = true;
	protected $listOrder   = 'thread_id DESC';

	protected $fields = array(
		'checkboxes'           => array('title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => 'value', 'class' => 'center', 'toggle' => 'e-multiselect'),
		'thread_id'            => array('title' => LAN_ID, 'data' => 'int', 'width' => '5%', 'class' => 'left', 'thclass' => 'left'),
		'thread_name'          => array('title' => LAN_FORUM_EXT_ADMIN_TOPIC, 'type' => 'text', 'data' => 'safestr', 'width' => 'auto', 'inline' => true, 'class' => 'left', 'thclass' => 'left', 'writeParms' => array('size' => 'xxlarge')),
		'thread_forum_id'      => array('title' => LAN_FORUM_EXT_ADMIN_FORUM, 'type' => 'dropdown', 'data' => 'int', 'width' => 'auto', 'filter' => true, 'batch' => true, 'class' => 'left', 'thclass' => 'left', 'writeParms' => array('optArray' => array())),
		'thread_datestamp'     => array('title' => LAN_DATESTAMP, 'type' => 'datestamp', 'data' => 'int', 'width' => 'auto', 'class' => 'left', 'thclass' => 'left', 'readonly' => true),
		'thread_user'          => array('title' => LAN_AUTHOR, 'type' => 'user', 'data' => 'int', 'width' => 'auto', 'filter' => true, 'class' => 'left', 'thclass' => 'left', 'readonly' => true),
		'thread_views'         => array('title' => LAN_FORUM_EXT_ADMIN_VIEWS, 'type' => 'number', 'data' => 'int', 'width' => 'auto', 'class' => 'right', 'thclass' => 'right', 'readonly' => true),
		'thread_total_replies' => array('title' => LAN_FORUM_EXT_ADMIN_REPLIES, 'type' => 'number', 'data' => 'int', 'width' => 'auto', 'class' => 'right', 'thclass' => 'right', 'readonly' => true),
		'thread_active'        => array('title' => LAN_FORUM_EXT_ADMIN_ACTIVE, 'type' => 'boolean', 'data' => 'int', 'width' => 'auto', 'filter' => true, 'batch' => true, 'class' => 'center', 'thclass' => 'center'),
		'thread_sticky'        => array('title' => LAN_FORUM_EXT_ADMIN_STICKY, 'type' => 'dropdown', 'data' => 'int', 'width' => 'auto', 'filter' => true, 'batch' => true, 'class' => 'center', 'thclass' => 'center', 'writeParms' => array('optArray' => array())),
		'thread_lastpost'      => array('title' => LAN_FORUM_EXT_ADMIN_LASTPOST, 'type' => 'datestamp', 'data' => 'int', 'width' => 'auto', 'class' => 'left', 'thclass' => 'left', 'readonly' => true),
		'options'              => array('title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => 'value'),
	);

	protected $fieldpref = array('thread_id', 'thread_name', 'thread_forum_id', 'thread_user', 'thread_total_replies', 'thread_active', 'thread_sticky', 'thread_lastpost');


	public function init()
	{
		// Every forum that can hold topics; top-level categories
		// (forum_parent = 0) cannot.
		$forums = array();

		$sql = e107::getDb();
		$sql->execute('SELECT forum_id, forum_name FROM `#forum` WHERE forum_parent != 0 ORDER BY forum_order ASC');

		while($row = $sql->fetch())
		{
			$forums[(int) $row['forum_id']] = $row['forum_name'];
		}

		$this->fields['thread_forum_id']['writeParms']['optArray'] = $forums;

		// thread_sticky is not a boolean: 2 marks an announcement.
		$this->fields['thread_sticky']['writeParms']['optArray'] = array(
			0 => LAN_FORUM_EXT_ADMIN_NORMAL,
			1 => LAN_FORUM_EXT_ADMIN_STICKY,
			2 => LAN_FORUM_EXT_ADMIN_ANNOUNCE,
		);
	}


	/**
	 * Delete through the forum class so the topic's posts and the forum
	 * counters go with it. Returning false stops e_admin_ui's own delete.
	 */
	public function beforeDelete($data, $id)
	{
		require_once e_PLUGIN . 'forum/forum_class.php';

		$forum = new e107forum();

		if($forum->threadDelete((int) $id))
		{
			e107::getMessage()->addSuccess(LAN_FORUM_EXT_ADMIN_DELETED);
		}
		else
		{
			e107::getMessage()->addError(LAN_FORUM_EXT_ADMIN_DELETE_FAILED);
		}

		return false;
	}


	public function renderHelp()
	{
		return array('caption' => LAN_HELP, 'text' => LAN_FORUM_EXT_ADMIN_THREADS_HELP);
	}
}

class forum_extended_threads_form_ui extends e_admin_form_ui
{
}

new forum_extended_admin_dispatcher(true, array_merge($_GET, array('mode' => 'threads')));

require_once e_ADMIN . "auth.php";

e107::getAdminUI()->runPage();

require_once e_ADMIN . "footer.php";
exit;
