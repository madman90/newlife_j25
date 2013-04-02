<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * HelloWorld Model
 */
class K2AjaxSearchModelK2AjaxSearch extends JModelItem
{
    public function getExtraFields()
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
                $res['series'] = array();
                foreach ($row->value as $option)
                {
                    $res['series'][$option->value] = $option;
                }
            }
            if ($row->id == 2)
            {
                $res['books'] = array();
                foreach($row->value as $option)
                {
                    $res['books'][$option->value] = $option;
                }
            }
        }
        return $res;
    }

    public function getData()
    {
        $params = JFactory::getApplication()->input->get('query', null, 'ARRAY');
        if ($params)
        {
            if (isset($params['book']) && count($params['book']) == 1 && $params['book'][0] == "")
            {
                unset($params['book']);
            }
        }

        $db = JFactory::getDBO();
        $query = "SELECT DISTINCT i.id, i.* FROM #__k2_items as i ";
        if (isset($params['tag']) && count($params['tag'])>0)
        {
            $query .= " LEFT JOIN #__k2_tags_xref as t ON i.id = t.itemID ";
            $tags = implode(', ', $params['tag']);
        }
        $query .= " WHERE i.catid=1 AND i.published = 1 AND i.trash = 0";
        if (isset($tags))
        {
            $query .= " AND t.tagID IN ({$tags})";
        }
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $res = array();
        foreach($rows as $row)
        {
            $row->extra_fields = json_decode($row->extra_fields);
            $row->params = json_decode($row->params);
            $r = true;
            if (!is_null($params))
            {
                foreach($row->extra_fields as $field)
                {
                    switch($field->id)
                    {
                        case 1:
                            $key = 'seria';
                            break;
                        default:
                        case 2:
                            $key = 'book';
                    }
                    if (isset($params[$key]))
                    {
                        $r = $r && in_array($field->value, $params[$key]);
                    }
                }

            }
            if ($r)
            {
                $res[] = $row;
            }
        }
        return $res;
    }
}