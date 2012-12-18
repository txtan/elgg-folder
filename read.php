<?php
	/**
         * @package Elgg
         * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
         * @author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
         * @link http://grc.ucalgary.ca/
         */

	
	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	group_gatekeeper();
	
	$container = get_input('page_owner');
	set_page_owner($container);
	set_context('search');
	$folderguid = get_input('folderguid');
	if(!$folderguid)$folderguid = 'none';
	if($folderguid == 'none'){
		$title = elgg_echo('folder:file:nofolder');
		$area1 = elgg_view_title($title);
		$tfiles = elgg_get_entities(array('types' => 'object', 'subtypes' => 'file', 'container_guid' => $container,'limit'=>9999));
		foreach($tfiles as $file){
			$rel = get_entity_relationships($file->guid, true);
			if(!get_entity_relationships($file->guid,true))
				 $files[] = $file;
		}
		if($files)
			$area1 .=  elgg_view('folder/file/filelist',array('viewtype'=>'all','files'=>$files));
		else
			$area1 .= '<p>No files with unspecified folder</p>';
	}else{
		$folder = get_entity($folderguid);	
		$area1 = elgg_view_title(sprintf(elgg_echo('Folder: %s'),$folder->title));
		$count .= get_entities_from_relationship('folder_of',$folder->getGUID(),false,'object','file',0,'',9999,0,true);	
		if($count > 0){
			$files = get_entities_from_relationship('folder_of',$folder->getGUID(),false,'object','file',0,'',9999,0);
			$area1 .= elgg_view('folder/file/filelist',array('viewtype'=>'singlefolder','files'=>$files));
		}else 
			$area1 .= '<p>No files in folder</p>';
	}
	set_context('folder');
	$area1 .= elgg_view('input/hidden',array('internalname'=>'cfolder','value'=>$folderguid));	
	echo $area1;
	
?>
<script type='text/javascript'>
	$(document).ready(function(){
		var folderguid = "<?php echo $folderguid;?>";
		$('#'+folderguid).css('background-color','#e0e0e0');
	});
	$(this).click(function(event){
		if(event.target.nodeName == 'B' || event.target.nodeName == 'IMG'){
			var folderguid = "<?php echo $folderguid;?>";
			$('#'+folderguid).css('background-color','#FFFFFF');
		}
	});
</script>

