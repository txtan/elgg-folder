<?php
	/**
         * @package Elgg
         * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
         * @author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
         * @link http://grc.ucalgary.ca/
         */


	gatekeeper();
	action_gatekeeper();
	
	$fileguid = get_input('fileguid');
	$file = get_entity($fileguid);
	if($file->canEdit()){
		$folderguid = get_input('folderguid');	
		//remove file from previous folder
		$file->folder = $folderguid;
		remove_entity_relationships($fileguid, 'folder_of', true);
		if($folderguid != 'none')
			//add to new folder
			add_entity_relationship($folderguid, 'folder_of', $fileguid);
	}
?>
