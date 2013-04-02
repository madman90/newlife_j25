<?php
/**
 * ------------------------------------------------------------------------
 * JA K2 Extra Fields Plugin for Joomla 2.5
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die('Restricted access');


jimport('joomla.plugin.plugin');

class plgK2JAK2_Indexing extends JPlugin
{
	public function plgK2JAK2_Indexing (&$subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage(null, JPATH_ADMINISTRATOR);
    }

	// Extend user forms with K2 fields
	public function onAfterK2Save(&$row, $isNew)
	{
		//Extra fields
        $objects = array();
        $variables = JRequest::get('post', 4);
        foreach ($variables as $key=>$value) {
            if (( bool )JString::stristr($key, 'K2ExtraField_')) {
                $object = new JObject;
                $object->set('id', JString::substr($key, 13));
                $object->set('value', $value);
                unset($object->_errors);
                $objects[] = $object;
            }
        }
		
		if (!count($objects)) return ;
		$db = &JFactory::getDBO();
		// get extrafields list for itemid
		$db->setQuery("SELECT DISTINCT id,exfid FROM `#__ja_k2extrafields` WHERE itemid = {$row->id}");
		$list = $db->loadObjectList('exfid');
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'extrafield.php');
		$ext_model = new K2ModelExtraField();
		foreach ($objects as $extrafield) {
            $object = new stdClass();
            $object->itemid = $row->id;
            $object->exfid = $extrafield->id;
            $object->value = is_array($extrafield->value) ? implode(',', $extrafield->value) : $extrafield->value;
            $object->name = method_exists($ext_model, 'getSearchValue') ? $ext_model->getSearchValue($extrafield->id, $extrafield->value) : null;
            if (! array_key_exists($extrafield->id, $list)) {
                $db->insertObject('#__ja_k2extrafields', $object, 'id');
            } else {
                $object->id = $list[$extrafield->id]->id;
                $db->updateObject('#__ja_k2extrafields', $object, 'id');
            }
        }
	}

	public function K2Extrafield_reindex ($start, $numitems)
    {
        //trigger_error('k2Extrafield_reindex', E_USER_ERROR);
        $db = & JFactory::getDBO();
        // Delete table
        if ($start == 0) {
	        $db->setQuery("TRUNCATE TABLE `#__ja_k2extrafields`");
	        $result = $db->query();
        }
        $db->setQuery("SELECT COUNT(id) FROM #__k2_items WHERE trash=0 ");
        $total = $db->loadResult();
        if (! $total || ($total < $start))
            return '1';
        $query = "SELECT id, extra_fields FROM #__k2_items WHERE trash=0 LIMIT $start , $numitems ";
        $db->setQuery($query);
        $list = $db->loadObjectList();
        if (! $list)
            return '1';
        foreach ($list as $item) {
            $item->extra_fields = str_replace(array("\r" , "\r\n" , "\n" , "\\"), '', $item->extra_fields);
            $extrafields = json_decode($item->extra_fields);
            if ($extrafields) {
                foreach ($extrafields as $index) {
                    $object = new stdClass();
                    $object->itemid = $item->id;
                    $object->exfid = $index->id;
                    $object->value = is_array($index->value) ? implode(',', $index->value) : $index->value;
                    $object->name = $this->getSearchValue($index->id, $index->value);
                    $db->setQuery("SELECT * FROM `#__ja_k2extrafields` WHERE itemid = {$object->itemid} AND exfid = {$object->exfid} LIMIT 1");
                    $rs = $db->loadObject();
                    if ($rs) {
                        if ($rs->value != $object->value) {
                            $object->id = $rs->id;
                            $db->updateObject('#__ja_k2extrafields', $object, 'id');
                        }
                    } else {
                        $db->insertObject('#__ja_k2extrafields', $object, 'id');
                    }
                }
            }
        }
        $n = count($list);
        return ($start + $n) . ',' . $start;
    }


    public function getSearchValue($id, $currentValue)
    {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT * FROM #__k2_extra_fields WHERE id = {$id}");
        $row = $db->loadObject();
        
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/lib/JSON.php';
        $json = new Services_JSON();
        $jsonObject = $json->decode($row->value);
        
        $value = '';
        if ($row->type == 'textfield' || $row->type == 'textarea' || $row->type == 'labels') {
            $value = $currentValue;
        } elseif ($row->type == 'multipleSelect') {
            foreach ($jsonObject as $option) {
                if (in_array($option->value, $currentValue)) {
                    $value .= $option->name . ' ';
                }
            }
        } elseif ($row->type == 'link') {
            $value .= $currentValue[0] . ' ';
            $value .= $currentValue[1] . ' ';
        } else {
            if (!is_array($jsonObject)) return '';
            foreach ($jsonObject as $option) {
                if ($option->value == $currentValue) {
                    $value .= $option->name;
                }
            }
        }
        return $value;
    }
}
