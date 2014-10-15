<?php
if ($modx->event->name == 'OnDocFormPrerender') {    
	if (!$id = $modx->controller->resourceArray['id']) {
		return;
	}
	$resources = array();
    foreach ($modx->getParentIds($id, 10, array('context' => 'web')) as $parentId) {
		if ($parentId) array_push($resources, $parentId);		
    }    
	natsort($resources);
	$resources[] = $id;
	
	$setting = $modx->getObject('modSystemSetting', 'settings_version');
    $version = explode('.',$setting->get('value'));
    $url = MODX_MANAGER_URL.'/manager/index.php?a=30&id=';
    if($version[1]==3){
        $url = MODX_MANAGER_URL.'?a=resource/update&id=';
    }
	
	$level = 0;
    $childTemplates = '<a style="color: #333;" href="'.MODX_MANAGER_URL.'">Панель</a> <span style="color: #333;">|</span> ';
    foreach ($resources as $resourceId) {
      $resource = $modx->getObject('modResource', $resourceId);
      if ($resourceId == $id) {
          $childTemplates .= '<span style="color: #333;">'.$resource->get('pagetitle').'</span>';
      } else {
          $childTemplates .= '<a style="color: #333;" href="'.$url.$resource->get('id').'">'.$resource->get('pagetitle').'</a> <span style="color: #333;">|</span> ';
      }
      $level++; 
    }

	
	$modx->controller->addHtml('
	<script>'."
		Ext.onReady(function() {		
			var title = Ext.select('#modx-resource-header');
			var pagetitle = Ext.select('#modx-resource-pagetitle');
			
			title.createChild('<p style=\"padding-bottom: 15px;\">$childTemplates</p>');
			pagetitle.on('keyup', function(){
				title.createChild('<p style=\"padding-bottom: 15px;\">$childTemplates</p>');
			});			
		});					
		</script>	
	".'</script>');
	
	return;
}
