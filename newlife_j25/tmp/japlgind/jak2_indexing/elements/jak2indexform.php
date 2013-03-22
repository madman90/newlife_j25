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
defined('_JEXEC') or die( 'Restricted access' );

class JFormFieldJAK2IndexForm extends JFormField
{
	protected $type = 'JAK2IndexForm';
	
	protected function getInput()
	{
		if (!defined('_JAK2INDEXFORM_')) {
            define('_JAK2INDEXFORM_', 1);
            $uri = str_replace(DS, "/", str_replace(JPATH_SITE, JURI::base(), dirname(__FILE__)));
            $uri = str_replace("/administrator", "", $uri);
            
            JHtml::stylesheet('jak2indexform.css', $uri . "/");
            JHtml::script('jak2indexform.js', $uri . "/");
        }
		$db = &JFactory::getDBO();
        $db->setQuery("SELECT COUNT(id) FROM #__k2_items WHERE trash=0");
        $total = $db->loadResult();
        if ($total > 0) {
            $html = '<script type="text/javascript" >
			    var url="' . JURI::root() . 'plugins/k2/jak2_indexing/jak2_indexing/jak2_reindex.php' . '";
			</script>';
            $html .= '<h3>' . JText::_('JAK2_EXTRAFIELDS_TOTAL_ITEMS') . "  " . $total . '</h3>';
            
            $html .= '<div id="ja_reindex">';
            $html .= '<label for="num_item">' . JText::_('JAK2_EXTRAFIELDS_NUMBER_OF_ITEMS') . '</label>';
            $html .= '<select name="num_item" id="num_item" >
						<option value="20" selected>20</option>
						<option value="50">50</option>
						<option value="100">100</option>
						<option value="150">150</option>
						<option value="200">200</option>
					</select>';
            $html .= '<input type="button" class="button" onclick="ja_reindex(0)" value="' . JText::_('JAK2_EXTRAFIELDS_START_REINDEXING') . '" />';
            $html .= '<input type="button" class="button" onclick="form_cancel()" value="Cancel" />';
            $html .= '<div id="loadingspan" style="display:none">Loading...</div>';
            $html .= '<div id="update-status">	</div>';
            $html .= '</div>					';
        
        } else {
            $html = JText::_('JAK2_EXTRAFIELDS_K2_ITEMS_NOT_FOUND');
        }
        
        return $html;
	}
}

?>