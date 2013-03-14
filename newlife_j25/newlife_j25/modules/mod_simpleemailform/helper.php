<?php
/*
        mod_simpleEmailForm.php
        
        Copyright 2010 - 2012 D. Bierer <doug@unlikelysource.com>
		Version	1.7.09

        This program is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published by
        the Free Software Foundation; either version 2 of the License, or
        (at your option) any later version.
        
        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.
        
        You should have received a copy of the GNU General Public License
        along with this program; if not, write to the Free Software
        Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
        MA 02110-1301, USA.

		2012-08-20 DB:
		* Removed email_test.php: poses too much of a security risk!
		* (Thanks to Ties Kemper for pointing this out)
*/

class _SimpleEmailForm {
	public $to 			= NULL;
	public $from 		= NULL;
	public $fromName	= NULL;
	public $cc 			= NULL;
	public $bcc 		= NULL;
	public $replyTo 	= NULL;
	public $replyToActive = FALSE;
	public $attachment 	= '';
	public $subject 	= '';
	public $body 		= '';
	public $dir 		= '';
	public $copyMe 		= '';
	public $copyMeAuto 	= '';
	public $error 		= '';
}

class modSimpleEmailForm {
	
	// Initialize vars
	protected $_msg			= '';
	protected $_output 		= '';
	protected $_maxFields	= 6;
	protected $_field		= array();
	protected $_txtError	= '';
	protected $_badEmail	= '';
	protected $_fileMsg		= '';
	protected $_lang		= 'en-GB';
	protected $_transLang	= array();
	protected $_testMode	= 'N';
	protected $_params		= array();
	
	// 2011-12-03 DB: added CSS styling for elements
	// Used if CSS Class param is set
	protected $_tableClass		= '';
	protected $_trClass			= '';
	protected $_thClass			= '';
	protected $_spaceClass		= '';
	protected $_tdClass			= '';
	protected $_inputClass		= '';
	protected $_captchaClass	= '';

	// Init XML params
	protected $_cssClass 		= '';
	protected $_labelAlign		= '';
	protected $_fromsize 		= 0;
	protected $_txtRows 		= 0;
	protected $_txtCols 		= 0;
	protected $_txtLabel 		= '';
	protected $_txtActive 		= '';
	protected $_subjectsize 	= 0;
	protected $_subjectlabel 	= '';
	protected $_subjectline		= '';
	protected $_fromlabel 		= '';
	protected $_copymeLabel 	= '';
	protected $_copymeActive 	= 0;
	protected $_copymeAuto 		= 0;
	protected $_errorTxtColor	= '';
	protected $_successTxtColor	= '';
	protected $_anchor			= '';
	protected $_autoReset		= '';
	protected $_redirectURL		= '';
	protected $_col2space		= 0;
	protected $_uploadActive 	= '';
	protected $_uploadAllowed 	= '';
	protected $_emailCheck		= '';
	protected $_instance		= 0;
	
	// Init CAPTCHA params
	protected $_useCaptcha 			= 0;
	protected $_captchaDir 			= '';
	protected $_captchaURL 			= '';
	protected $_captchaLen 			= 0;
	protected $_captchaSize			= 0;
	protected $_captchaWidth		= 0;
	protected $_captchaHeight		= 0;
	protected $_captchaTextColor	= '';
	protected $_captchaLinesColor	= '';
	protected $_captchaBgColor		= '';
	
	public function __construct($params) 
	{
		
		// Get XML params
		$this->_cssClass 		= $params->get('mod_simpleemailform_cssClass');
		$this->_labelAlign		= $params->get('mod_simpleemailform_labelAlign');
		$this->_fromsize 		= $params->get('mod_simpleemailform_fromsize');
		$this->_txtRows 		= $params->get('mod_simpleemailform_textareaRows');
		$this->_txtCols 		= $params->get('mod_simpleemailform_textareaCols');
		$this->_txtLabel 		= $params->get('mod_simpleemailform_textareaLabel');
		$this->_txtActive 		= $params->get('mod_simpleemailform_textareaActive');
		$this->_subjectsize 	= $params->get('mod_simpleemailform_subjectsize');
		$this->_subjectlabel 	= $params->get('mod_simpleemailform_subjectlabel');
		$this->_subjectline		= $params->get('mod_simpleemailform_subjectline');
		$this->_fromlabel 		= $params->get('mod_simpleemailform_fromlabel');
		$this->_copymeLabel 	= $params->get('mod_simpleemailform_copymeLabel');
		$this->_copymeActive 	= $params->get('mod_simpleemailform_copymeActive');
		$this->_copymeAuto 		= $params->get('mod_simpleemailform_copymeAuto');
		$this->_errorTxtColor	= $params->get('mod_simpleemailform_errorTxtColor');
		$this->_successTxtColor = $params->get('mod_simpleemailform_successTxtColor');
		$this->_anchor 			= $params->get('mod_simpleemailform_anchor');
		$this->_autoReset 		= $params->get('mod_simpleemailform_autoreset');
		$this->_redirectURL		= $params->get('mod_simpleemailform_redirectURL');
		$this->_col2space		= $params->get('mod_simpleemailform_col2space');
		$this->_uploadActive 	= $params->get('mod_simpleemailform_uploadActive');
		$this->_uploadAllowed 	= $params->get('mod_simpleemailform_uploadAllowed');
		$this->_instance		= $params->get('mod_simpleemailform_instance');
		$this->_emailCheck		= $params->get('mod_simpleemailform_emailCheck');
		// test mode
		$this->_testMode 		= $params->get('mod_simpleemailform_testMode');
		if ($this->_testMode == 'Y') {
			$this->_params = $params;
		}
		// error checking for all incoming params
		$this->_fromsize		= (int) $this->_fromsize;
		$this->_txtRows			= (int) $this->_txtRows;
		$this->_txtCols			= (int) $this->_txtCols;
		$this->_autoReset 		= ($this->_autoReset != 'Y') ? 'N' : 'Y'; 
		$this->_col2space		= (int) $this->_col2space;
		$this->_uploadAllowed 	= strtolower(trim($this->_uploadAllowed));
		$this->_cssClass		= strip_tags(trim($this->_cssClass));
		$this->_emailCheck		= ($this->_emailCheck == 'Y') ? TRUE : FALSE;
		$this->_instance		= trim($this->_instance);	
		// 2011-12-03 DB: init CSS class properties (if set)
		if ($this->_cssClass) {
			$this->_tableClass		= " class='" . $this->_cssClass . "_table'";
			$this->_trClass			= " class='" . $this->_cssClass . "_tr'";
			$this->_thClass			= " class='" . $this->_cssClass . "_th'";
			$this->_spaceClass		= " class='" . $this->_cssClass . "_space'";
			$this->_tdClass			= " class='" . $this->_cssClass . "_td'";
			$this->_inputClass		= " class='" . $this->_cssClass . "_input'";
			$this->_captchaClass	= " class='" . $this->_cssClass . "_captcha'";
		}
		// Assign field params into array 
		for ($x = 1; $x < $this->_maxFields; $x++) {
			$sizeLabel 	 = 'mod_simpleemailform_field' . $x . 'size';
			$labelLabel  = 'mod_simpleemailform_field' . $x . 'label';
			$activeLabel = 'mod_simpleemailform_field' . $x . 'active';
			$maxxlabel	 = 'mod_simpleemailform_field' . $x . 'maxx';
			// 2010-12-12 DB: added check to see if any values + set defaults
			$s = $params->get($sizeLabel);
			$l = $params->get($labelLabel);
			$a = $params->get($activeLabel);
			$m = $params->get($maxxlabel);
			if (isset($a)) {
				$a = trim(strtoupper($a));
				if (strpos('-RYN', $a)) {
					$this->_field[$x]['active'] =  $a;
				} else {
					$this->_field[$x]['active'] =  'N';
				}
			} else {
				$this->_field[$x]['active'] =  'N';
			}
			$this->_field[$x]['size'] 	= (isset($s)) ? (int) $s : 40;
			$this->_field[$x]['label'] 	= (isset($l)) ? trim($l) : $x . ':';
			$this->_field[$x]['maxx']	= (isset($m)) ? (int) $m : 255;
			$this->_field[$x]['error'] 	= '';
		}

		// Captcha
		$this->_useCaptcha 			= $params->get('mod_simpleemailform_useCaptcha');
		$this->_captchaDir 			= $params->get('mod_simpleemailform_captchaDir');
		$this->_captchaURL 			= $params->get('mod_simpleemailform_captchaURL');
		$this->_captchaLen 			= $params->get('mod_simpleemailform_captchaLen');
		$this->_captchaSize			= $params->get('mod_simpleemailform_captchaSize');
		$this->_captchaWidth		= $params->get('mod_simpleemailform_captchaWidth');
		$this->_captchaHeight		= $params->get('mod_simpleemailform_captchaHeight');
		$this->_captchaTextColor	= $params->get('mod_simpleemailform_captchaTxtColor');
		$this->_captchaLinesColor	= $params->get('mod_simpleemailform_captchaLinesColor');
		$this->_captchaBgColor		= $params->get('mod_simpleemailform_captchaBgColor');

		// Load language files
		// i.e. tr-TR.mod_simpleemailform.ini
		$this->_lang = $params->get('mod_simpleemailform_defaultLang');
		$langFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'language_files' 
				  . DIRECTORY_SEPARATOR . $this->_lang . '.mod_simpleemailform.ini';
		if (file_exists($langFile)) {
			$this->_transLang = parse_ini_file($langFile);
		} else {
			$langFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'language_files' 
					  . DIRECTORY_SEPARATOR . 'en-GB.mod_simpleemailform.ini';
			$this->_transLang = parse_ini_file($langFile);
		}

		// Set email object params
		$this->_msg 		 	= new _SimpleEmailForm();
		$this->_msg->dir 	 	= $params->get('mod_simpleemailform_emailFile');
		$this->_msg->subject 	= $params->get('mod_simpleemailform_subjectline'); 
		$this->_msg->copyMe	 	=  0;	// NOTE: depends on what user selects
		$this->_msg->fromName 	= $params->get('mod_simpleemailform_fromName');
		$this->_msg->copyMeAuto = ($this->_copymeAuto == 'Y') ? 1 : 0;
		// TODO: check for multiple targets, and, if so, convert to array()
		$to  = trim($params->get('mod_simpleemailform_emailTo'));
		$cc  = trim($params->get('mod_simpleemailform_emailCC'));
		$bcc = trim($params->get('mod_simpleemailform_emailBCC'));
		$this->_msg->to  = (preg_match('/[\s,]+/', $to))  ? preg_split('/[\s,]+/', $to)  : array($to);
		if ($cc)  { $this->_msg->cc  = (preg_match('/[\s,]+/', $cc))  ? preg_split('/[\s,]+/', $cc)  : array($cc); }
		if ($bcc) { $this->_msg->bcc = (preg_match('/[\s,]+/', $bcc)) ? preg_split('/[\s,]+/', $bcc) : array($bcc); }
		// 2012-2-7 DB: add optional Reply-To field
		$this->_msg->replyToActive 	= $params->get('mod_simpleemailform_replytoActive');
		if ($this->_msg->replyToActive == 'Y') {
			$this->_msg->replyTo 	= array($params->get('mod_simpleemailform_emailReplyTo'), '');
		} else {
			$this->_msg->replyTo 	= '';
		}

	}

	/**
	 * Assumes $this->_transLang[] has been defined
	 */
	public function uploadAttachment($dir, $uploadAllowed, $errorTxtColor, $successTxtColor, &$message)
	{
		$message = '';
		$result  = '';
		$allowed = FALSE;
		// Capture filename
		$fn = (isset($_FILES['mod_simpleemailform_upload_' . $this->_instance]['name'])) 
			   ? basename(strip_tags($_FILES['mod_simpleemailform_upload_' . $this->_instance]['name'])) : '';
		// use regex to check for allowed filenames
		if ($fn) {
			// Get filename extension
			$pos = strrpos($fn, '.');		// last occurrence of '.'
			$ext = strtolower(substr($fn, $pos + 1));
			if ($uploadAllowed) {
				if (strpos($uploadAllowed, $ext)) {
					$allowed = TRUE;
				} else {
					$allowed = FALSE;
				}
			} else {
				$allowed = TRUE;
			}
			if ($allowed) {
				// Check to see if upload parameter specified
				if ( $_FILES['mod_simpleemailform_upload_' . $this->_instance]['error'] == UPLOAD_ERR_OK ) {
					// Check to make sure file uploaded by upload process
					if ( is_uploaded_file ($_FILES['mod_simpleemailform_upload_' . $this->_instance]['tmp_name'] ) ) {
						// Set filename to current directory
						$copyfile = $dir . DIRECTORY_SEPARATOR . $fn;
						// Copy file
						if ( move_uploaded_file ($_FILES['mod_simpleemailform_upload_' . $this->_instance]['tmp_name'], $copyfile) ) {
							// Save name of file
							$message .= $this->formatErrorMessage($successTxtColor, $this->_transLang['MOD_SIMPLEEMAILFORM_upload_success'], $fn);
							$result = $fn;
						} else {
							// Trap upload file handle errors
							$message .= $this->formatErrorMessage($errorTxtColor, $this->_transLang['MOD_SIMPLEEMAILFORM_upload_unable'], $fn);
						}
					} else {
						// Failed security check
						$message .= $this->formatErrorMessage($errorTxtColor, $this->_transLang['MOD_SIMPLEEMAILFORM_upload_failure'], $fn);
					}
				} else {
					// Failed security check
					$message .= $this->formatErrorMessage($errorTxtColor, $this->_transLang['MOD_SIMPLEEMAILFORM_upload_error'], $fn);
				}
			} else {
				// Failed regex
				$message .= $this->formatErrorMessage($errorTxtColor, $this->_transLang['MOD_SIMPLEEMAILFORM_disallowed_filename'], $fn);
			}
		}
		return $result;
	}

	// uses $this->_labelAlign, $this->_col2space, $this->_errorTxtColor
	public function formatRow ($name, $size, $label, $error = NULL, $maxx = 255) 
	{
		if (isset($_POST[$name])) {
			$value =  htmlspecialchars($_POST[$name]);
			// replace '@' to prevent Joomla code from interpolating email address into javascript
			if (strpos($value, '@')) {
				$value = str_replace('@', '&#64;', $value);
			}
		} else {
			$value = '';
		}
		// 2011-12-03 DB: added CSS classes for input, table, row, th and td
		$row = '';
		$row .= '<tr' . $this->_trClass   . '>';
		// labels
		$row .= "<th" . $this->_thClass    . " align='" . $this->_labelAlign . "'>" . $label . "</th>";
		// space between cols
		$row .= "<td" . $this->_spaceClass . " width='" . $this->_col2space  . "'>&nbsp;</td>";
		// input field
		$row .= "<td" . $this->_tdClass    . ">";
		$row .= sprintf('<input type="text" name="%s" id="%s" size="%d" value="%s" maxlength="%d" %s/>',
						$name,
						$name,
						$size,
						$value,
						$maxx,
						$this->_inputClass);
		$row .= ($error) ? $this->formatErrorMessage($this->_errorTxtColor, $error) : '';
		$row .= "</td>";
		$row .= "</tr>\n";
		return $row;
	}

	public function sendResults(_SimpleEmailForm &$msg, $field, $txtLabel, $fromlabel)
	{
		// 2012-02-15 db: override unwanted error messages originating from JMail
		ob_start();
		// Build Body
		$msg->subject = (isset($_POST['mod_simpleemailform_subject_' . $this->_instance])) 
						 ? htmlspecialchars($_POST['mod_simpleemailform_subject_' . $this->_instance]) : $msg->subject;
		$msg->body =  $fromlabel . ': ' . htmlspecialchars($msg->from);
		for ($x = 1; $x <= 5; $x++) {
			$label = 'mod_simpleemailform_field' . $x . '_' . $this->_instance;
			$msg->body .= (isset($_POST[$label])) ? "\n" . $field[$x]['label'] . ': ' . htmlspecialchars($_POST[$label]) : '';
		}
		$msg->body .= (isset($_POST['mod_simpleemailform_textarea_' . $this->_instance]))  
					   ? "\n" . $txtLabel          . ":\n" . htmlspecialchars($_POST['mod_simpleemailform_textarea_' . $this->_instance]) : '';
		// Strip slashes
		$msg->body = stripslashes($msg->body);	
		// Filter for \n in subject - 2010-05-03 DB
		$msg->subject = str_replace("\n",'',$msg->subject);
		// Send mail
		$message = JFactory::getMailer();
		//echo $message->dumpLanguage(); exit;
		$message->addRecipient($msg->to);
		$message->setSender($msg->from);
		$message->setSubject($msg->subject);
		$message->setBody($msg->body);
		// 2012-02-03 DB: added reply to field (has to be array())
		if ($msg->cc) 				{ $message->addCC($msg->cc); }
		if ($msg->bcc) 				{ $message->addBCC($msg->bcc); }
		if ($msg->replyTo) 			{ $message->addReplyTo($msg->replyTo); }
		if ($msg->attachment) { 
			// Formulate FN for attachment
			$msg->attachment = $msg->dir . DIRECTORY_SEPARATOR . $msg->attachment;
			$message->addAttachment($msg->attachment); 
		}
		try {
			if (!$sent = $message->send()) {
				throw new Exception($this->_transLang['MOD_SIMPLEEMAILFORM_error']);
			}
			$msg->copyMe = (isset($_POST['mod_simpleemailform_copyMe_' . $this->_instance])) 
							? (int) $_POST['mod_simpleemailform_copyMe_' . $this->_instance] : 0;
			// 2011-08-12 DB: added option for copyMeAuto
			if ($msg->copyMe || $msg->copyMeAuto) { 
				$message->ClearAllRecipients();
				$message->addRecipient($msg->from, $msg-fromName);
				if (!$sent = $message->send()) {
					throw new Exception($this->_transLang['MOD_SIMPLEEMAILFORM_error']);
				}
			}
			$result = TRUE;
		} catch (Exception $e) {
			$result = FALSE;
			$msg->error = $this->_transLang['MOD_SIMPLEEMAILFORM_error'] . ': Mail Server';
		}
		// 2012-02-15 db: override unwanted error messages originating from JMail
		// 2012-03-07 DB: added test mode
		if ($this->_testMode == 'Y') {
			$msg->error .= ob_get_contents();
		}
		ob_end_clean();
		return $result;
		
	}

	public function imageCaptcha(	
							$captchaBgColor, 
							$captchaDir, 
							$captchaHeight, 
							$captchaLen, 
							$captchaLinesColor, 
							$captchaSize, 
							$captchaTextColor, 
							$captchaURL, 
							$captchaWidth,
							&$url_fn)
	{
		require_once 'Image.php';
		$imgOptions = array(
			'font_size'  		=> $captchaSize,
			'font_path'     	=> dirname(__FILE__),
			'font_file'     	=> 'FreeSansBold.ttf',
			'text_color'    	=> $captchaTextColor,
			'lines_color'    	=> $captchaLinesColor,
			'background_color'	=> $captchaBgColor
		);        
		$options = array(
			'width' 		=> $captchaWidth,
			'height'   		=> $captchaHeight,
			'output'  		=> 'png',
			'imageOptions'	=> $imgOptions
		);        
		// Generate a new Text_CAPTCHA object, Image driver
		$c = Text_CAPTCHA::factory('Image');
		$retval = $c->init($options);
		if (PEAR::isError($retval)) {
			throw new Exception($this->_transLang['MOD_SIMPLEEMAILFORM_captcha_error_init'] . ' ' . $retval->getMessage());
		}

		// Get CAPTCHA image (as PNG)
		$png = $c->getCAPTCHAAsPNG();
		if (PEAR::isError($png)) {
			throw new Exception($this->_transLang['MOD_SIMPLEEMAILFORM_captcha_error_gen'] . ' ' . $png->getMessage());
		}
		$randval = time() . rand(1,999);
		$fn = 'captcha_' . $this->_instance . '_' . md5($randval) . '.png';
		$put_fn = $captchaDir . DIRECTORY_SEPARATOR . $fn;
		$url_fn = $captchaURL . DIRECTORY_SEPARATOR . $fn;
		JFile::write($put_fn, $png);
		
		return $c->getPhrase();
	}		

	public function textCaptcha($captchaBgColor, 
								$captchaLen, 
								$captchaSize, 
								$captchaTextColor, 
								$captchaWidth, 
								&$textCaptcha)
	{
			$alpha = 'abcdefghijklmnopqrstuvwxyz';
			$textCaptcha = "<span style='color: $captchaTextColor; background-color: $captchaBgColor;'>";
			$phrase = '';
			$count = 0;
			for ($x = 0; $x < $captchaLen; $x++) {
				$a = substr($alpha, rand(0,25), 1);
				$phrase .= $a;
				switch ($count) {
					case ($count % 3) :
						$textCaptcha .= "<b>$a</b>";
						break;
					case ($count % 2) :
						$textCaptcha .= "<font size=+1>$a</font>";
						break;
					default :
						$textCaptcha .= "<font size=+2>$a</font>";
						break;
				}
				$count++;
			}
			$textCaptcha .= '</span>';
			return $phrase;
	}

	protected function formatErrorMessage($color, $message, $fn = '')
	{
		if ($fn) {
			$message = "<p><b><span style='color:$color;'>$message ($fn)</span></b></p>\n";
		} else {
			$message = "<p><b><span style='color:$color;'>$message</span></b></p>\n";
		}
		return $message;
	}

	protected function autoResetForm()
	{
		foreach ($_POST as $key => $value) {
			$_POST[$key] = '';
		}
	}

	/**
	 * Verifies that the string is in a proper email address format.
	 * @param   string  $email  String to be verified.
	 * @return  boolean  True if string has the correct format; false otherwise.
	 * @since   11.1
	 */
	public static function isEmailAddress($email)
	{
		// Split the email into a local and domain
		$atIndex = strrpos($email, "@");
		$domain = substr($email, $atIndex + 1);
		$local = substr($email, 0, $atIndex);

		// Check Length of domain
		$domainLen = strlen($domain);
		if ($domainLen < 1 || $domainLen > 255)
		{
			return false;
		}

		/*
		 * Check the local address
		 * We're a bit more conservative about what constitutes a "legal" address, that is, A-Za-z0-9!#$%&\'*+/=?^_`{|}~-
		 * Also, the last character in local cannot be a period ('.')
		 */
		$allowed = 'A-Za-z0-9!#&*+=?_-';
		$regex = "/^[$allowed][\.$allowed]{0,63}$/";
		if (!preg_match($regex, $local) || substr($local, -1) == '.')
		{
			return false;
		}

		// No problem if the domain looks like an IP address, ish
		$regex = '/^[0-9\.]+$/';
		if (preg_match($regex, $domain))
		{
			return true;
		}

		// Check Lengths
		$localLen = strlen($local);
		if ($localLen < 1 || $localLen > 64)
		{
			return false;
		}

		// Check the domain
		$domain_array = explode(".", rtrim($domain, '.'));
		$regex = '/^[A-Za-z0-9-]{0,63}$/';
		foreach ($domain_array as $domain)
		{

			// Must be something
			if (!$domain)
			{
				return false;
			}

			// Check for invalid characters
			if (!preg_match($regex, $domain))
			{
				return false;
			}

			// Check for a dash at the beginning of the domain
			if (strpos($domain, '-') === 0)
			{
				return false;
			}

			// Check for a dash at the end of the domain
			$length = strlen($domain) - 1;
			if (strpos($domain, '-', $length) === $length)
			{
				return false;
			}
		}

		return true;
	}
	
	// Main logic
	public function main()
	{
		// initialize vars + test mode
		if ($this->_testMode == 'Y') {
			ini_set('display_errors', 1);
		}
		$message = '';
		// set session info
		$sessionNamespace = 'mod_simpleemailform_' . $this->_instance;
		$currentSession = JFactory::getSession();
		if (isset($this->_useCaptcha) && $this->_useCaptcha == "I") {
			try {
				// Get rid of old CAPTCHA images older than time() - 300
				$date = JFactory::getDate();
				$timeCheck = $date->toUnix() - 300;
				foreach(new DirectoryIterator($this->_captchaDir) as $file) {
					if (!$file->isDot()) {
						$fn = $file->getFilename();
						if (strlen($fn) > 8 && substr($fn,0,8) == "captcha_") {
							$fn = $this->_captchaDir . DIRECTORY_SEPARATOR . $fn;
							// remove CAPTCHAs older than 5 minutes
							if ($file->getMTime() < $timeCheck) unlink($fn);
						}
					}
				}
			} catch (Exception $e) {
				$this->_output .= $this->formatErrorMessage($this->_errorTxtColor, $this->_transLang['MOD_SIMPLEEMAILFORM_unable_clean_captcha']);
				// Make Captcha directory and URL recommendations
				$dirs = explode(DIRECTORY_SEPARATOR, dirname(__FILE__));
				if (count($dirs) > 2) {
					array_pop($dirs);
					array_pop($dirs);
				}
				$suggestedCaptchaDir = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'captcha';
				$suggestedCaptchaURL = 'http://' . $_SERVER['HTTP_HOST'] . DIRECTORY_SEPARATOR . 'captcha';
				$this->_output .= "<p>" . $this->_transLang['MOD_SIMPLEEMAILFORM_make_captcha_dir'] . ": " . $suggestedCaptchaDir . "</p>\n";
				$this->_output .= "<p>" . $this->_transLang['MOD_SIMPLEEMAILFORM_make_captcha_url'] . ": " . $suggestedCaptchaURL . "</p>\n";
			}
		}

		if (isset($_POST['mod_simpleemailform_submit_' . $this->_instance])) {

			$this->_msg->from = strip_tags($_POST['mod_simpleemailform_from_field_' . $this->_instance]);
			if ($this->_uploadActive == 'Y') {
				$result = $this->uploadAttachment($this->_msg->dir, 
												  $this->_uploadAllowed, 
												  $this->_errorTxtColor, 
												  $this->_successTxtColor, 
												  $this->_fileMsg);
				// if valid attachment, assign to _msg, blank otherwise
				$this->_msg->attachment = $result;
			}
			
			// Check to see if "from" email address contains "&#64;"
			$this->_msg->from = str_ireplace('&#64;', '@', $this->_msg->from);
			
			// Check "from" email address
			// 2012-02-07 DB: changed from internal validation to JMailHelper::isEmailAddress
			if (!$this->_emailCheck || $this->isEmailAddress($this->_msg->from)) {
				// Check required fields
				$requiredCheck = TRUE;
				for ($x = 1; $x < $this->_maxFields; $x++) {
					$fieldLabel = 'mod_simpleemailform_field' . $x . '_' . $this->_instance;
					if ($this->_field[$x]['active'] == 'R') {
						if (!isset($_POST[$fieldLabel]) || $_POST[$fieldLabel] == NULL) {
							$requiredCheck = FALSE;
							$this->_field[$x]['error'] = $this->_transLang['MOD_SIMPLEEMAILFORM_required_field'] . ' ' . $this->_field[$x]['label'];
						}
					}
				}
				if ($this->_txtActive == 'R') {
						if (!isset($_POST['mod_simpleemailform_textarea_' . $this->_instance]) 
							|| $_POST['mod_simpleemailform_textarea_' . $this->_instance] == NULL) {
							$requiredCheck = FALSE;
							$this->_txtError = $this->_transLang['MOD_SIMPLEEMAILFORM_required_field'] . " " . $this->_txtLabel;
						}
				}
				if ($requiredCheck) {
					// Validate captcha if active
					if ($this->_useCaptcha != 'N') {
						$captchaPhrase = $currentSession->get('captchaPhrase', '', $sessionNamespace);
						if (isset($_POST['mod_simpleemailform_captcha_' . $this->_instance]) 
							&& $_POST['mod_simpleemailform_captcha_' . $this->_instance] == $captchaPhrase) {
							if ($this->sendResults($this->_msg, $this->_field, $this->_txtLabel, $this->_fromlabel)) {
								$message .=  $this->formatErrorMessage($this->_successTxtColor, $this->_transLang['MOD_SIMPLEEMAILFORM_form_success']);
								if (isset($this->_redirectURL) && $this->_redirectURL !== '') { 
									header('Location: ' . $this->_redirectURL);
									exit;
								}
								if ($this->_autoReset == 'Y') { 
									$this->autoResetForm(); 
								}
							} else {
								$message .=  $this->formatErrorMessage($this->_errorTxtColor, $this->_transLang['MOD_SIMPLEEMAILFORM_form_unable']);
							}
						} else {
							$message .=  $this->formatErrorMessage($this->_errorTxtColor, $this->_transLang['MOD_SIMPLEEMAILFORM_form_reenter']);
						}
					} else {
						if ($this->sendResults($this->_msg, $this->_field, $this->_txtLabel, $this->_fromlabel)) {
							$message .=  $this->formatErrorMessage($this->_successTxtColor, $this->_transLang['MOD_SIMPLEEMAILFORM_form_success']);
							if (isset($this->_redirectURL) && $this->_redirectURL !== '') { 
								header('Location: ' . $this->_redirectURL);
								exit;
							}
							if ($this->_autoReset == 'Y') { 
								$this->autoResetForm(); 
							}
						} else {
							$message .=  $this->formatErrorMessage($this->_errorTxtColor, $this->_transLang['MOD_SIMPLEEMAILFORM_form_unable']);
						}
					}
					// Add any mail server error messages (is blank if none)
					$message .= $this->_msg->error;
				}
			} else {
				$this->_badEmail .= $this->_transLang['MOD_SIMPLEEMAILFORM_email_invalid'];
			}
		} elseif (isset($_POST['mod_simpleemailform_reset_' . $this->_instance])) {
			$this->autoResetForm();
		}

		// Present the Email Form
		$this->_output .= ($this->_cssClass) ? "<div class='" . $this->_cssClass . "'>\n" : '';
		// 2012-04-20 DB: added anchor tag if > 1 (default anchor = #)
		$this->_output .= (strlen($this->_anchor) > 1) ? "<a name='" . substr($this->_anchor, 1) . "'>&nbsp;</a>\n" : '';
		if ($this->_testMode == 'Y') {
			$this->_output .= '<pre>';
			$this->_output .= 'Mail Object:' . '<br />';
			$this->_output .= var_export($this->_msg, TRUE);
			$this->_output .= 'Params:' . '<br />';
			$this->_output .= var_export($this->_params, TRUE);
			$this->_output .= '</pre>';
		}
		$this->_output .= "<form method='post' "
						. "action='" . $this->_anchor . "' "
						. "name='_SimpleEmailForm_" . $this->_instance . "' "
						. "id='_SimpleEmailForm_" . $this->_instance . "' "
						. "enctype='multipart/form-data'>\n";
		$this->_output .= "<table" . $this->_tableClass . ">\n";
		$this->_output .= $this->formatRow( 'mod_simpleemailform_from_field_' . $this->_instance, 
											$this->_fromsize, 
											$this->_fromlabel, 
											$this->_badEmail);
		if (!isset($_POST['mod_simpleemailform_subject_' . $this->_instance])) { 
			$_POST['mod_simpleemailform_subject_' . $this->_instance] = $this->_subjectline; 
		}
		$this->_output .= $this->formatRow('mod_simpleemailform_subject_' . $this->_instance, 
										   $this->_subjectsize, 
										   $this->_subjectlabel);
		// 2010-08-24 DB: restructured field params into array 
		for ($x = 1; $x < $this->_maxFields; $x++) {
			$fieldLabel = 'mod_simpleemailform_field' . $x . '_' . $this->_instance;
			if ($this->_field[$x]['active'] != 'N' ) {
				$this->_output .= $this->formatRow($fieldLabel,
												   $this->_field[$x]['size'],
												   $this->_field[$x]['label'],
												   $this->_field[$x]['error'],
												   $this->_field[$x]['maxx']);
			}
		}
		if ($this->_txtActive != 'N') {
			$value = (isset($_POST['mod_simpleemailform_textarea_' . $this->_instance])) 
				   ? htmlspecialchars($_POST['mod_simpleemailform_textarea_' . $this->_instance]) 
				   : '';
			$this->_output .= "<tr" . $this->_trClass   . ">";
			$this->_output .= "<th" . $this->_thClass    . " align='" . $this->_labelAlign . "'>" . $this->_txtLabel . "</th>";
			// space between cols
			$this->_output .= "<td" . $this->_spaceClass . " width='" . $this->_col2space . "'>&nbsp;</td>";
			// textarea
			$this->_output .= "<td" . $this->_tdClass    . ">";
			// NOTE: $this->_inputClass has a space prepended
			$this->_output .= "<textarea "
							. "name='mod_simpleemailform_textarea_" . $this->_instance . "' "
							. "id='textarea_" . $this->_instance . "' "
							. "rows='" . $this->_txtRows . "' "
							. "cols='" . $this->_txtCols . ' '
							. $this->_inputClass . "'>"
							. stripslashes($value)
							. "</textarea>";
			$this->_output .= ($this->_txtError) 
							  ? $this->formatErrorMessage($this->_errorTxtColor, $this->_txtError)
							  : '';
			$this->_output .= "</td>";
			$this->_output .= "</tr>\n";
		}
		if ($this->_uploadActive == 'Y') {
			$this->_output .= "<tr" . $this->_trClass . ">";
			$this->_output .= "<th" . $this->_thClass . " align='" . $this->_labelAlign . "'>" 
							. $this->_transLang['MOD_SIMPLEEMAILFORM_attachment'] 
							. "</th>";
			// space between cols
			$this->_output .= "<td" . $this->_spaceClass . " width='" . $this->_col2space . "'>&nbsp;</td>";
			// file upload field
			$this->_output .= "<td" . $this->_tdClass . ">";
			$this->_output .= "<input "
							. "type=file "
							. "name='mod_simpleemailform_upload_" . $this->_instance . "' "
							. "id='mod_simpleemailform_upload_" . $this->_instance . "' "
							. "enctype='multipart/form-data' "
							. $this->_inputClass . " />";
			$this->_output .= $this->_fileMsg;
			$this->_output .= "</td>";
			$this->_output .= "</tr>\n";
		}
		if ($this->_useCaptcha != 'N') {
			try {
				// Set CAPTCHA secret passphrase + store in session
				if ($this->_useCaptcha == 'I') {
					$phrase = $this->imageCaptcha(	$this->_captchaBgColor, 
													$this->_captchaDir, 
													$this->_captchaHeight, 
													$this->_captchaLen, 
													$this->_captchaLinesColor, 
													$this->_captchaSize, 
													$this->_captchaTextColor, 
													$this->_captchaURL, 
													$this->_captchaWidth,
													$this->_url_fn);
				} else {
					$phrase = $this->textCaptcha(	$this->_captchaBgColor, 
													$this->_captchaLen, 
													$this->_captchaSize, 
													$this->_captchaTextColor, 
													$this->_captchaWidth,
													$this->_textCaptcha);
				}
				
				// store CAPTCHA in session
				$currentSession->set('captchaPhrase', $phrase, $sessionNamespace);

				// render CAPTCHA
				$this->_output .= "<tr" . $this->_trClass . ">";
				$this->_output .= "<th" . $this->_thClass . " align='" . $this->_labelAlign . "'>" 
								. $this->_transLang['MOD_SIMPLEEMAILFORM_captcha_please_enter'] 
								. "</th>";
				// space between cols
				$this->_output .= "<td" . $this->_spaceClass . " width='" . $this->_col2space . "'>&nbsp;</td>";
				// captcha
				$this->_output .= "<td" . $this->_tdClass . ">";
				if ($this->_useCaptcha == 'I') {
					$this->_output .= vsprintf("<img src='%s' width='%s' height='%s' %s />", 
											   array($this->_url_fn, $this->_captchaWidth,$this->_captchaHeight, $this->_captchaClass));
					$this->_output .= "<br />";
				} else {
					$this->_output .= $this->_textCaptcha;
				}
				$this->_output .= "<input name='mod_simpleemailform_captcha_" . $this->_instance . "' "
								. "id='mod_simpleemailform_captcha_" . $this->_instance . "' "
								. "type='text' "
								. "size='" . $this->_captchaLen . "' "
								. "maxlength='" . $this->_captchaLen . "' " 
								. $this->_inputClass . " />";
				$this->_output .= "&nbsp;" . $this->_transLang['MOD_SIMPLEEMAILFORM_captcha_please_help'] . "</td>";
				$this->_output .= "</tr>\n";
			} catch (Exception $e) {
				$this->_output .= "<tr" . $this->_trClass . ">";
				$this->_output .= "<th" . $this->_thClass . ">" . $this->_transLang['MOD_SIMPLEEMAILFORM_error'] . "</th>";
				// space between cols
				$this->_output .= "<td" . $this->_spaceClass . " width='" . $this->_col2space . "'>&nbsp;</td>";
				// message
				$this->_output .= "<td" . $this->_tdClass . ">" . $e->getMessage() . "</td>";
				$this->_output .= "</tr>\n";
			}
		}

		if ($this->_copymeActive == 'Y') {
			$this->_output .= "<tr" . $this->_trClass . ">";
			$this->_output .= "<th" . $this->_thClass . ">&nbsp;</th>";
			// space between cols
			$this->_output .= "<td" . $this->_spaceClass . " width='" . $this->_col2space . "'>&nbsp;</td>";
			// copy me
			$this->_output .= "<td" . $this->_tdClass . ">";
			$this->_output .= "<input "
							. "type='checkbox' "
							. "name='mod_simpleemailform_copyMe_" . $this->_instance . "' "
							. "id='mod_simpleemailform_copyMe_" . $this->_instance . "' "
							. "value='1' " 
							. $this->_inputClass . " />"
							. $this->_copymeLabel 
							. "</td>";
			$this->_output .= "</tr>\n";
		}
		$this->_output .= "<tr" . $this->_trClass . ">";
		$this->_output .= "<th" . $this->_thClass . ">&nbsp;</th>";
		// space between cols
		$this->_output .= "<td" . $this->_spaceClass . " width='" . $this->_col2space . "'>&nbsp;</td>";
		// buttons
		$this->_output .= "<td" . $this->_tdClass . ">";
		$this->_output .= "<input " 
						. $this->_inputClass . " "
						. "type='submit' "
						. "name='mod_simpleemailform_submit_" . $this->_instance . "' "
						. "id='mod_simpleemailform_submit_" . $this->_instance . "' "
						. "value='" . $this->_transLang['MOD_SIMPLEEMAILFORM_button_submit'] . "' "
						. "title='" . $this->_transLang['MOD_SIMPLEEMAILFORM_click_submit'] . "' />";
		$this->_output .= "&nbsp;&nbsp;";
		$this->_output .= "<input " 
						. $this->_inputClass . " "
						. "type='submit' "
						. "name='mod_simpleemailform_reset_" . $this->_instance . "' "
						. "id='mod_simpleemailform_reset_" . $this->_instance . "' "
						. "value='" . $this->_transLang['MOD_SIMPLEEMAILFORM_button_reset'] . "' "
						. "title='' />";
		$this->_output .= "</td>";
		$this->_output .= "</tr>\n";
		// 2011-12-10 DB: added test for $message before adding extra row
		if ($message) {
			$this->_output .= "<tr" . $this->_trClass . ">";
			$this->_output .= "<th" . $this->_thClass . ">&nbsp;</th>";
			// space between cols
			$this->_output .= "<td" . $this->_spaceClass . " width='" . $this->_col2space . "'>&nbsp;</td>";
			// message
			$this->_output .= "<td" . $this->_tdClass . ">" . $message . "</td>";
			$this->_output .= "</tr>\n";
		}
		$this->_output .= "</table>\n";
		$this->_output .= "</form>\n";
		$this->_output .= ($this->_cssClass) ? '</div>' : '';
		return $this->_output;
	}
}
