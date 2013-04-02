<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * Hello World Component Controller
 */
class K2AjaxSearchController extends JController
{
    public function execute($task)
    {
        if ($task == 'change_view')
        {
            $sesion = JFactory::getSession();
            if ($sesion->get('view_mode','short') == 'short')
            {
                $sesion->set('view_mode', 'full');
            }
            else
            {
                $sesion->set('view_mode', 'short');
            }
            $url = htmlspecialchars($_SERVER['HTTP_REFERER']);
            $this->setRedirect($url);
        }
        else
        {
            return parent::execute($task);
        }
    }

}
