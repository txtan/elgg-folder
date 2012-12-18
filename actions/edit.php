<?php
	/**
         * @package Elgg
         * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
         * @author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
         * @link http://grc.ucalgary.ca/
         */


	gatekeeper();
	action_gatekeeper();
	
	$foldertitle = get_input('foldertitle');
	$access_id = get_input('access_id');
	$folderguid = get_input('folderguid');
	
	if(empty($foldertitle)){
		register_error(elgg_echo('folder:title:blank'));
		forward($_SERVER['HTTP_REFERER']);
	}else{
		if($folder = get_entity($folderguid)){
			$folder->access_id = $access_id;
			$folder->title = $foldertitle;
			if(!$folder->save()){
				register_error(elgg_echo('folder:edit:error'));
				forward($_SERVER['HTTP_REFERER']);
			}
			system_message(elgg_echo("folder:edit:success"));
			$page_owner = get_entity($folder->container_guid);
                	if ($page_owner instanceof ElggUser)
                	      	$username = $page_owner->username;
                	else if ($page_owner instanceof ElggGroup)
                  	      $username = "group:" . $page_owner->guid;
			forward($_SERVER['HTTP_REFERER']);
		}else{
			register_error(elgg_echo('folder:notfound'));
			forward($_SERVER['HTTP_REFERER']);
		}
	
	}
	

?>
