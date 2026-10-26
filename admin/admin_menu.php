<?php
/*
 * FILE: e107_plugins/forum_extended/admin/admin_menu.php
 *
 * e107 website system
 *
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * forum_extended - admin dispatcher and left menu only. Every entry file
 * (admin_threads.php, admin_links.php) includes this file, defines its own
 * UI classes and pins its route on this dispatcher, so the menu is the same
 * on every page.
 *
 * THE LANGUAGE PACKS ARE LOADED HERE, at the very top, BEFORE the class is
 * defined: property defaults reference LAN_* constants and PHP evaluates them
 * at parse time, so an undefined constant there is a fatal on PHP 8.
 */

if(!defined('e107_INIT'))
{
	exit;
}

e107::lan('forum', 'global', true);
e107::lan('forum', 'front', true);
e107::lan('forum_extended', true, true);


/**
 * $perm is 'P' (plugin admins): both pages are for forum moderators with
 * admin access. There are no preferences.
 */
class forum_extended_admin_dispatcher extends e_admin_dispatcher
{
	protected $perm = 'P';

	protected $defaultMode   = 'threads';
	protected $defaultAction = 'list';

	protected $modes = array(

		'threads' => array(
			'controller' => 'forum_extended_threads_ui',
			'path'       => null,
			'ui'         => 'forum_extended_threads_form_ui',
			'uipath'     => null,
		),

		'links' => array(
			'controller' => 'forum_extended_links_ui',
			'path'       => null,
			'ui'         => 'forum_extended_links_form_ui',
			'uipath'     => null,
		),
	);

	protected $adminMenu = array(

		'threads/list'   => array('caption' => LAN_FORUM_EXT_ADMIN_THREADS, 'perm' => 'P', 'url' => '{e_PLUGIN}forum_extended/admin/admin_threads.php', 'query' => ''),
		'links/overview' => array('caption' => LAN_FORUM_EXT_ADMIN_LINKS, 'perm' => 'P', 'url' => '{e_PLUGIN}forum_extended/admin/admin_links.php', 'query' => ''),
	);

	protected $adminMenuAliases = array(
		'threads/edit' => 'threads/list',
	);

	protected $menuTitle = LAN_FORUM_EXT_NAME;
}
