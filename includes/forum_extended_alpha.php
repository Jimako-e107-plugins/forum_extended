<?php
/**
 * FILE: e107_plugins/forum_extended/includes/forum_extended_alpha.php
 *
 * Alphabetical topic list of a forum.
 *
 * The upstream forum orders topics by last post only; some forums (authors
 * of the originals, one topic per author) are looked up by name, so they get
 * their own page with letter tabs, built like the unnuke categories page:
 * an "All" tab first, A-Z, then "Other". The bucket is the first BYTE of the
 * topic name upper-cased, so a name starting with a national or non-latin
 * character falls into "Other" - the same rule as categories.php.
 *
 * All tabs are rendered at once and switched client side, so every letter is
 * one click away with no paging.
 */

if(!defined('e107_INIT')) { exit; }


class forum_extended_alpha
{
	const PLUGIN = 'forum_extended';


	/**
	 * URL of the alphabetical page of a forum: /forum-alpha/<forum_sef>/
	 * through e_url.php. e107::url() itself falls back to the legacy form
	 * (alpha.php?id=N) when SEF URLs are off for the plugin; the plain URL is
	 * also used when the forum has no SEF name or e_url.php is not registered
	 * yet (e107::url() then returns false).
	 *
	 * @param int    $forumId
	 * @param string $forumSef
	 * @param array  $options e107::url() options, e.g. array('mode' => 'full')
	 * @return string
	 */
	public static function url($forumId, $forumSef = '', $options = array())
	{
		$forumSef = (string) $forumSef;

		if($forumSef !== '')
		{
			$url = e107::url(self::PLUGIN, 'alpha', array('forum_id' => (int) $forumId, 'forum_sef' => $forumSef), $options);

			if(!empty($url))
			{
				return $url;
			}
		}

		$url = e_PLUGIN_ABS . self::PLUGIN . '/alpha.php?id=' . (int) $forumId;

		return (isset($options['mode']) && $options['mode'] === 'full') ? SITEURLBASE . $url : $url;
	}


	/**
	 * Forums that can hold topics (not the top-level categories, which have
	 * forum_parent = 0), in forum order.
	 *
	 * @return array forum_id => array('forum_name', 'forum_sef')
	 */
	public static function forumList()
	{
		$list = array();

		$sql = e107::getDb();
		$sql->execute('SELECT forum_id, forum_name, forum_sef FROM `#forum` WHERE forum_parent != 0 ORDER BY forum_order ASC');

		while($row = $sql->fetch())
		{
			$list[(int) $row['forum_id']] = array(
				'forum_name' => (string) $row['forum_name'],
				'forum_sef'  => (string) $row['forum_sef'],
			);
		}

		return $list;
	}


	/**
	 * Forum id of a SEF name, 0 when there is none.
	 *
	 * @param string $sef
	 * @return int
	 */
	public static function idBySef($sef)
	{
		$sef = (string) $sef;

		if($sef === '')
		{
			return 0;
		}

		$sql = e107::getDb();
		$sql->execute('SELECT forum_id FROM `#forum` WHERE forum_sef = :sef LIMIT 1', array('sef' => $sef));

		$row = $sql->fetch();

		return is_array($row) ? (int) $row['forum_id'] : 0;
	}


	/**
	 * Forum id and SEF name of a topic, empty array when there is no such
	 * topic.
	 *
	 * @param int $threadId
	 * @return array array('forum_id' => int, 'forum_sef' => string)
	 */
	public static function forumOfThread($threadId)
	{
		$sql = e107::getDb();

		$sql->execute(
			'SELECT f.forum_id, f.forum_sef FROM `#forum_thread` AS t'
			. ' INNER JOIN `#forum` AS f ON f.forum_id = t.thread_forum_id'
			. ' WHERE t.thread_id = :id',
			array('id' => (int) $threadId)
		);

		$row = $sql->fetch();

		return is_array($row)
			? array('forum_id' => (int) $row['forum_id'], 'forum_sef' => (string) $row['forum_sef'])
			: array();
	}


	/**
	 * Letters with their own tab.
	 *
	 * @return string[]
	 */
	public static function letters()
	{
		return range('A', 'Z');
	}


	/**
	 * The forum row (name, sef) or an empty array.
	 *
	 * @param int $forumId
	 * @return array
	 */
	public static function forum($forumId)
	{
		$sql = e107::getDb();

		$sql->execute(
			'SELECT forum_id, forum_name, forum_sef, forum_description FROM `#forum` WHERE forum_id = :id',
			array('id' => (int) $forumId)
		);

		$row = $sql->fetch();

		return is_array($row) ? $row : array();
	}


	/**
	 * Every topic of the forum ordered by name, with the last poster's name.
	 *
	 * @param int $forumId
	 * @return array
	 */
	public static function threads($forumId)
	{
		$sql = e107::getDb();

		$sql->execute(
			'SELECT t.thread_id, t.thread_name, t.thread_total_replies, t.thread_lastpost,'
			. ' t.thread_lastuser, t.thread_lastuser_anon, u.user_name AS lastuser_name'
			. ' FROM `#forum_thread` AS t'
			. ' LEFT JOIN `#user` AS u ON u.user_id = t.thread_lastuser'
			. ' WHERE t.thread_forum_id = :id'
			. ' ORDER BY t.thread_name ASC, t.thread_id ASC',
			array('id' => (int) $forumId)
		);

		$rows = array();

		while($row = $sql->fetch())
		{
			$rows[] = $row;
		}

		return $rows;
	}


	/**
	 * Split the topics into letter buckets (first byte, upper-cased).
	 *
	 * @param array $rows
	 * @return array bucket key => rows
	 */
	public static function buckets(array $rows)
	{
		$letters = self::letters();
		$buckets = array();

		foreach($rows as $row)
		{
			$name  = ltrim((string) $row['thread_name']);
			$first = ($name !== '') ? strtoupper($name[0]) : '';
			$key   = in_array($first, $letters, true) ? $first : 'other';

			$buckets[$key][] = $row;
		}

		return $buckets;
	}


	/**
	 * Page body: tab strip and one table per tab.
	 *
	 * @param array $forum
	 * @param array $rows
	 * @return string
	 */
	public static function render(array $forum, array $rows)
	{
		$letters = self::letters();
		$buckets = self::buckets($rows);

		$out = '<div class="card"><ul class="nav nav-tabs flex-wrap" role="tablist">';

		$out .= '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link active" href="#fe-tab-all">'
			. LAN_FORUM_EXT_ALL . '</a></li>';

		foreach($letters as $letter)
		{
			$out .= '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link" href="#fe-tab-'
				. strtolower($letter) . '">' . $letter . '</a></li>';
		}

		$out .= '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link" href="#fe-tab-other">'
			. LAN_FORUM_EXT_OTHER . '</a></li>';

		$out .= '</ul></div>';

		$out .= '<div class="tab-content">';

		$out .= '<div class="tab-pane fade active show" id="fe-tab-all">' . self::table($forum, $rows) . '</div>';

		// A letter with no topics still gets its pane - the tab strip is
		// fixed, so a missing pane would be a dead link.
		foreach(array_merge($letters, array('other')) as $key)
		{
			$out .= '<div class="tab-pane fade" id="fe-tab-' . strtolower($key) . '">'
				. self::table($forum, isset($buckets[$key]) ? $buckets[$key] : array())
				. '</div>';
		}

		return $out . '</div>';
	}


	/**
	 * One table of topics.
	 *
	 * @param array $forum
	 * @param array $rows
	 * @return string
	 */
	private static function table(array $forum, array $rows)
	{
		if(empty($rows))
		{
			return '<p class="text-secondary m-3">' . LAN_FORUM_EXT_NO_TOPICS . '</p>';
		}

		$tp = e107::getParser();

		$out = '<table class="table table-striped table-hover">'
			. '<thead><tr>'
			. '<th scope="col">' . defset('LAN_FORUM_1003', 'Topic') . '</th>'
			. '<th scope="col" class="text-center">' . defset('LAN_FORUM_0003', 'Replies') . '</th>'
			. '<th scope="col" class="d-none d-md-table-cell">' . defset('LAN_FORUM_0004', 'Last Post') . '</th>'
			. '</tr></thead><tbody>';

		foreach($rows as $row)
		{
			$url = e107::url('forum', 'topic', array(
				'forum_sef'  => (string) $forum['forum_sef'],
				'thread_id'  => (int) $row['thread_id'],
				'thread_sef' => eHelper::title2sef((string) $row['thread_name'], 'dashl'),
			));

			$last = '';

			if(!empty($row['thread_lastpost']))
			{
				$who = !empty($row['lastuser_name']) ? (string) $row['lastuser_name'] : (string) $row['thread_lastuser_anon'];

				$last = self::esc($who) . ' <span class="text-secondary">'
					. $tp->toDate((int) $row['thread_lastpost'], 'short') . '</span>';
			}

			$out .= '<tr>'
				. '<td><a href="' . self::esc($url) . '">' . self::esc($row['thread_name']) . '</a></td>'
				. '<td class="text-center">' . (int) $row['thread_total_replies'] . '</td>'
				. '<td class="d-none d-md-table-cell small">' . $last . '</td>'
				. '</tr>';
		}

		return $out . '</tbody></table>';
	}


	/**
	 * HTML-escape for text and attributes. Existing entities are kept: topic
	 * names are stored through toDB() and may already carry them.
	 *
	 * @param string $value
	 * @return string
	 */
	public static function esc($value)
	{
		return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8', false);
	}
}
