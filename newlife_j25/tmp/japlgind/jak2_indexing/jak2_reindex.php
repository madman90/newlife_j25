<?php
if (isset($_GET['jak2_reindex']) && ($_GET['jak2_reindex'] == 1)) {
	define( '_JEXEC', 1 );
	define( 'DS', DIRECTORY_SEPARATOR );

	$pathbase = str_replace(DS.'plugins'.DS.'k2'.DS.'jak2_indexing'.DS.'jak2_indexing','',dirname(__FILE__));
	
	define('JPATH_BASE',$pathbase );
	define('JPATH_COMPONENT_ADMINISTRATOR',$pathbase.DS.'administrator'.DS.'components'.DS.'com_k2' );
	
	require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
	require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
	
	$app = JFactory::getApplication('site');
	$app->initialise();
	
	$numitems = JRequest::getVar('numitem',20);
	$start    = JRequest::getVar('start',20);
	
	JPluginHelper::importPlugin('k2');
	$rs = $app->triggerEvent('K2Extrafield_reindex', array($start, $numitems));
	foreach ($rs as $value) {
		if (is_string($value)) echo $value;
		break;
	}
}
?>