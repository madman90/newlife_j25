<?php
/**
 * ------------------------------------------------------------------------
 * JA K2 Search Plugin for Joomla 2.5
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */
defined('_JEXEC') or die( 'Restricted access' );

if (! function_exists("objectToArray")) {
    function objectToArray ($object)
    {
        if (! is_object($object) && ! is_array($object)) {
            return $object;
        } elseif (is_array($object)) {
            return $object;
        }
        if (is_object($object)) {
            $object = get_object_vars($object);
        }
        return array_map('objectToArray', $object);
    }
}

if (! function_exists('ja_k2_extra_field_sort')) {
    function ja_k2_extra_field_sort ($a, $b)
    {
        if ($a->group == $b->group) {
            if ($a->order == $b->order)
                return 0;
            return ($a->order < $b->order) ? - 1 : 1;
        }
        return ($a->group < $b->group) ? - 1 : 1;
    }
}

class JFormFieldJaExtraField extends JFormField
{
	protected $type = 'JaExtraField';
	
	protected function getInput()
	{
        if (!defined('_JA_EXTRAFIELDS_')) {
            define('_JA_EXTRAFIELDS_', 1);
            $uri = str_replace(DS, "/", str_replace(JPATH_SITE, JURI::base(), dirname(__FILE__)));
            $uri = str_replace("/administrator", "", $uri);
            
            JHTML::stylesheet('jaextrafields.css', $uri . "/");
            JHTML::script('jaextrafields.js', $uri . "/");
        }
		
		$value = $this->value;
        $name = $this->fieldname;
        
        // for type_options
        $type_options = array();
        $type_options[] = JHTML::_('select.option', 'text', JText::_('JAK2_FILTER_TEXTBOX'));
        $type_options[] = JHTML::_('select.option', 'jarange', JText::_('JAK2_FILTER_RANGE_VALUES'));
        $type_options[] = JHTML::_('select.option', 'select', JText::_('JAK2_FILTER_DROPDOWN_SELECTION'));
        $type_options[] = JHTML::_('select.option', 'multi', JText::_('JAK2_FILTER_MULTI_SELECT'));
        $type_options[] = JHTML::_('select.option', 'radio', JText::_('JAK2_FILTER_RADIO'));
        
        $type_options1 = array();
        $type_options1[] = JHTML::_('select.option', 'text', JText::_('JAK2_FILTER_TEXTBOX'));
        $type_options1[] = JHTML::_('select.option', 'jarange', JText::_('JAK2_FILTER_RANGE_VALUES'));
        
        $value = str_replace(array("\r", "\r\n", "\n", "\\"), '', $value);
        $extraSelect = json_decode($value, true);
        $extraSelect = objectToArray($extraSelect);
        $db = &JFactory::getDBO();
        
        $query = "SELECT t.id, t.name, t.value, t.type, t.group, t.published, t.ordering, g.id as gid, g.name as gname FROM #__k2_extra_fields_groups AS g LEFT JOIN #__k2_extra_fields t ON t.group = g.id WHERE t.published =1 ORDER BY t.group ";
        $db->setQuery($query);
        $extra_fields = ($db->loadObjectList('id'));
		
		if (!$extra_fields) {
            return JText::_("JAK2_FILTER_DONT_INSTALL_K2");
        }
		
		// parse list
		$max_order = 0;
        if (is_array($extraSelect)) {
            foreach ($extraSelect as $sitem) {
                $max_order = max(array($max_order, $sitem['order']));
            }
        }
        $max_order++;
        foreach ($extra_fields as $item) {
            $item->checked = '';
            $item->disabled = 'disabled';
            $item->hidden = 'none';
            $item->el_type = $item->type;
            $item->custom_value = '';
            if (isset($extraSelect[$item->id])) {
                $item->checked = 'checked';
                $item->order = $extraSelect[$item->id]['order'];
                $item->el_type = $extraSelect[$item->id]['type'];
                $item->custom_value = $extraSelect[$item->id]['ranges'];
                if ($item->el_type == 'jarange')
                    $item->hidden = '';
                $item->disabled = '';
            } else
                $item->order = $max_order++;
        }
		usort($extra_fields, 'ja_k2_extra_field_sort');
        // end parse list
        if (!$value)
            $value = "";
        $html = "<input type=\"hidden\" id=\"extrafield_data_id\" name=\"jform[params][$name]\" value='{$value}'/>";
        
        $html .= '<table class="adminlist" cellspacing="0" style="float:left;">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th class="title" valign="middle">
					<input type="checkbox" id="chkAll" onclick ="checkAll(this.checked);">
					</th>';
        $html .= '<th class="title" valign="middle">' . JText::_('JAK2_FILTER_NAME') . '</th>';
        $html .= '<th class="title" valign="middle">' . JText::_('JAK2_FILTER_FILTER_TYPE') . '</th>';
        $html .= '<th class="title" valign="middle">' . JText::_('JAK2_FILTER_ORDER') . '</th>';
        $html .= '<th class="title" valign="middle">' . JText::_('JAK2_FILTER_ID') . '</th>';
        $html .= '</tr>';
        $html .= '</thead>';
	
		$group = '';
		foreach ($extra_fields as $i => $item) {
            if ($group != $item->gid) {
                // $group = $item->group;
                $group = $item->gid;
                $html .= '<tbody id="group_' . $item->gid . '">';
                $html .= '<tr><td colspan="5"> <strong>'
                    . JText::_('JAK2_FILTER_GROUP') . ": " . $item->gname
                    . ' </strong></td></tr>';
            }
            $html .= '<tr class="row' . ($i % 2) . '">';
            $html .= '<td align="center">';
            $html .= '<input type="checkbox" onclick="updateExtrafields(\'' . $item->id . '\', this.checked)"'
                . ' value="' . $item->id . '" class="inputbox"  id="extrafield_' . $item->id . '"  '
                . $item->checked . '>';
            $html .= '</td>';
            $html .= '<td>';
            $html .= '<label for="extrafield_' . $item->id . '" >' . $item->name . '  [<em>' . $item->type . '</em>]</label>';
            $html .= '</td>';
            $html .= '<td >';
            if (($item->type == 'textfield') || ($item->type == 'textarea')) {
                $html .= JHTML::_(
                        'select.genericlist',
                        $type_options1,
                        "paramsfield_type_{$item->id}",
                        "class=\"inputbox\" {$item->disabled} size=\"1\" onchange=\"updateExtrafields({$item->id},1) \" ",
                        'value',
                        'text',
                        $item->el_type
                );
            } else {
                $html .= JHTML::_(
                        'select.genericlist',
                        $type_options,
                        "paramsfield_type_{$item->id}",
                        "class=\"inputbox\" {$item->disabled} size=\"1\" onchange=\"updateExtrafields({$item->id},1) \" ",
                        'value',
                        'text',
                        $item->el_type
                );
            }
            $html .= '</td>';
            $html .= '<td align="center" >';
            $html .= '  <input type="text" id="paramsfield_order_' . $item->id . '"'
                . ' name="paramsfield_order_' . $item->id . '" value="' . $item->order . '" ' .
                $item->disabled . ' size="5" maxlength="2" onchange="updateExtrafields(' . $item->id . ',1)"/>';
            $html .= '</td>';
            $html .= '<td align="center">';
            $html .= $item->id;
            $html .= '</td>';
            $html .= '</tr>';
            
            $html .= '<tr id="jacustom_type_' . $item->id . '" style=" display:' . $item->hidden . '" class="row' . ($i % 2) . '">';
            $html .= '<td align="center" colspan="2" >';
            $html .= JText::_('JAK2_FILTER_ADD_VALUE_OPTIONS');
            $html .= '</td>';
            $html .= '<td align="center" colspan="4">';
            $html .= '<div><label for="custom_value_' . $item->id . '"'
                . ' title="' . JText::_('JAK2_FILTER_ONE_OPTION_PER_LINE') . '" >'
                . JText::_('JAK2_FILTER_ONE_OPTION_PER_LINE')
                . '</label> </div>';
            $html .= '<textarea id="custom_value_' . $item->id . '" cols="30" rows="5" class="inputbox"'
                . ' onchange="updateExtrafields(' . $item->id . ',1)">'
                . str_replace(";", "\n", $item->custom_value) . '</textarea>';
            $html .= '</td>';
            $html .= '</tr>';
            
            if ($group != $item->gid) {
                $html .= '</tbody>';
            }
        }
        $html .= '</table>';
		
		return $html;
	}
}

?>