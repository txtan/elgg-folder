<?php
	/**
         * @package Elgg
         * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
         * @author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
         * @link http://grc.ucalgary.ca/
         */

	function folder_init(){
		extend_view('css','folder/css');
		extend_view('file/upload','folder/file/upload');
		extend_view('publication/embed/upload','folder/file/upload');
		extend_view('metatags','folder/metatags');
		register_entity_type('object','folder');
	}
	
	function folder_pagesetup(){
		global $CONFIG;
		$context = get_context();
		$page_owner = page_owner_entity();
		if($context == 'file'){
			if(isloggedin() && can_write_to_container($_SESSION['guid'],page_owner())){
				if($page_owner instanceof ElggGroup)
					add_submenu_item(sprintf(elgg_echo('folder:user'),$page_owner->name), $CONFIG->wwwroot."pg/folder/".$page_owner->username."/yourfolder");
				else
					add_submenu_item(elgg_echo('folder:your'), $CONFIG->wwwroot."pg/folder/".$page_owner->username."/yourfolder");
			}else{
				add_submenu_item(elgg_echo('folder:view'),$CONFIG->wwwroot."pg/folder/".$page_owner->username."/yourfolder");
			}
		}else if($context == 'folder'){
			if(isloggedin()){
				if($page_owner instanceof ElggGroup){
					add_submenu_item(sprintf(elgg_echo('folder:user'),$page_owner->name), $CONFIG->wwwroot."pg/folder/".$page_owner->username."/yourfolder");
					add_submenu_item(sprintf(elgg_echo("file:user"),$page_owner->name), $CONFIG->wwwroot . "pg/file/" . $page_owner->username);
				}else{
				if($page_owner->guid != $_SESSION['guid']){
					add_submenu_item(sprintf(elgg_echo('folder:user'),$page_owner->name), $CONFIG->wwwroot."pg/folder/".$page_owner->username."/yourfolder");
				}
					add_submenu_item(elgg_echo('folder:your'), $CONFIG->wwwroot."pg/folder/".$_SESSION['username']."/yourfolder");
				add_submenu_item(sprintf(elgg_echo('folder:friends'),$page_owner->name), $CONFIG->wwwroot . "pg/folder/". $_SESSION['username'] . "/friends/");
					add_submenu_item(elgg_echo('file:yours'),$CONFIG->wwwroot."pg/file/".$_SESSION['username']);
				}
			}
		}
	}

	function folder_pagehandler($page){
		global $CONFIG;
		if(isset($page[0]))
			set_input('username',$page[0]);
		if(isset($page[1])){
			switch($page[1]){
				case "editfolder":
					if(isset($page[2]))
						set_input('guid',$page[2]);
					include($CONFIG->pluginspath . "folder/add.php");
					break;
				case "addfolder":
					include($CONFIG->pluginspath . "folder/add.php");
					break;
				case "yourfolder":
					include($CONFIG->pluginspath . "folder/index.php");
					break;
				case "friends":
					include($CONFIG->pluginspath. "folder/friends.php");
					break;
				case "read":
					if(isset($page[2]))
						set_input('folderguid', $page[2]);
					include($CONFIG->pluginspath . "folder/read.php");
					break;
			}
		}
	}
	
	function file_save_handler($event, $type, $object){
		if($folder_guid = get_input('folder')){
			remove_entity_relationships($object->getGUID(), 'folder_of',true);
			$object->folder = $folder_guid;
			if($folder_guid != 'none'){
				add_entity_relationship($folder_guid, 'folder_of', $object->getGUID());
			}
		}
	}	

	function folder_url($entity){
		global $CONFIG;
		return $CONFIG->url . "pg/folder/" .$entity->getOwnerEntity()->username ."/read/" .$entity->getGUID();
	}
		
	register_entity_url_handler('folder_url','object','folder');
	register_elgg_event_handler('init','system','folder_init');
	register_elgg_event_handler('pagesetup','system','folder_pagesetup');
	register_elgg_event_handler('update','object','file_save_handler');
	register_elgg_event_handler('create','object','file_save_handler');
	register_page_handler('folder','folder_pagehandler');
	register_action("folder/add",false,$CONFIG->pluginspath . "folder/actions/add.php");
	register_action("folder/edit",false,$CONFIG->pluginspath . "folder/actions/edit.php");
	register_action("folder/movefile",false,$CONFIG->pluginspath . "folder/actions/movefile.php");
	register_action("folder/delete",false,$CONFIG->pluginspath . "folder/actions/delete.php");

?>
