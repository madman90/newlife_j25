<?php

class modK2AjaxHelper
{
    function getSeries()
    {
        $db = JFactory::getDBO();
        $query = "SELECT i.* FROM #__k2_extra_fields as i WHERE i.group=1 AND i.published = 1";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $res = array();
        foreach($rows as $row)
        {
            $row->value = json_decode($row->value);
            if ($row->id == 1)
            {
                $res['series'] = $row->value;
            }
            if ($row->id == 2)
            {
                $res['books'] = $row->value;
            }
        }
        return $res;
    }

    public static function tagCloud($params)
    {
        $mainframe = JFactory::getApplication();
        $user = JFactory::getUser();
        $aid = (int)$user->get('aid');
        $db = JFactory::getDBO();

        $jnow = JFactory::getDate();
        $now = K2_JVERSION == '15' ? $jnow->toMySQL() : $jnow->toSql();

        $nullDate = $db->getNullDate();

        $query = "SELECT i.id FROM #__k2_items as i";
        $query .= " LEFT JOIN #__k2_categories c ON c.id = i.catid";
        $query .= " WHERE i.published=1 ";
        $query .= " AND ( i.publish_up = ".$db->Quote($nullDate)." OR i.publish_up <= ".$db->Quote($now)." ) ";
        $query .= " AND ( i.publish_down = ".$db->Quote($nullDate)." OR i.publish_down >= ".$db->Quote($now)." )";
        $query .= " AND i.trash=0 ";
        if (K2_JVERSION != '15')
        {
            $query .= " AND i.access IN(".implode(',', $user->getAuthorisedViewLevels()).") ";
        }
        else
        {
            $query .= " AND i.access <= {$aid} ";
        }
        $query .= " AND c.published=1 ";
        $query .= " AND c.trash=0 ";
        if (K2_JVERSION != '15')
        {
            $query .= " AND c.access IN(".implode(',', $user->getAuthorisedViewLevels()).") ";
        }
        else
        {
            $query .= " AND c.access <= {$aid} ";
        }

        $cloudCategory = $params['cloud_category'];
        if (is_array($cloudCategory))
        {
            $cloudCategory = array_filter($cloudCategory);
        }
        if ($cloudCategory)
        {
            if (!is_array($cloudCategory))
            {
                $cloudCategory = (array)$cloudCategory;
            }
            foreach ($cloudCategory as $cloudCategoryID)
            {
                $categories[] = $cloudCategoryID;
                if ($params['cloud_category_recursive'])
                {
                    $children = modK2ToolsHelper::getCategoryChildren($cloudCategoryID);
                    $categories = @array_merge($categories, $children);
                }
            }
            $categories = @array_unique($categories);
            JArrayHelper::toInteger($categories);
            if (count($categories) == 1)
            {
                $query .= " AND i.catid={$categories[0]}";
            }
            else
            {
                $query .= " AND i.catid IN(".implode(',', $categories).")";
            }
        }

        if (K2_JVERSION != '15')
        {
            if ($mainframe->getLanguageFilter())
            {
                $languageTag = JFactory::getLanguage()->getTag();
                $query .= " AND c.language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") AND i.language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") ";
            }
        }

        $db->setQuery($query);
        $IDs = K2_JVERSION == '30' ? $db->loadColumn() : $db->loadResultArray();

        $query = "SELECT tag.name, tag.id
        FROM #__k2_tags as tag
        LEFT JOIN #__k2_tags_xref AS xref ON xref.tagID = tag.id
        WHERE xref.itemID IN (".implode(',', $IDs).")
        AND tag.published = 1";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $cloud = array();
        if (count($rows))
        {
            $ids_arr = array();
            foreach ($rows as $tag)
            {
                $ids_arr[$tag->name] = $tag->id;
                if (@array_key_exists($tag->name, $cloud))
                {
                    $cloud[$tag->name]++;
                }
                else
                {
                    $cloud[$tag->name] = 1;
                }
            }

            $max_size = $params['max_size'];
            $min_size = $params['min_size'];
            $max_qty = max(array_values($cloud));
            $min_qty = min(array_values($cloud));
            $spread = $max_qty - $min_qty;
            if (0 == $spread)
            {
                $spread = 1;
            }

            $step = ($max_size - $min_size) / ($spread);

            $counter = 0;
            arsort($cloud, SORT_NUMERIC);
            $cloud = @array_slice($cloud, 0, $params['cloud_limit'], true);
            uksort($cloud, "strnatcasecmp");

            foreach ($cloud as $key => $value)
            {
                $size = $min_size + (($value - $min_qty) * $step);
                $size = ceil($size);
                $tmp = new stdClass;
                $tmp->id = $ids_arr[$key];
                $tmp->tag = $key;
                $tmp->count = $value;
                $tmp->size = $size;
                $tmp->link = urldecode(JRoute::_(K2HelperRoute::getTagRoute($key)));
                $tags[$counter] = $tmp;
                $counter++;
            }

            return $tags;
        }
    }
}