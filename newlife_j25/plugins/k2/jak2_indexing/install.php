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
// No direct access
defined('_JEXEC') or die;

/**
 * Enable, re-order the plugin to last position after install
 */
class plgK2JAK2_IndexingInstallerScript
{
    /**
     * Run postflight function
     *
     * @param string           $type             Type of process (install, update, ...)
     * @param JInstallerPlugin $pluginInstaller  Plugin Installer Object
     */
    public function postflight($type, $pluginInstaller)
    {
        $db = JFactory::getDbo();
        // Create table query
        $query = 'CREATE TABLE IF NOT EXISTS `#__ja_k2extrafields` (
              `id` int(11) NOT NULL auto_increment,
              `itemid` int(11) NOT NULL,
              `exfid` int(11) NOT NULL,
              `value` varchar(255) character set utf8 NOT NULL,
              `name` varchar(255) character set utf8 NOT NULL,
              PRIMARY KEY  (`id`),
              UNIQUE KEY `id` (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';
        $db->setQuery($query);
        try {
            $db->query();
        } catch ( JException $e ) {
            // Return warning message that cannot update order
            echo JText::_('JAK2_FILTER_CREATE_TABLE_FAIL');
        }
    }
    
    /**
     * Remove table when uninstall jak2_indexing
     *
     * @param JInstallerPlugin $pluginInstaller  Plugin Installer Object
     */
    function uninstall($pluginInstaller)
    {
        $db = JFactory::getDbo();
        // Create query
        $query = 'DROP TABLE `#__ja_k2extrafields`';
        $db->setQuery($query);
        try {
            $db->query();
        } catch(JException $e) {
            echo JText::_('JAK2_FILTER_DROP_TABLE_FAIL');
        }
    }
}