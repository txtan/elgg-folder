<?php
	/**
         * @package Elgg
         * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
         * @author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
         * @link http://grc.ucalgary.ca/
         */


	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	group_gatekeeper();
	
	$page_owner = page_owner_entity();
	if ($page_owner === false || is_null($page_owner)) {
		$page_owner = $_SESSION['user'];
		set_page_owner($_SESSION['guid']);
	}
	if ($page_owner instanceof ElggGroup)
		$container = $page_owner->guid;

	if($container){
                $title = elgg_echo('folder:group');
        }else{
		$container = $page_owner->guid;
		if($page_owner->guid != $_SESSION['guid']) 
                	$title = sprintf(elgg_echo('folder:user'),$page_owner->name);
		else 
			$title = elgg_echo('folder:your');
	}
	$area1 = elgg_view_title($title);
	$area1 .= elgg_view("folder/manage", array('container_guid' => $container));
	
	page_draw($title,elgg_view_layout("two_column_left_sidebar", '', $area1));

		
?>
