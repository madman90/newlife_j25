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
}