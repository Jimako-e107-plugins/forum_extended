<?php
/*
 * FILE: e107_plugins/forum_extended/admin/admin_links.php
 *
 * e107 website system
 *
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * forum_extended - alphabetical links entry point.
 *
 * Read-only overview: every forum with the URL of its alphabetical page,
 * ready to copy into the site menu or a sticky topic, plus the template
 * shortcode. Nothing is stored - any forum can be shown alphabetically and
 * the page itself checks the visitor's forum permission.
 */

require_once '../../../class2.php';   // admin/ -> forum_extended -> eplugins -> root

if(!getperms('P'))
{
	e107::redirect('admin');
	exit;
}

include 'admin_menu.php';

require_once(__DIR__ . '/../includes/forum_extended_alpha.php');

class forum_extended_links_ui extends e_admin_ui
{
	protected $pluginTitle = LAN_FORUM_EXT_NAME;
	protected $pluginName  = 'forum_extended';

	protected $table = '';


	public function overviewPage()
	{
		$out = '<table class="table table-striped adminlist">'
			. '<thead><tr>'
			. '<th>' . LAN_ID . '</th>'
			. '<th>' . LAN_FORUM_EXT_ADMIN_FORUM . '</th>'
			. '<th>' . LAN_FORUM_EXT_ADMIN_LINK . '</th>'
			. '<th>' . LAN_FORUM_EXT_ADMIN_SHORTCODE . '</th>'
			. '</tr></thead><tbody>';

		foreach(forum_extended_alpha::forumList() as $id => $forum)
		{
			$url = forum_extended_alpha::url($id, $forum['forum_sef'], array('mode' => 'full'));

			$out .= '<tr>'
				. '<td>' . (int) $id . '</td>'
				. '<td>' . forum_extended_alpha::esc($forum['forum_name']) . '</td>'
				. '<td><a href="' . forum_extended_alpha::esc($url) . '" target="_blank">' . forum_extended_alpha::esc($url) . '</a></td>'
				. '<td><code>{FORUM_EXT_ALPHA_LINK: id=' . (int) $id . '}</code></td>'
				. '</tr>';
		}

		return $out . '</tbody></table>';
	}


	public function renderHelp()
	{
		return array('caption' => LAN_HELP, 'text' => LAN_FORUM_EXT_ADMIN_LINKS_HELP);
	}
}

class forum_extended_links_form_ui extends e_admin_form_ui
{
}

new forum_extended_admin_dispatcher(true, array_merge($_GET, array('mode' => 'links', 'action' => 'overview')));

require_once e_ADMIN . "auth.php";

e107::getAdminUI()->runPage();

require_once e_ADMIN . "footer.php";
exit;
