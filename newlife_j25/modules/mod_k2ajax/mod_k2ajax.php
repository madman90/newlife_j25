<?php

defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).DS.'helper.php');

$data = modK2AjaxHelper::getSeries();

$tags = modK2AjaxHelper::tagCloud(array(
    'min_size' => 100,
    'max_size' => 300,
    'cloud_limit' => 30,
    'cloud_category' => 1,
    'cloud_category_recursive' => 0
));

require(JModuleHelper::getLayoutPath('mod_k2ajax'));