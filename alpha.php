<?php
/**
 * FILE: e107_plugins/forum_extended/alpha.php
 *
 * Alphabetical topic list of one forum: /forum-alpha/<forum_sef>/ (e_url.php)
 * or alpha.php?id=<forum_id>.
 * Linked from the site menu, from a sticky topic in the forum, or through
 * the {FORUM_EXT_ALPHA_LINK} shortcode in a template.
 *
 * Any forum can be shown; read access is the forum's own: the visitor must
 * have 'view' permission on it (e107forum::checkPerm), so a private forum
 * stays private here too.
 */

if(!defined('e107_INIT'))
{
	require_once(__DIR__ . '/../../class2.php');
}

if(!e107::isInstalled('forum'))
{
	e107::redirect();
	exit;
}

require_once(e_PLUGIN . 'forum/forum_class.php');
require_once(__DIR__ . '/includes/forum_extended_alpha.php');

e107::lan('forum', 'front', true);
e107::lan('forum_extended', 'front', true);


// ?sef= comes from the /forum-alpha/<forum_sef>/ route (e_url.php), ?id= from
// the legacy URL. The SEF name is only ever used as a bound parameter.
$feForumId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if($feForumId === 0 && !empty($_GET['sef']) && is_string($_GET['sef']))
{
	$feForumId = forum_extended_alpha::idBySef($_GET['sef']);
}


$feForumClass = new e107forum();
$feForum      = ($feForumId > 0) ? forum_extended_alpha::forum($feForumId) : array();
$feCaption    = LAN_FORUM_EXT_ALPHA_TITLE;

// Not found and not allowed answer the same, so the page does not reveal
// which private forums exist.
if(empty($feForum) || !$feForumClass->checkPerm($feForumId, 'view'))
{
	$feBody = '<div class="alert alert-warning">' . LAN_FORUM_EXT_NO_ACCESS . '</div>';
}
else
{
	$feCaption = forum_extended_alpha::esc($feForum['forum_name']) . ' - ' . LAN_FORUM_EXT_ALPHA_TITLE;

	$feForumUrl = e107::url('forum', 'forum', $feForum);

	$feBody = '<p><a href="' . forum_extended_alpha::esc($feForumUrl) . '">'
		. LAN_FORUM_EXT_BACK . '</a></p>'
		. forum_extended_alpha::render($feForum, forum_extended_alpha::threads($feForumId));
}

// No variable called $text at file scope: the theme header unsets it.
require_once(HEADERF);

e107::getRender()->tablerender($feCaption, $feBody, 'forum_extended_alpha');

require_once(FOOTERF);
