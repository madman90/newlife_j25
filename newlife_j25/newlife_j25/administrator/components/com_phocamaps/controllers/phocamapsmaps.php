<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
 
defined('_JEXEC') or die;
jimport('joomla.application.component.controlleradmin');

class PhocaMapsCpControllerPhocaMapsMaps extends JControllerAdmin
{
	protected	$option 		= 'com_phocamaps';
	
	
	
	public function &getModel($name = 'PhocaMapsMap', $prefix = 'PhocaMapsCpModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}