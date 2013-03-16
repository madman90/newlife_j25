<?php
/**
 * ------------------------------------------------------------------------
 * JA K2 Fillter Module for Joomla 2.5
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */
defined('_JEXEC') or die('Restricted access');

if (!defined('JA_K2_FILTER_GROUP')) {
    define('JA_K2_FILTER_GROUP', 'mod_ja_group_');
}

jimport('joomla.html.parameter');

$mainframe = JFactory::getApplication();

// Main params
// Select Extra field
$plugin = JPluginHelper::getPlugin( 'search', 'jak2_filter' );

if(!$plugin) {
	 echo JText::_('JAK2_FILTER_INSTALL_PLUGIN_K2_FILTER');
	 return;
} else {
    $plgparams = new JParameter( $plugin->params );
}

//Overriden plugin parameters
$is_override = $params->get("override_plg_config", 0);
if(!empty($is_override) && $is_override == 1)
{
	$show_extra_fields_groups = $params->get('show_extra_fields_groups', 0);
	$show_cats = $params->get("show_cats",0);
	$filter_author = $params->get("filter_author",0);
	$description = $params->get("description","");
	$extrafield_data = $params->get("extrafield_data","");
	
	$plgparams->set("show_extra_fields_groups", $show_extra_fields_groups);
	$plgparams->set("show_cats", $show_cats);
	$plgparams->set("filter_author", $filter_author);
	$plgparams->set("description", $description);
	$plgparams->set("extrafield_data", $extrafield_data);
}
//End overriden

JPluginHelper::importPlugin('search','jak2_filter');
$dispatcher = &JDispatcher::getInstance();
$jaextrafields = array();
$results = $dispatcher->trigger('getExtrafields',array($plgparams));
if ($results) {
	$finded = false;
	foreach ($results as $options) {
		if (is_array($options)) {
			foreach($options as $option) {
				if(is_array($option)) {
					foreach($option as $suboption) {
						if(isset($suboption->group)) {
							$jaextrafields = $options;
							$finded = true;
							break;
						}
					}
					if ($finded) break;
				}
			}
            if ($finded) break;
		}
	}
}

// Generate K2 Extra Fields Groups
if ($plgparams->get('show_extra_fields_groups', 0) == 1) {
	$html = $dispatcher->trigger('genExtraFieldGroups', array($plgparams, JA_K2_FILTER_GROUP));
	$extra_fields_groups_html = '';
	if ($html) {
		foreach ($html as $options) {
			if (is_string($options)) {
				$extra_fields_groups_html = $options;
				break;
			}
		}
	}
}

// For K2 Categories
if ($plgparams->get('show_cats', 0) == 1) {
	$string_cats = $dispatcher->trigger('jak2CatsForm',array($plgparams, JA_K2_FILTER_GROUP));
	$cat_html ='';
	if ($string_cats) {
		foreach ($string_cats as $options) {
			if (is_string($options)) {
                $cat_html = $options;
                break;
			}
		}
	}
}
// End categories

// Generate author input
if ($plgparams->get('filter_author',0) == 1) {
	$created_by = 0;
	if (isset($_COOKIE['jak2_filter'])) {
		$ja_filter = $_COOKIE['jak2_filter'];
		$ja_filter = str_replace(array("\r", "\r\n", "\n","\\"), '', $ja_filter);
		$ja_k2filter = json_decode($ja_filter);
		$created_by = isset($ja_k2filter->created_by) ? $ja_k2filter->created_by : 0;
	}
	
	$db    =& JFactory::getDBO();
	$query = 'SELECT DISTINCT u.*
	    FROM #__users AS u INNER JOIN #__k2_items AS i ON u.id = i.created_by
        WHERE i.published = 1
		ORDER BY u.id';
	
	$db->setQuery( $query );
	$rows = $db->loadObjectList();

	$mitems = array();
	$mitems[] = JHTML::_('select.option',  0, '&nbsp;&nbsp;'. JText::_('JAK2_FILTER_SELECT_AUTHOR') );
	foreach ($rows as $row) {
		$mitems[] = JHTML::_('select.option',  $row->id, '&nbsp;&nbsp;'. $row->username );
	}
	
	$groupname=JA_K2_FILTER_GROUP;
	
	$onchange = "load_extrafields(this.value,\"{$groupname}\")";
	
	$author_html = JHTML::_('select.genericlist',  $mitems, $groupname . 'created_by', 'class="inputbox" style="width:95%;"  size="1" onchange= '.$onchange, 'value', 'text', $created_by );
}
// End generate author input

$search_word = JRequest::getVar('searchword','');
if ($search_word=='jak2_filter')$search_word='';

if (!defined('_MODE_JAK2_FILTER_ASSETS_')) {
    define('_MODE_JAK2_FILTER_ASSETS_', 1);
    
    JHTML::stylesheet('style.css', 'modules/' . $module->module . '/assets/');
    if (is_file(JPATH_SITE . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'css' . DS . $module->module . ".css")) {
        JHTML::stylesheet($module->module . ".css", 'templates/' . $mainframe->getTemplate() . '/css/');
    }
    JHTML::script("script.js", JURI::root() . 'plugins/search/jak2_filter/jak2_filter/', true);
}

require(JModuleHelper::getLayoutPath('mod_jak2_filter'));
?>