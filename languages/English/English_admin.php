<?php
/**
 * FILE: e107_plugins/forum_extended/languages/English/English_admin.php
 *
 * Admin constants. Loaded via e107::lan('forum_extended', true, true) at the top of admin/admin_menu.php.
 */

if(!defined('e107_INIT')) { exit; }

return [
	'LAN_FORUM_EXT_NAME'                    => "Forum Extended",
	'LAN_FORUM_EXT_ADMIN_THREADS'           => "Topics",
	'LAN_FORUM_EXT_ADMIN_THREADS_HELP'      => "One flat list of all forum topics, mainly for searching. Edits change the topic row only - related data (forum counters) is not checked. Delete works like on the front end and removes the posts too.",
	'LAN_FORUM_EXT_ADMIN_TOPIC'             => "Topic",
	'LAN_FORUM_EXT_ADMIN_FORUM'             => "Forum",
	'LAN_FORUM_EXT_ADMIN_VIEWS'             => "Views",
	'LAN_FORUM_EXT_ADMIN_REPLIES'           => "Replies",
	'LAN_FORUM_EXT_ADMIN_ACTIVE'            => "Open",
	'LAN_FORUM_EXT_ADMIN_STICKY'            => "Sticky",
	'LAN_FORUM_EXT_ADMIN_NORMAL'            => "Normal",
	'LAN_FORUM_EXT_ADMIN_ANNOUNCE'          => "Announcement",
	'LAN_FORUM_EXT_ADMIN_LASTPOST'          => "Last post",
	'LAN_FORUM_EXT_ADMIN_DELETED'           => "Topic deleted.",
	'LAN_FORUM_EXT_ADMIN_DELETE_FAILED'     => "The topic could not be deleted.",
	'LAN_FORUM_EXT_ADMIN_LINKS' => "Alphabetical links",
	'LAN_FORUM_EXT_ADMIN_LINK' => "Link",
	'LAN_FORUM_EXT_ADMIN_SHORTCODE' => "Shortcode",
	'LAN_FORUM_EXT_ADMIN_LINKS_HELP' => "Every forum can be shown alphabetically. Copy the link into the site menu (Sitelinks) or a sticky topic. In the forum topic-list template use {FORUM_EXT_ALPHA_LINK: forums=67} - it finds the current forum itself, forums= limits it to the listed ones. Elsewhere (e.g. the topic template) give the forum: {FORUM_EXT_ALPHA_LINK: id=67}. type=url returns the bare URL.",
];
