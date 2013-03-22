<?php

defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).DS.'helper.php');

$data = modK2AjaxHelper::getSeries();


require(JModuleHelper::getLayoutPath('mod_k2ajax'));