<?php

// Generated e107 Plugin Admin Area 

require_once('../../class2.php');
if (!getperms('P')) 
{
	e107::redirect('admin');
	exit;
}

e107::lan('forum', 'global', true);
e107::lan('forum', 'admin', true);
e107::lan('forum', 'front', true);

// e107::lan('forum_extended',true);


class forum_extended_adminArea extends e_admin_dispatcher
{

	protected $modes = array(	
	
		'main'	=> array(
			'controller' 	=> 'forum_thread_ui',
			'path' 			=> null,
			'ui' 			=> 'forum_thread_form_ui',
			'uipath' 		=> null
		),
		

	);	
	
	
	protected $adminMenu = array(

		'main/list'			=> array('caption'=> LAN_FORUM_1007, 'perm' => 'P'),
	 

		// 'main/div0'      => array('divider'=> true),
		// 'main/custom'		=> array('caption'=> 'Custom Page', 'perm' => 'P'),
		
	);

	protected $adminMenuAliases = array(
		'main/edit'	=> 'main/list'				
	);	
	
	protected $menuTitle = 'Forum Extended';
}




				
class forum_thread_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'Forum Extended';
		protected $pluginName		= 'forum_extended';
	//	protected $eventName		= 'forum_extended-forum_thread'; // remove comment to enable event triggers in admin. 		
		protected $table			= 'forum_thread';
		protected $pid				= 'thread_id';
		protected $perPage			= 50; 
		protected $batchDelete		= true;
		protected $batchExport     = true;
		protected $batchCopy		= true;

	//	protected $sortField		= 'somefield_order';
	//	protected $sortParent      = 'somefield_parent';
	//	protected $treePrefix      = 'somefield_title';

	//	protected $tabs				= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable. 
		
	//	protected $listQry      	= "SELECT * FROM `#tableName` WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
	
		protected $listOrder		= 'thread_id DESC';
	
		protected $fields 		= array (
			'checkboxes'              => array (  'title' => '',  'type' => null,  'data' => null,  'width' => '5%',  'thclass' => 'center',  'forced' => 'value',  'class' => 'center',  'toggle' => 'e-multiselect',  'readParms' =>  array (),  'writeParms' =>  array (),),
			'thread_id'               => array (  'title' => LAN_ID,  'data' => 'int',  'width' => '5%',  'help' => '',  'readParms' =>  array (),  'writeParms' =>  array (),  'class' => 'left',  'thclass' => 'left',),
			'thread_name'             => array (  'title' => LAN_FORUM_1003,  'type' => 'text',  'data' => 'safestr',  'width' => 'auto',  'inline' => 'value',  'help' => '',  'readParms' =>  array (),  'writeParms' =>  array (),  'class' => 'left',  'thclass' => 'left',),
			'thread_forum_id'         => array (  'title' => LAN_PLUGIN_FORUM_NAME,  'type' => 'dropdown',  'data' => 'int',  'width' => '5%',  'help' => '',  'readParms' =>  array (),  'writeParms' =>  array (),  'class' => 'left',  'thclass' => 'left',  'filter' => false,  'batch' => false,),
			
			'thread_datestamp'        => array (  'title' => LAN_DATESTAMP,  'type' => 'datestamp',  'data' => 'int',  'width' => 'auto',  'filter' => 'value',  'help' => '',  'readParms' =>  array (),  'writeParms' =>  array (),  'class' => 'left',  'thclass' => 'left',),
			'thread_user'             => array (  'title' => LAN_AUTHOR,  'type' => 'user',  'data' => 'int',  'width' => 'auto',  'help' => '',  'readParms' =>  array (),  'writeParms' =>  array (),  'class' => 'left',  'thclass' => 'left',),
			'thread_user_anon'        => array (  'title' => 'Anon',  'type' => 'text',  'data' => 'safestr',  'width' => 'auto',  'help' => '',  'readParms' =>  array (),  'writeParms' =>  array (),  'class' => 'left',  'thclass' => 'left',),


			'thread_views'            => array (  'title' => 'Views',  'type' => 'number',  'data' => 'int',  'width' => 'auto',  'help' => '',  'readParms' =>  array (),  'writeParms' =>  array (),  'class' => 'left',  'thclass' => 'left',),

			'thread_active'           => array (  'title' => 'Active',  'type' => 'boolean',  'data' => 'int',  'width' => 'auto',  'help' => '', 'batch' => true,   'readParms' =>  array (),  
			'writeParms' => 'enabled=LAN_ACTIVE&disabled=Locked' ,  'class' => 'left',  'thclass' => 'left', 'filter'=>TRUE  ),

			'thread_sticky'           => array (  'title' => 'Sticky',  'type' => 'boolean',  'data' => 'int',  'width' => 'auto',  'help' => '', 'batch' => true,  'readParms' =>  array (),  
			'writeParms' => 'enabled=LAN_FORUM_1011&disabled=LAN_NO' ,  'class' => 'left',  'thclass' => 'left',),

			'thread_lastpost'         => array (  'title' => 'Lastpost',  'type' => 'datestamp',  'data' => 'int',  'width' => 'auto',  'help' => '',  'readParms' =>  array (),  'writeParms' =>  array (),  'class' => 'left',  'thclass' => 'left',  'filter' => false,  'batch' => false,),
 
			'thread_lastuser'         => array (  'title' => 'Lastuser',  'type' => 'user',  'data' => 'int',  'width' => 'auto',  'help' => '',  'readParms' =>  array (),  'writeParms' =>  array (),  'class' => 'left',  'thclass' => 'left',),
			'thread_lastuser_anon'    => array (  'title' => 'Anon',  'type' => 'text',  'data' => 'safestr',  'width' => 'auto',  'help' => '',  'readParms' =>  array (),  'writeParms' =>  array (),  'class' => 'left',  'thclass' => 'left',),

			'thread_total_replies'    => array (  'title' => 'Replies',  'type' => 'number',  'data' => 'int',  'width' => 'auto',  'help' => '',  'readParms' =>  array (),  'writeParms' =>  array (),  'class' => 'left',  'thclass' => 'left',),

			'thread_options'          => array (  'title' => 'Options',  'type' => 'textarea',  'data' => 'str',  'width' => 'auto',  'help' => '',  'readParms' =>  array (),  'writeParms' =>  array (),  'class' => 'left',  'thclass' => 'left',  'filter' => false,  'batch' => false,),

			'options'                 => array (  'title' => LAN_OPTIONS,  'type' => null,  'data' => null,  'width' => '10%',  'thclass' => 'center last',  'class' => 'center last',  'forced' => 'value',  'readParms' =>  array (),  'writeParms' =>  array (),),
		);		
		
		protected $fieldpref = array('thread_id', 'thread_name', 'thread_forum_id', 'thread_views', 'thread_active', 'thread_lastpost', 'thread_sticky', 'thread_datestamp', 'thread_user', 'thread_user_anon', 'thread_lastuser', 'thread_lastuser_anon', 'thread_total_replies' );
		

	//	protected $preftabs        = array('General', 'Other' );
		protected $prefs = array(
		); 

	
		public function init()
		{
			// This code may be removed once plugin development is complete. 
			if(!e107::isInstalled('forum_extended'))
			{
				e107::getMessage()->addWarning("This plugin is not yet installed. Saving and loading of preference or table data will fail.");
			}
			
			$data = e107::getDb()->retrieve('forum', 'forum_id,forum_name,forum_parent', 'forum_id != 0',true);
			$this->forumParents[0] = FORLAN_216;
			$forumSubParents = array();

			foreach($data as $val)
			{
				$id = $val['forum_id'];

				if($val['thread_forum_id'] == 0)
				{
					$this->forumParents[$id] = $val['forum_name'];
				}
				else
				{
					$forumSubParents[$id] = $val['forum_name'];
				}

			}

			$this->fields['thread_forum_id']['writeParms']['optArray'] = $this->forumParents;
 
		}


		public function beforeDelete($data,$id)
		{
			$mes = e107::getMessage();
			$threadId = $id;
			require_once (e_PLUGIN.'forum/forum_class.php');
			$f = new e107forum;
			$ret = $f->threadDelete($threadId);
	 
            if($ret) {
				$mes->addSuccess("Thread deleted"); 
			}
			else {
				$mes->addError("Thread couldn't be deleted"); 
			}
			
			$this->redirect(); 

		}

		
		// ------- Customize Create --------
		
		public function beforeCreate($new_data,$old_data)
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
		
		// left-panel help menu area. (replaces e_help.php used in old plugins)
		public function renderHelp()
		{
			$caption = LAN_HELP;
			$text = 'Some help text';

			return array('caption'=>$caption,'text'=> $text);

		}
			
	/*	
		// optional - a custom page.  
		public function customPage()
		{
			$text = 'Hello World!';
			$otherField  = $this->getController()->getFieldVar('other_field_name');
			return $text;
			
		}
		

	
	 // Handle batch options as defined in forum_thread_form_ui::thread_options;  'handle' + action + field + 'Batch'
	 // @important $fields['thread_options']['batch'] must be true for this method to be detected. 
	 // @param $selected
	 // @param $type
	function handleListThreadOptionsBatch($selected, $type)
	{

		$ids = implode(',', $selected);

		switch($type)
		{
			case 'custombatch_1':
				// do something
				e107::getMessage()->addSuccess('Executed custombatch_1');
				break;

			case 'custombatch_2':
				// do something
				e107::getMessage()->addSuccess('Executed custombatch_2');
				break;

		}


	}

	
	 // Handle filter options as defined in forum_thread_form_ui::thread_options;  'handle' + action + field + 'Filter'
	 // @important $fields['thread_options']['filter'] must be true for this method to be detected. 
	 // @param $selected
	 // @param $type
	function handleListThreadOptionsFilter($type)
	{

		$this->listOrder = 'thread_options ASC';
	
		switch($type)
		{
			case 'customfilter_1':
				// return ' thread_options != 'something' '; 
				e107::getMessage()->addSuccess('Executed customfilter_1');
				break;

			case 'customfilter_2':
				// return ' thread_options != 'something' '; 
				e107::getMessage()->addSuccess('Executed customfilter_2');
				break;

		}


	}
	
		
		
	*/
			
}
				


class forum_thread_form_ui extends e_admin_form_ui
{

	
	// Custom Method/Function 
	function thread_options($curVal,$mode)
	{

		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $this->text('thread_options',$curVal, 255, 'size=large');
			break;
			
			case 'filter':
				return array('customfilter_1' => 'Custom Filter 1', 'customfilter_2' => 'Custom Filter 2');
			break;
			
			case 'batch':
				return array('custombatch_1' => 'Custom Batch 1', 'custombatch_2' => 'Custom Batch 2');
			break;
		}
		
		return null;
	}

}		
		
		
new forum_extended_adminArea();

require_once(e_ADMIN."auth.php");
e107::getAdminUI()->runPage();

require_once(e_ADMIN."footer.php");
exit;

