<?php

/**
 * @version    $Id: mod_flying_tweets.php 4 2011-09-16 13:52:55Z  $
 * @package    mod_twitterfriends15
 * @name       mod_flying_tweets - Flying Tweets joomla 1.5
 * @author     xing
 * @copyright  Copyright (C) 2011 autson. All rights reserved.
 * @license    GNU Public License <http://www.gnu.org/licenses/gpl.html>
 * @link       http://www.autson.com
 * @support    http://www.autson.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

require(JModuleHelper::getLayoutPath('mod_flying_tweets'));
?>