<?php
 

if(!defined('e107_INIT'))
{
	exit;
}
 
e107::lan('forum_extended', "front", true);
 
class forum_extended_shortcodes extends e_shortcode
{
 
	/**
	 * {FORUM_SEARCH}
	 * @example {FORUM_SEARCH: placeholder=Search forums} - sets placeholder 'Search forums'
	 * @example {FORUM_SEARCH: buttonclass=btn btn-small} - sets button class 'btn btn-small'
	 * global shortcode for forum search outside templates
	 */

	function sc_forum_search($parm = null)
	{

		if (!deftrue('FONTAWESOME') || !$srchIcon = e107::getParser()->toGlyph('fa-search'))
		{
			$srchIcon = LAN_SEARCH;
		}

		$formclass 	= (!empty($parm['formclass'])) ? $parm['formclass']  : '';

		$buttonclass 	= (!empty($parm['buttonclass'])) ? "class='" . $parm['buttonclass'] . "'" : "class='btn btn-default btn-secondary button'";
		$placeholder    = (!empty($parm['placeholder'])) ? $parm['placeholder'] : LAN_SEARCH;

		// String candidate for USERLIST wrapper
		return "
	<form method='get' class='forum-search-form {$formclass}' action='" . e_HTTP . "search.php'>
	<div class='input-group'>
	<input type='hidden' name='r' value='0' />
	<input type='hidden' name='t' value='forum' />
	<input type='hidden' name='forum' value='all' />
	<input class='tbox form-control' type='text' name='q' size='20' value='' placeholder='" . $placeholder . "' maxlength='50' />
	<span class='input-group-btn'>
	<button " . $buttonclass . " type='submit' name='s' value='search' >" . $srchIcon . "</button>
	</span>
	</div>

	</form>\n";
	}

	/* {LAST_FORUM_UPDATED} */
	/* last post date from whole forum */
	function sc_last_forum_updated() {

		$date1 = e107::getDb()->retrieve('forum_thread',
			'thread_datestamp',
			' ORDER BY thread_datestamp DESC LIMIT 1' );

		$date2 = e107::getDb()->retrieve(
			'forum',
			'forum_datestamp',
			' ORDER BY forum_datestamp DESC LIMIT 1'
		);	

		$date = $date1 > $date2 ? $date1 : $date2;

		$mode = empty($parm['format']) ? 'forum' : $parm['format'];

		return e107::getParser()->toDate($date, $mode);
 
	} 
    
    /* {FORUM_WELCOME} */
    function sc_forum_welcome() {
 
        $INFO = "";
        if (USER == TRUE)
        {
            $INFO = LAN_FORUM_0018." ".USERNAME."<br />";
        }
        else
        {
        	$INFO .= "";
        	if (ANON == TRUE)
        	{
        		$INFO .= LAN_FORUM_0049;
        	}
        	elseif(USER == FALSE)
        	{
        		$INFO .=  LAN_FORUM_0049;
        	}
        }
        
        return $INFO;
    
    }
    
    /* {FORUM_WELCOME_INFO} */
    function sc_forum_welcome_info($parm = NULL) {

		$linkclass 	= (!empty($parm['linkclass'])) ? "class='" . $parm['linkclass'] . "'" : "";

        $INFO = "";
        if (USER == TRUE)
        {
            $total_new_threads = e107::getDb()->count('forum_thread', '(*)', "WHERE thread_datestamp>'".USERLV."' ");
            $total_updated_threads = e107::getDb()->count('forum_thread', '(*)', "WHERE thread_lastpost >'".USERLV."'  AND thread_lastpost > thread_datestamp ");
         
           // if($total_updated_threads > $total_new_threads) $total_new_threads =  $total_updated_threads;
            $userData = e107::user(USERID);
            
        	if ($userData['user_plugin_forum_viewed'] != "")
        	{
        		$tmp = explode(".", $userData['user_plugin_forum_viewed']);		// List of numbers, separated by single period
        		$total_read_threads = count($tmp);
        	}
        	else
        	{
        		$total_read_threads = 0;
        	}
        
        	 
        	//$lastvisit_datestamp = $gen->convert_date(USERLV, 'long');
            $lastvisit_datestamp = defined('USERLV') ? e107::getDate()->convert_date(USERLV, 'long') : '';
        	$datestamp = e107::getDate()->convert_date(time(), "long");
        /*	if (!$total_new_threads)
        	{
        		$INFO .= LAN_FORUM_0019;
        	}
        	elseif($total_new_threads == 1)
        	{
        		$INFO .= LAN_FORUM_0020;
        	}
        	else
        	{
        		$INFO .= LAN_FORUM_0021." ".$total_new_threads." ".LAN_FORUM_0022." ";
        	} */
            
            $INFO .= LAN_FORUM_NEW_0001." ".LAN_FORUM_NEW_0004 .": ".LAN_FORUM_NEW_0002.":". $total_new_threads ." & ".LAN_FORUM_NEW_0003.":". $total_updated_threads ;
     
       
            if ( ($total_new_threads == $total_read_threads && $total_new_threads != 0 ) OR  $total_read_threads >= $total_new_threads)
        	{
        		$INFO .= "<br>".LAN_FORUM_0029;
        		$allread = TRUE;
        	}
        	elseif($total_read_threads != 0 )
        	{
        		$INFO .= " (".LAN_FORUM_0027. " ". $total_read_threads. " " . LAN_FORUM_0028.")";
        	}
        
        	$INFO .= "<br><small>
        	".LAN_FORUM_0024." ".$lastvisit_datestamp."<br />
        	".LAN_FORUM_0025." ".$datestamp."</small>";
            
        }
        else
        {
        	$INFO .= "";
        	if (ANON == TRUE)
        	{
        		$INFO .= LAN_FORUM_0050." "."<a {$linkclass} role='button' href='".e_SIGNUP."'>".LAN_FORUM_0051."</a>"." ".LAN_FORUM_0052;
        	}
        	elseif(USER == FALSE)
        	{
        		$INFO .=  LAN_FORUM_0053." "."<a {$linkclass} role='button' href='".e_SIGNUP."'>".LAN_FORUM_0054."</a>"." ".LAN_FORUM_0055;
        	}
        }
        
        
        if (USER && $allread != TRUE && ( $total_new_threads OR $total_updated_threads) && ($total_new_threads >= $total_read_threads OR $total_updated_threads >= $total_read_threads ))
        {
        	$url_new = e107::getUrl()->create("forum/new");
            $url_markread = e107::getUrl()->create("forum/markread/all");   //mark.all.as.read
            
            $INFO .= "<br /><a class='btn btn-primary' href='".$url_markread."'>".LAN_FORUM_0057.'</a>'.(e_QUERY != 'new' ? "  <a class='btn btn-primary'  href='".$url_new."'>".LAN_FORUM_NEW_0007."</a>" : '');
        }
        
        
        return $INFO;
    
    }
 
}

