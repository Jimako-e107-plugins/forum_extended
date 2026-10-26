<?php
/**
 * FILE: e107_plugins/forum_extended/e_shortcode.php
 *
 * {FORUM_EXT_ALPHA_LINK} - link to the alphabetical topic list of a forum.
 * Usage notes: docs/notes.md.
 *
 * Parameters (all optional):
 *   id=67         forum id. Without it the forum is found from the page:
 *                 - topic list (forum_viewforum.php): the forum plugin has
 *                   already resolved the forum, also from a SEF URL, into
 *                   $_REQUEST['id'] and ?sef= - no query;
 *                 - topic detail (forum_viewtopic.php): ?id= is the TOPIC
 *                   id, its forum is read once.
 *                 Anywhere else id= is required.
 *   forums=67,70  show the link only for these forums - for a template that
 *                 is shared by every forum.
 *   type=url      return the bare URL instead of a button.
 *
 * Examples: {FORUM_EXT_ALPHA_LINK: forums=67,70}
 *           {FORUM_EXT_ALPHA_LINK: id=67&type=url}
 *
 * The target page checks the visitor's permission; this only builds a link.
 */

if(!defined('e107_INIT')) { exit; }


class forum_extended_shortcodes extends e_shortcode
{
	/** @var array|null forum of the current page, resolved once per request */
	private $pageForum = null;


	/* {FORUM_EXT_ALPHA_LINK} */
	public function sc_forum_ext_alpha_link($parm = null)
	{
		$parm = is_array($parm) ? $parm : array();

		require_once(__DIR__ . '/includes/forum_extended_alpha.php');

		if(isset($parm['id']))
		{
			$forumId = (int) $parm['id'];
			$sef     = null;   // read below, after the forums= check
		}
		else
		{
			$forum   = $this->pageForum();
			$forumId = $forum['forum_id'];
			$sef     = $forum['forum_sef'];
		}

		if($forumId < 1)
		{
			return '';
		}

		if(!empty($parm['forums']))
		{
			$allowed = array_map('intval', explode(',', (string) $parm['forums']));

			if(!in_array($forumId, $allowed, true))
			{
				return '';
			}
		}

		if($sef === null)
		{
			$row = forum_extended_alpha::forum($forumId);
			$sef = isset($row['forum_sef']) ? (string) $row['forum_sef'] : '';
		}

		$url = forum_extended_alpha::url($forumId, $sef);

		if(isset($parm['type']) && $parm['type'] === 'url')
		{
			return $url;
		}

		e107::lan('forum_extended', 'front', true);

		return '<a class="btn btn-sm btn-outline-primary forum-ext-alpha-link" href="' . forum_extended_alpha::esc($url) . '">'
			. LAN_FORUM_EXT_ALPHA_LINK . '</a>';
	}


	/**
	 * Forum of the page being shown: the forum on its topic list, the topic's
	 * forum on a topic detail, none elsewhere.
	 *
	 * @return array array('forum_id' => int, 'forum_sef' => string)
	 */
	private function pageForum()
	{
		if($this->pageForum !== null)
		{
			return $this->pageForum;
		}

		$this->pageForum = array('forum_id' => 0, 'forum_sef' => '');

		$page = defset('e_PAGE');

		if($page === 'forum_viewforum.php')
		{
			$this->pageForum['forum_id'] = isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : 0;

			if(!empty($_GET['sef']) && is_string($_GET['sef']))
			{
				$this->pageForum['forum_sef'] = $_GET['sef'];
			}
			elseif($this->pageForum['forum_id'] > 0)
			{
				$row = forum_extended_alpha::forum($this->pageForum['forum_id']);
				$this->pageForum['forum_sef'] = isset($row['forum_sef']) ? (string) $row['forum_sef'] : '';
			}
		}
		elseif($page === 'forum_viewtopic.php' && !empty($_GET['id']))
		{
			$row = forum_extended_alpha::forumOfThread((int) $_GET['id']);

			if(!empty($row))
			{
				$this->pageForum = $row;
			}
		}

		return $this->pageForum;
	}
}
