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

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

/**
 * JA K2 Filter Search
 *
 * @package Plugin
 */
class plgSearchJAK2_Filter extends JPlugin
{
    var $group_cat = '0_0';
    var $current_search_ob = null;
    var $groupname = 'plg_ja_group_';

    /**
     * Constructor
     *
     * @param string &$subject Subject
     * @param string $config   Config
     */
    public function plgSearchJAK2_Filter (&$subject, $config)
    {
        parent::__construct($subject, $config);
        $this->_plugin = JPluginHelper::getPlugin('search', 'jak2_filter');
        $this->_params = new JParameter($this->_plugin->params);
        $this->current_search_ob = $this->getInputSearchParams();
        if (isset($this->current_search_ob->catid)) {
            $this->group_cat = $this->current_search_ob->catid;
        }
        if (isset($this->current_search_ob->created_by)) {
            $this->created_by = $this->current_search_ob->created_by;
        }
        $this->loadLanguage(null, JPATH_ADMINISTRATOR);
    }

    /**
     * Wrap action in J1.7
     *
     * @return string
     */
    public function onContentSearchAreas()
    {
        return $this->onSearchAreas();
    }

    /**
     * Wrap action in J1.7
     *
     * @param string $text      Target search string
     * @param string $phrase    mathcing option, exact|any|all
     * @param string $ordering  ordering option, newest|oldest|popular|alpha|category
     * @param string $areas     An array if the search it to be restricted to areas, null if search all
     *
     * @return array
     */
    public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
    {
        return $this->onSearch($text, $phrase, $ordering, $areas);
    }
    
    /**
     * Generate search areas
     *
     * @return string
     */
    public function onSearchAreas ()
    {
        static $areas = array();

        $form = $this->renderFormHTML();
        /*updated: Hide filter form when load result search page*/
        $hide_filter_form = $this->_params->get("hide_filter_form", 0);
        $hide_filter_form = $hide_filter_form == '1' ? 'true' : 'false';
        /*end updated*/
        // Fix bug there is simple quote character in $form
        $form = str_replace(array("\r" , "\n", "'"), array("" , "", "&#039;"), $form);
        $inline_label = $this->_params->get('inline_label', 0);

        $js = "<script type=\"text/javascript\">
        window.addEvent('load', function() {

            var show_extra_fields_groups = {$this->_params->get('show_extra_fields_groups', 0)};
            var show_cats = {$this->_params->get('show_cats',0)};
            var filter_author = {$this->_params->get('filter_author',0)};
            var groupname = '{$this->groupname}';

            hide_k2_filter = {$hide_filter_form};
            load_jak2_filter(unescape('{$form}'),'{$inline_label}');

            if ($('area_jak2')&&$('area_jak2').checked) {
                $('search-searchword').addEvent('keydown', function(e) {
                    e = e || window.event;
                    var code =0;
                    if(e)      code = e.keyCode || e.which;
                    if(code == 13) {
                        if(!$('area_jak2') || !$('area_jak2').checked) {
                            $('ja_searchword').value =$('search-searchword').value;
                            $('jaarea').value='';
                        } else {
                            build_jafilter('plg_ja_filterform','plg_ja_group_');
                        }
                        $('searchForm').submit();
                        return false;
                    }
                });

                // Get submit data
                jak2_filter = Cookie.read('jak2_filter');
                if (jak2_filter) jak2_filter = eval('(' + jak2_filter + ')');

                // Restore extra fields groups selection
                if (jak2_filter && show_extra_fields_groups) {
                    var sbEFGroups = $('searchForm').getElementById(groupname + 'efgroups');
                    if (sbEFGroups) {
                        var options = sbEFGroups.options;
                        var efgroups = jak2_filter['extra_fields_groups'];
                        for(i = 0; i < options.length; i++) {
                            if (options[i].value == efgroups) {
                                options[i].selected = 1;
                                break;
                            }
                        }
                    }
                }

                if (show_extra_fields_groups) load_categories($('searchForm').getElementById(groupname + 'efgroups').value, groupname);

                // Restore categories selection
                if (jak2_filter && show_cats) {
                    var sbCatid = $('searchForm').getElementById(groupname + 'catid');
                    if (sbCatid) {
	                    var options = sbCatid.options;
	                    var catid   = jak2_filter['catid'];
	                    for(i = 0; i < options.length; i++) {
	                        if (options[i].value == catid) {
	                            options[i].selected = 1;
	                            break;
	                        }
	                    }
                    }
                }

                if (show_cats) load_extrafields($('searchForm').getElementById(groupname + 'catid').value, groupname);

                if (filter_author) load_extrafields($('searchForm').getElementById(groupname + 'created_by').value, groupname);
            }

            $('searchForm').getElement(\"button[name=Search]\").onclick = function(e) {
                if(!$('area_jak2') || !$('area_jak2').checked) {
                    $('ja_searchword').value =$('search-searchword').value;
                    $('jaarea').value='';
                } else {
                    build_jafilter('plg_ja_filterform', groupname);
                }
                return $('searchForm').submit();
            };

            $('area_jak2').addEvent('click', function() {
                var category = $(groupname+'catid');
                if (category) {
                    load_extrafields(category.value, groupname);
                }
            });
        });
        </script>";
        
        $doc = JFactory::getDocument();
        $doc->addCustomTag(JHTML::script('script.js', JURI::root() . 'plugins/search/jak2_filter/jak2_filter/', true));
        $doc->addCustomTag(JHTML::stylesheet('style.css', JURI::root() . 'plugins/search/jak2_filter/jak2_filter/', true));
        $areas = array('jak2_filter' => $this->_params->get('searcharea_label', JText::_('JAK2_FILTER')));
        $areas['jak2_filter'] .= $js;
        return $areas;
    }

    /**
     * Search text
     *
     * @param string $text      Target search string
     * @param string $phrase    mathcing option, exact|any|all
     * @param string $ordering  ordering option, newest|oldest|popular|alpha|category
     * @param string $areas     An array if the search it to be restricted to areas, null if search all
     *
     * @return array
     */
    public function onSearch($text, $phrase = '', $ordering = '', $areas = null)
    {
//        echo "<pre>";
//        print_r($areas);
//        echo "</pre>";
//        die('ku1');
        if ($areas) {
            foreach ($areas as $area) {
                $area = JFilterInput::clean($area, 'cmd');
            }
        } else {
            $areas = array();
        }
        if (is_array($areas)) {
            if (!array_intersect($areas, array_keys($this->onSearchAreas()))) {
                return array();
            }
        }

        $db   = JFactory::getDBO();
        $now  = JFactory::getDate()->toMySQL();
        $nullDate = $db->getNullDate();
        $user   = JFactory::getUser();
        $access = $user->getAuthorisedViewLevels();

        include_once JPATH_SITE . '/administrator/components/com_search/helpers/search.php';
        include_once JPATH_SITE . '/components/com_k2/helpers/route.php';
        $searchText = $text;
        
        $sql = array();
        if ($areas && (in_array('jak2_filter', $areas))) {
            $inputParams = $this->getInputSearchParams();
            if ($inputParams) {
                if (!empty($inputParams->created_by)) {
                    $sql[] = " i.created_by = " . $db->quote($inputParams->created_by);
                }
                if (isset($inputParams->catid) && ($inputParams->catid != '0_0')) {
                    include_once JPATH_SITE . '/components/com_k2/models/itemlist.php';
                    $cat_tmp = explode('_', $inputParams->catid);
                    if (count($cat_tmp) == 2) {
                        $inputParams->catid = $cat_tmp[1];
                    }
                    // Check function for compatible K2 2.4 & 2.5
                    $k2ModelMethod = 'getCategoryChilds';
                    if (!method_exists('K2ModelItemlist', $k2ModelMethod)) {
                        $k2ModelMethod = 'getCategoryChildren';
                    }
                    // Get categories
                    $cats = K2ModelItemlist::$k2ModelMethod($inputParams->catid);
                    $cats = array_merge($cats, array($inputParams->catid));
                    $sql[] = " i.catid IN (" . implode(',', $cats) . ")";
                    if ($inputParams->catid == 0)
                        $inputParams = array();
                    unset($inputParams->catid);
                }
                $jasql = $this->k2PrepareSearch($inputParams);
                if (is_string($jasql)) {
                    $sql[] = $jasql;
                }
                if (!empty($inputParams->extra_fields_groups)) {
                	$sql[] = ' efg.id = ' . $db->Quote($inputParams->extra_fields_groups);
                }
            }
        }

        $pluginParams = $this->getParams();
        $limit = $pluginParams->def('search_limit', 50);
        $text = JString::trim($text);
        if (($limit > 0) && ($text != '') && ($text != 'customsearch')) {
            $tagIDs = array();
            $itemIDs = array();
            if ($pluginParams->get('search_tags')) { // for search tags
                $tagQuery = JString::str_ireplace('*', '', $text);
                $words = explode(' ', $tagQuery);
                for ($i = 0; $i < count($words); $i ++) {
                    $words[$i] .= '*';
                }
                $tagQuery = implode(' ', $words);
                $tagQuery = $db->Quote($db->getEscaped($tagQuery, true), false);
                $query = "SELECT id FROM #__k2_tags WHERE MATCH(name) AGAINST ({$tagQuery} IN BOOLEAN MODE) AND published=1";
                $db->setQuery($query);
                $tagIDs = $db->loadResultArray();
                if (count($tagIDs)) {
                    JArrayHelper::toInteger($tagIDs);
                    $query = "SELECT itemID FROM #__k2_tags_xref WHERE tagID IN (" . implode(',', $tagIDs) . ")";
                    $db->setQuery($query);
                    $itemIDs = $db->loadResultArray();
                }
            }
            // Check search by language or not
            // If exist joomfish and current language is different default language
            $k2sql = $this->prepareSearch($text, $phrase);
            $sql2 = $k2sql . "
                AND i.trash = 0
                AND i.published = 1
                AND i.access  IN(".implode(',', $access).")
                AND c.published = 1
                AND c.access  IN(".implode(',', $access).")
                AND c.trash = 0
                AND ( i.publish_up = " . $db->Quote($nullDate) . " OR i.publish_up <= " . $db->Quote($now) . " )
                AND ( i.publish_down = " . $db->Quote($nullDate) . " OR i.publish_down >= " . $db->Quote($now) . " )";
            if ($pluginParams->get('search_tags') && count($itemIDs)) {
                JArrayHelper::toInteger($itemIDs);
                $sql2 .= "  OR i.id IN (" . implode(',', $itemIDs) . ") ";
            }
            $sql[] = " ( {$sql2} ) ";
        }

        $searchLanguage = $pluginParams->get('search_language', 1);
        $queryTranslate = "";
        if ($searchLanguage) {
            $queryTranslate = "i.id as key1, c.id as key2, ";
        }
        $query = "
            SELECT
              $queryTranslate
              i.title,
              i.metadesc,
              i.metakey,
              c.name as section,
              i.image_caption,
              i.image_credits,
              i.video_caption,
              i.video_credits,
              i.extra_fields_search,
              i.created,
              i.introtext,
              CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(':', i.id, i.alias) ELSE i.id END as slug,
              CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(':', c.id, c.alias) ELSE c.id END as catslug
            FROM #__k2_items AS i
                INNER JOIN #__k2_categories AS c ON c.id=i.catid AND c.access  IN(".implode(',', $access).")
                INNER JOIN #__k2_extra_fields_groups AS efg ON c.extraFieldsGroup = efg.id
        ";
        $sql[] = " i.published = 1 ";
        if (count($sql) > 0) {
            $query .= "WHERE " . implode("   AND  ", $sql);
        }
        $query .= " GROUP BY i.id ";
        switch ($ordering) {
            case 'oldest':
                $query .= 'ORDER BY i.created ASC';
                break;
            case 'popular':
                $query .= 'ORDER BY i.hits DESC';
                break;
            case 'alpha':
                $query .= 'ORDER BY i.title ASC';
                break;
            case 'category':
                $query .= 'ORDER BY c.name ASC, i.title ASC';
                break;
            case 'newest':
            default:
                $query .= 'ORDER BY i.created DESC';
                break;
        }
        //self::log($query);
        $db->setQuery($query, 0, $limit);
        $list = $db->loadObjectList();
        if (! $list)
            return array();
        $limit -= count($list);
        $results = array();
        foreach ($list as $key => $item) {
            $item->text = $item->introtext;
            $item->href = JRoute::_(K2HelperRoute::getItemRoute($item->slug, $item->catslug));
            $item->tag = $searchText;
            $item->browsernav = '';
            if (searchHelper::checkNoHTML($item, $searchText, array('text' , 'title' , 'metakey' , 'metadesc' , 'section' , 'image_caption' , 'image_credits' , 'video_caption' , 'video_credits' , 'extra_fields_search' , 'tag'))) {
                $results[] = $item;
            }
        }
        return $results;
    }

    /**
     * Get params
     *
     * @return array
     */
    public function getParams ()
    {
        return $this->_params;
    }

    /**
     * Prepare SQL string
     *
     * @param string &$inputParams Input search parameters
     *
     * @return string
     */
    public function k2PrepareSearch(&$inputParams)
    {
    	$extrafield_data = $this->_params->get('extrafield_data', "");
        $extrafield_data = str_replace(array("\r" , "\r\n" , "\n" , "\\"), '', $extrafield_data);
        $extrafield_data = json_decode($extrafield_data, true);
        $sql = array();
        foreach ($inputParams as $key => $value) {
            if (strpos($key, 'field_') !== false) {
                $key = str_replace('field_', '', $key);
                if ((intval($key) > 0) && ($value)) {
                	$type = $extrafield_data[intval($key)]['type'];
                	if ($type == 'jarange') {
                        $values = explode('-', $value);
                	} else {
                        $values = array();
                	}
                    if (count($values) == 2) {
                        sort($values);
                        //Fix if values are currency
                        $pattern = '/^\d+|\d+$/';
                        if (preg_match($pattern, $values[0], $matches0, PREG_OFFSET_CAPTURE)
                         && preg_match($pattern, $values[1], $matches1, PREG_OFFSET_CAPTURE)
                        ) {
                        	$value0 = floatval(substr($values[0], $matches0[0][1]));
                        	$value1 = floatval(substr($values[1], $matches1[0][1]));
                        	if ($value0 > $value1) {
                        		$tmp = $value0;
                        		$value0 = $value1;
                        		$value1 = $tmp;
                        	}
                        	$key = intval($key);
                            $sql[] = " (
                                `exfid` = {$key}
                                 AND (
                                    CAST(SUBSTR(`value`, {$matches0[0][1]}+1) AS DECIMAL)
                                    BETWEEN  {$value0}
                                    AND {$value1}
                                 )
                            ) ";
                        } else {
                            $sql[] = " (
                                `exfid` =" . intval($key) . "
                                AND (`value` BETWEEN  '{$values[0]}' AND '{$values[1]}')
                                AND (length(`value`) BETWEEN length('{$values[0]}') AND length('{$values[1]}'))
                            ) ";
                        }
                    } else {
                        $values = explode(',', $value);
                        if (count($values) == 1) {
                            $sql[] = " (`exfid` =" . intval($key) . "  AND `value` LIKE '%$value%' )";
                        } else {
                            // for multi select or between
                            $subsql = array();
                            foreach ($values as $sub) {
                                $subsql[] = " (value LIKE '%$sub%') ";
                            }
                            $sql[] = " (`exfid` =" . intval($key) . "  AND (" . implode(" OR  ", $subsql) . ") )";
                        }
                    }
                }
            }
        }
        if (count($sql) > 0) {
            $db = & JFactory::getDBO();
            $k = true;
            foreach ($sql as $dk) {
                $db->setQuery(" SELECT DISTINCT itemid FROM #__ja_k2extrafields WHERE " . $dk);
                $rs = $db->loadResultArray();
                if (! $rs)
                    $rs = array();
                if ($k) {
                    $ja_filter_items = $rs;
                    $k = false;
                } else {
                    if (count($ja_filter_items) == 0)
                        break;
                    $ja_filter_items = array_values($ja_filter_items);
                    if (count($rs) == 0) {
                        $ja_filter_items = array();
                        break;
                    }
                    $n = count($ja_filter_items);
                    for ($i = $n - 1; $i >= 0; -- $i) {
                        if (! in_array($ja_filter_items[$i], $rs)) {
                            unset($ja_filter_items[$i]);
                        }
                    }
                }
            }
            JArrayHelper::toInteger($ja_filter_items);
            if (count($ja_filter_items) == 0)
                $ja_filter_items = array(0);
            return " i.id IN (" . implode(',', $ja_filter_items) . ")";
        }
        return false;
    }

    /**
     * getLayoutPath
     *
     * @param string $plugin Plugin
     * @param string $layout Layout
     *
     * @return string
     */
    public function getLayoutPath ($plugin, $layout = 'default')
    {
        $app = JFactory::getApplication();
        // Build the template and base path for the layout
        $tPath = JPATH_BASE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . $plugin->name . DS . $layout . '.php';
        $bPath = JPATH_BASE . DS . 'plugins' . DS . $plugin->type . DS . $plugin->name . DS . $plugin->name . DS . 'tmpl' . DS . $layout . '.php';
        // If the template has a layout override use it
        if (file_exists($tPath)) {
            return $tPath;
        } elseif (file_exists($bPath)) {
            return $bPath;
        }
        return '';
    }

    /**
     * generateAuthorSelect
     *
     * @param string $groupname Group name
     *
     * @return string
     */
    public function generateAuthorSelect ($groupname = 'plg_ja_group_')
    {
        $created_by = isset($this->created_by) ? $this->created_by : 0;
        $db = & JFactory::getDBO();
        $query = 'SELECT DISTINCT u.*
            FROM #__users AS u INNER JOIN #__k2_items AS i ON u.id = i.created_by
            WHERE i.published = 1
            ORDER BY u.id';
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $mitems = array();
        $mitems[] = JHTML::_('select.option', 0, '&nbsp;&nbsp;' . JText::_("JAK2_FILTER_SELECT_AUTHOR"));
        foreach ($rows as $row) {
            $mitems[] = JHTML::_('select.option', $row->id, '&nbsp;&nbsp;' . $row->username);
        }
        $onchange = "load_extrafields(this.value,\"{$groupname}\")";
        $author_html = JHTML::_(
                'select.genericlist', $mitems, $groupname . 'created_by',
                'class="inputbox" style="width:95%;"  size="1" onchange= ' . $onchange,
                'value', 'text', $created_by
        );
        return $author_html;
    }

    /**
     * renderFormHTML
     *
     * @return string
     */
    public function renderFormHTML ()
    {
        $params = $this->getParams();
        if ($params->get('show_extra_fields_groups') == 1) {
        	$extra_fields_groups_html = $this->genExtraFieldGroups($params);
        }
        if ($params->get('show_cats', 0) == 1) {
            $cat_html = $this->jak2CatsForm($params, 'plg_ja_group_');
        }
        if ($params->get('filter_author', 0) == 1) {
            $author_html = $this->generateAuthorSelect();
        }
        $jaextrafields = $this->getExtrafields($params);
        $layout = $this->getLayoutPath($this->_plugin, 'jak2_filter');
        if ($layout) {
            ob_start();
            include $layout;
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
        return "";
    }

    /**
     * Get input search parameters
     *
     * @return mixed  Array of parameters if cookie jak2_filter exists, otherwise FALSE
     */
    public function getInputSearchParams ()
    {
        if (isset($_COOKIE['jak2_filter'])) {
            $inputParams = $_COOKIE['jak2_filter'];
            $inputParams = str_replace(array("\r" , "\r\n" , "\n" , "\\"), '', $inputParams);
            $inputParams = json_decode($inputParams);
            return $inputParams;
        }
        return false;
    }

    /**
     * getExtrafields
     *
     * @param mixed $params parameters
     *
     * @return array
     */
    public function getExtrafields ($params)
    {
        $extrafield_data = $params->get('extrafield_data', "");
        $extrafield_data = str_replace(array("\r" , "\r\n" , "\n" , "\\"), '', $extrafield_data);
        $extrafield_data = json_decode($extrafield_data, true);
        $fids = is_array($extrafield_data) ? array_keys($extrafield_data) : array();
        if (count($fids) == 0)
            return false;
        $db = & JFactory::getDBO();
        $query = "SELECT t.id, t.name, t.value, t.type, t.group, t.published FROM #__k2_extra_fields t WHERE t.published =1  AND t.id IN (" . implode(',', $fids) . ") ORDER BY t.group";
        $db->setQuery($query);
        $fields = $db->loadObjectList('id');
        $ja_k2filter = $this->getInputSearchParams();
        if (! $ja_k2filter)
            $ja_k2filter = array();
        else
            $ja_k2filter = JArrayHelper::fromObject($ja_k2filter);
        $jaextrafields = array();
        foreach ($fields as $item) {
            $item->order = 100;
            $item->ranges = '';
            $item->default_value = '';
            if (isset($extrafield_data[$item->id])) {
                $item->order = $item->group * ($extrafield_data[$item->id]['order']);
                $item->type = $extrafield_data[$item->id]['type'];
                $item->ranges = $extrafield_data[$item->id]['ranges'];
                if (array_key_exists("field_{$item->id}", $ja_k2filter)) {
                    $item->default_value = $ja_k2filter["field_{$item->id}"];
                } elseif (array_key_exists("field_{$item->id}[]", $ja_k2filter)) {
                    $item->default_value = $ja_k2filter["field_{$item->id}[]"];
                }
            }
            $object = new stdClass();
            $object->id = "field_{$item->id}";
            $object->group = $item->group;
            $object->label_html = $item->name;
            $object->input_name = "field_{$item->id}";
            $object->order = $item->order;
            $object->type = $item->type;
            $object->default_value = $item->default_value;
            switch ($item->type) {
                case 'text':
                    $object->options = '';
                    break;
                case 'jarange':
                    if ($item->ranges == '') {
                        //$object->group =$item->group;
                        $object->type = 'ja_range';
                        $object->options = array();
                        $values = explode('-', $item->default_value);
                        sort($values);
                        $object1 = new stdClass();
                        $object1->id = "field_{$item->id}_1";
                        $object1->label_html = $item->name . " 1";
                        $object1->input_name = "field_{$item->id}[]";
                        $object1->type = 'text';
                        $object1->options = '';
                        $object1->default_value = @$values[0];
                        $object->options[] = $object1;
                        $object2 = new stdClass();
                        $object2->id = "field_{$item->id}_2";
                        $object2->label_html = $item->name . " 2";
                        $object2->input_name = "field_{$item->id}[]";
                        $object2->type = 'text';
                        $object2->options = '';
                        $object2->default_value = @$values[1];
                        $object->options[] = $object2;
                    } else {
                        $objects = array();
                        $options = explode(';', $item->ranges);
                        $options = array_values(array_unique($options));
                        $n = count($options);
                        $objects[] = JHTML::_('select.option', "", JText::_('JAK2_FILTER_ANY') . " " . $item->name);
                        $objects[] = JHTML::_('select.option', "0-{$options[0]}", "0 - {$options[0]}");
                        for ($i = 0; $i < $n - 1; $i ++) {
                            $objects[] = JHTML::_('select.option', $options[$i] . '-' . $options[$i + 1], $options[$i] . ' - ' . $options[$i + 1]);
                        }
                        $objects[] = JHTML::_('select.option', $options[$n - 1] . '-999999', 'More than ' . $options[$n - 1]);
                        $object->id = 'field_' . $item->id;
                        $object->input_name = "field_{$item->id}";
                        $object->type = 'select';
                        $object->options = $objects;
                    }
                    break;
                case 'select':
                    $object->input_name = "field_{$item->id}";
                    $object->options = $this->parseOptions($item->name, $item->value);
                    break;
                case 'multi':
                    $object->input_name = "field_{$item->id}[]";
                    $object->options = $this->parseOptions($item->name, $item->value, 0);
                    $object->default_value = explode(',', $item->default_value);
                    break;
                case 'radio':
                    $object->input_name = "field_{$item->id}";
                    $object->options = $this->parseOptions($item->name, $item->value, 0);
                    $object->default_value = explode(',', $item->default_value);
                    break;
                default:
                    $object->default_value = explode(',', $item->default_value);
                    break;
            }
            $jaextrafields[$item->group][] = $object;
        }
        foreach ($jaextrafields as $j => $group) {
            $jaextrafields[$j] = JArrayHelper::sortObjects($group, 'order');
        }
        return $jaextrafields;
    }

    /**
     * parseOptions
     *
     * @param string $name      Name
     * @param string $values    Values
     * @param int    $all       All
     *
     * @return object
     */
    public function parseOptions ($name, $values, $all = 1)
    {
        include_once JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_k2' . DS . 'lib' . DS . 'JSON.php';
        $json = new Services_JSON();
        $jsonObjects = $json->decode($values);
        if (count($jsonObjects) < 1)
            return null;
            // convert objects to array
        $objects = array();
        if ($all == 1) {
            $objects[] = JHTML::_('select.option', "", JText::_('JAK2_FILTER_ANY') . " " . $name);
        }
        foreach ($jsonObjects as $object) {
            if (isset($object->name)) {
                if (isset($object->value)) {
                    $objects[] = JHTML::_('select.option', $object->value, $object->name);
                } else {
                    $objects[] = JHTML::_('select.option', $object->name, $object->name);
                }
            } elseif (isset($object->id)) {
                $objects[] = JHTML::_('select.option', $object->id, $object->value);
            } else
                return;
        }
        return $objects;
    }

    /**
     * jak2CatsForm
     *
     * @param string $groupname Group name
     *
     * @return string
     */
    public function jak2CatsForm ($params, $groupname = 'plg_ja_group_')
    {
        $extrafield_data = $params->get('extrafield_data', "");
        $extrafield_data = str_replace(array("\r" , "\r\n" , "\n" , "\\"), '', $extrafield_data);
        $extrafield_data = json_decode($extrafield_data, true);
        $fids = is_array($extrafield_data) ? array_keys($extrafield_data) : array();
        if (count($fids) == 0)
            return false;
        $db = & JFactory::getDBO();
        $query = "SELECT DISTINCT t.group
            FROM #__k2_extra_fields t
            WHERE t.published =1  AND t.id IN (" . implode(',', $fids) . ")
            ORDER BY t.group
        ";
        $db->setQuery($query);
        $groups = $db->loadResultArray();
        if (! $groups)
            return false;
        $value = $this->group_cat;
        if (JRequest::getVar('option', '') == 'com_k2') {
            if (JRequest::getVar('view', '') == 'itemlist') {
                if (JRequest::getVar('layout', '') == 'category') {
                    $catid = JRequest::getVar('id', 0);
                    if ($catid) {
                        $db = &JFactory::getDBO();
                        $query = "SELECT id,extraFieldsGroup FROM #__k2_categories WHERE id = '{$catid}' AND published=1";
                        $db->setQuery($query);
                        $cat = $db->loadObject();
                        if ($cat)
                            $value = "{$cat->extraFieldsGroup}_{$cat->id}";
                    }
                }
            }
        }
        $categories = $this->_fetchElement(0, '', array(), $groups);
        if (! $categories)
            return false;
        if (count($groups) == 1) {
            //$mitems[]     = JHTML::_('select.option',  $groups[0].'_0', JText::_( 'All Categories' ) );
        } else {
            if (($value == '0_0') && ! isset($this->current_search_ob->catid)) {
                $items = array_slice($categories, 0, 1);
                $value = $items[0]->extraFieldsGroup . '_' . $items[0]->id;
            }
            $mitems[] = JHTML::_('select.option', '0_0', JText::_('JAK2_FILTER_ALL_CATEGORIES'));
        }
        foreach ($categories as $item) {
            $mitems[] = JHTML::_('select.option', $item->extraFieldsGroup . '_' . $item->id, '&nbsp;&nbsp;' . $item->treename);
        }
        $onchange = "load_extrafields(this.value,\"{$groupname}\")";
        $out = JHTML::_(
                'select.genericlist', $mitems, $groupname . 'catid',
                'class="inputbox" style="width:95%;"  size="1" onchange= ' . $onchange,
                'value', 'text', $value
        );
        return $out;
    }

    public function genExtraFieldGroups($params, $groupname = 'plg_ja_group_')
    {
        $extrafield_data = $params->get('extrafield_data', "");
        $extrafield_data = str_replace(array("\r" , "\r\n" , "\n" , "\\"), '', $extrafield_data);
        $extrafield_data = json_decode($extrafield_data, true);
        $fids = is_array($extrafield_data) ? array_keys($extrafield_data) : array();
        if (count($fids) == 0)
            return false;
        $db = & JFactory::getDBO();
        $query = "SELECT DISTINCT g.id, g.name
            FROM #__k2_extra_fields_groups g
                INNER JOIN #__k2_extra_fields t ON g.id = t.group and t.published = 1
            WHERE t.id IN (" . implode(',', $fids) . ")";
        $db->setQuery($query);
        $groups = $db->loadObjectList();

        $items = array();
        if (count($groups) > 1) {
            $items[] = JHTML::_('select.option', '0', JText::_('JAK2_FILTER_ALL_EXTRA_FIELD_GROUPS'));
        }
        foreach($groups as $group) {
            $items[] = JHTML::_('select.option', $group->id, $group->name);
        }

        $onchange = "load_categories(this.value,\"{$groupname}\")";
        $html = JHTML::_('select.genericlist', $items, $groupname . 'efgroups', 'class="inputbox" onchange='.$onchange, 'value', 'text');
        return $html;
    }

    /**
     * _fetchChild
     *
     * @param int    $parent Parent ID
     * @param string $groups Groups
     *
     * @return mixed
     */
    public function _fetchChild($parent, $groups = null)
    {
        $db = &JFactory::getDBO();
        $query = "SELECT * FROM #__k2_categories WHERE parent = '{$parent}' AND published=1";
        if ($groups && ! empty($groups)) {
            JArrayHelper::toInteger($groups);
            ;
            $groups = implode(",", $groups);
            $query .= " AND extraFieldsGroup IN ({$groups}) ";
        }
        $db->setQuery($query);
        $cats = $db->loadObjectList();
        return $cats;
    }

    /**
     * _fetchElement
     *
     * @param int    $id        ID
     * @param string $indent    Indent
     * @param array  $list      List
     * @param string $groups    Groups
     * @param int    $maxlevel  Max level
     * @param int    $level     Level
     * @param int    $type      Type
     *
     * @return array
     */
    public function _fetchElement($id, $indent, $list, $groups, $maxlevel = 9999, $level = 0, $type = 1)
    {
        $children = $this->_fetchChild($id, $groups);
        if (@$children && $level <= $maxlevel) {
            foreach ($children as $v) {
                $id = $v->id;
                if ($type) {
                    $pre = '&nbsp;&nbsp;|_&nbsp;&nbsp;';
                    $spacer = '.&nbsp;&nbsp;&nbsp;';
                } else {
                    $pre = '- ';
                    $spacer = '&nbsp;&nbsp;';
                }
                if ($v->parent == 0) {
                    $txt = $v->name;
                } else {
                    $txt = $pre . $v->name;
                }
                $pt = $v->parent;
                $list[$id] = $v;
                $list[$id]->treename = "{$indent}{$txt}";
                $list[$id]->children = count(@$children);
                $list = $this->_fetchElement($id, $indent . $spacer, $list, $groups, $maxlevel, $level + 1, $type);
            }
        }
        return $list;
    }

    /**
     * Customize prepareSearch function from components/com_k2/models/itemlist.php
     *
     * @param string $search    Search word
     * @param string $type      Search type
     *
     * @return string
     */
    public function prepareSearch ($search, $type)
    {
        jimport('joomla.filesystem.file');
        $db = &JFactory::getDBO();
        $language = &JFactory::getLanguage();
        $defaultLang = $language->getDefault();
        $currentLang = $language->getTag();
        $length = JString::strlen($search);
        $params = $this->getParams();
        $searchLanguage = $params->get('search_language', 1);
        if (JFolder::exists(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_joomfish')
            && $currentLang != $defaultLang
            && $searchLanguage
        ) {
            $conditions = array();
            $search_ignore = array();
            $sql = '';
            $ignoreFile = $language->getLanguagePath() . DS . $currentLang . DS . $currentLang . '.ignore.php';
            if (JFile::exists($ignoreFile)) {
                include $ignoreFile;
            }
            if ($type == 'exact') {
                $word = $search;
                if (JString::strlen($word) > 2 && ! in_array($word, $search_ignore)) {
                    $word = $db->Quote('%' . $db->getEscaped($word, true) . '%', false);
                    $jfQuery = " SELECT reference_id FROM #__jf_content as jfc LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id";
                    $jfQuery .= " WHERE jfc.reference_table = 'k2_items'";
                    $jfQuery .= " AND jfl.code=" . $db->Quote($currentLang);
                    $jfQuery .= " AND jfc.published=1";
                    $jfQuery .= " AND jfc.value LIKE " . $word;
                    $jfQuery .= " AND (jfc.reference_field = 'title'
                                OR jfc.reference_field = 'introtext'
                                OR jfc.reference_field = 'fulltext'
                                OR jfc.reference_field = 'image_caption'
                                OR jfc.reference_field = 'image_credits'
                                OR jfc.reference_field = 'video_caption'
                                OR jfc.reference_field = 'video_credits'
                                OR jfc.reference_field = 'extra_fields_search'
                                OR jfc.reference_field = 'metadesc'
                                OR jfc.reference_field = 'metakey'
                    )";
                    $db->setQuery($jfQuery);
                    $result = $db->loadResultArray();
                    $result = @array_unique($result);
                    JArrayHelper::toInteger($result);
                    if (count($result)) {
                        $conditions[] = "i.id IN(" . implode(',', $result) . ")";
                    }
                }
            } else {
                $search = explode(' ', JString::strtolower($search));
                $words = array();
                foreach ($search as $searchword) {
                    if (JString::strlen($searchword) > 2 && ! in_array($searchword, $search_ignore)) {
                        $word = $db->Quote('%' . $db->getEscaped($searchword, true) . '%', false);
                        $words[] = "jfc.value LIKE " . $word;
                    }
                }
                if (count($words)) {
                    $searchQuery = ($type == 'all') ? implode(' AND ', $words) : implode(' OR ', $words);
                    $jfQuery = " SELECT reference_id FROM #__jf_content as jfc LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id";
                    $jfQuery .= " WHERE jfc.reference_table = 'k2_items'";
                    $jfQuery .= " AND jfl.code=" . $db->Quote($currentLang);
                    $jfQuery .= " AND jfc.published=1";
                    $jfQuery .= " AND (" . $searchQuery . ")";
                    $jfQuery .= " AND (jfc.reference_field = 'title'
                                 OR jfc.reference_field = 'introtext'
                                 OR jfc.reference_field = 'fulltext'
                                 OR jfc.reference_field = 'image_caption'
                                 OR jfc.reference_field = 'image_credits'
                                 OR jfc.reference_field = 'video_caption'
                                 OR jfc.reference_field = 'video_credits'
                                 OR jfc.reference_field = 'extra_fields_search'
                                 OR jfc.reference_field = 'metadesc'
                                 OR jfc.reference_field = 'metakey'
                    )";
                    $db->setQuery($jfQuery);
                    $result = $db->loadResultArray();
                    $result = @array_unique($result);
                    foreach ($result as $id) {
                        $allIDs[] = $id;
                    }
                }
                if (isset($allIDs) && count($allIDs)) {
                    JArrayHelper::toInteger($allIDs);
                    $conditions[] = "i.id IN(" . implode(',', $allIDs) . ")";
                }
            }
            if (count($conditions)) {
                $sql = " (" . implode(" OR ", $conditions) . ")";
            }
        } else {
            $sql = " MATCH(i.title, i.introtext, i.`fulltext`,i.image_caption,i.image_credits,i.video_caption,i.video_credits,i.extra_fields_search,i.metadesc,i.metakey) ";
            if ($type == 'exact') {
                $text = JString::trim($search, '"');
                $text = $db->Quote('"' . $db->getEscaped($text, true) . '"', false);
            } else {
                $search = JString::str_ireplace('*', '', $search);
                $words = explode(' ', $search);
                for ($i = 0; $i < count($words); $i ++) {
                    $words[$i] .= '*';
                }
                $search = implode(' ', $words);
                $text = $db->Quote($db->getEscaped($search, true), false);
            }
            $sql .= "AGAINST ({$text} IN BOOLEAN MODE)";
        }
        return $sql;
    }
    
    /**
     * Logging
     *
     * @param string $msg        Message data
     * @param bool   $traceback  Indicate traceback or not
     *
     * @return void
     */
    public function log($msg, $traceback = false)
    {
        $app = & JFactory::getApplication();
        $log_path = $app->getCfg('log_path');
        if (! is_dir($log_path)) $log_path = JPATH_ROOT . DS . 'logs';
        if (! is_dir($log_path)) @JFolder::create($log_path);
        if (! is_dir($log_path)) return false; //cannot create log folder
        //prevent http access to this location
        $htaccess = $log_path . DS . '.htaccess';
        if (! is_file($htaccess)) {
            $htdata = "Order deny,allow\nDeny from all\n";
            @JFile::write($htaccess, $htdata);
        }
        // Build log message
        $data = date('H:i:s') . "\n" . $msg;
        if ($traceback) {
            $data .= "\n---------\n";
            $cdata = ob_get_contents(); //store old data
            ob_start();
            debug_print_backtrace();
            $data .= ob_get_contents();
            ob_end_clean();
            echo $cdata; //write the old data
            $data .= "\n---------";
        }
        $data .= "\n\n";
        $log_file = $log_path . DS . 'jak2filter.log';
        if (! ($f = fopen($log_file, 'a'))) return false;
        fwrite($f, $data);
        fclose($f);
        return true;
    }
}