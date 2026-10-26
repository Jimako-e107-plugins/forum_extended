<?php
/*
 * FILE: e107_plugins/forum_extended/e_url.php
 *
 * e107 website system
 *
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * forum_extended - SEF routes.
 *
 * /forum-alpha/<forum_sef>/ -> alpha.php?sef=<forum_sef>
 *
 * Its own alias next to the forum's /forum/<forum_sef>/, not a sub-path of
 * it: the forum plugin's 'forum' route matches ^forum/<anything>, so a path
 * under /forum/ would be taken by the forum itself.
 *
 * NOTE: after adding a route here the plugin has to be re-scanned in the e107
 * admin AND the URL profile set under Admin -> URLs, or neither the rewrite
 * nor e107::url() will see the new key.
 */

if(!defined('e107_INIT')) { exit; }

class forum_extended_url
{
	/**
	 * @return array route key => array('alias', 'regex', 'sef', 'redirect', 'legacy')
	 */
	public function config()
	{
		$config = array();

		// The SEF name is captured up to the next slash or the query string
		// and looked up with a bound parameter in alpha.php; an unknown name
		// gets the same "not available" answer as a forbidden forum.
		$config['alpha'] = array(
			'alias'    => 'forum-alpha',
			'regex'    => '^{alias}/([^/?]+)/?(?:\?.*)?$',
			'sef'      => '{alias}/{forum_sef}/',
			'redirect' => '{e_PLUGIN}forum_extended/alpha.php?sef=$1',
			'legacy'   => '{e_PLUGIN}forum_extended/alpha.php?id={forum_id}',
		);

		return $config;
	}
}
