<?php
/*
# mod_sp_quickcontact - Ajax based quick contact Module by JoomShaper.com
# -----------------------------------------------------------------------	
# Author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2012 JoomShaper.com. All Rights Reserved.
# License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomshaper.com
*/

define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__).'/../../' );
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
$mainframe 				= JFactory::getApplication('site');

class IraQuickContact{
	function sendEmail(){
		global $mainframe;
		$mail 			= JFactory::getMailer();
		$modId			= JRequest::getVar('modId');	
		$db				= JFactory::getDBO();
		$sql 			= "SELECT params FROM #__modules WHERE id=$modId";
		$db->setQuery($sql);
		$data 			= $db->loadResult();
		$params 		= json_decode($data);
		$success 		= $params->success;
		$failed 		= $params->failed;
		$recipient 		= $params->email;
        $subject 		= $params->subject;
		
		$email 			= JRequest::getVar('email');
		$name 			= JRequest::getVar('name');

		$message 		= JRequest::getVar('message');
		
		$sender = array($email, $name);	
		$mail->setSender($sender);
		$mail->addRecipient($recipient);
		$mail->setSubject($subject);
		$mail->isHTML(true);
		$mail->Encoding = 'base64';	
		$mail->setBody($message);
		 
		if ($mail->Send()) {
		  echo $success;
		} else {
		  echo $failed;
		}
	}
	
}	
$ira_qc = new IraQuickContact();
$ira_qc->sendEmail();