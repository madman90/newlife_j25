<?php
/*------------------------------------------------------------------------
# PLG - SYSTEM - IMAGESIZER
# ------------------------------------------------------------------------
# author    reDim - Norbert Bayer
# copyright (C) 2011 redim.de. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.redim.de
# Technical Support:  Forum - http://www.redim.de/kontakt/
-------------------------------------------------------------------------*/

# reDim - Norbert Bayer
# Plugin: ImageSizer for Joomla! 1.6/1.7/2.5
# license GNU/GPL   www.redim.de

# Thanks to cb-bois-chauffage.fr (Language FR)
# Thanks to Jacob Lieben (Language NL) 
# Thanks to Martin Skroch'
#
# Version 1.6 Stable
#
# Version 1.6.1 - 06.12.2011
# eregi entfernt

# Version 1.6.2 - 11.01.2012
# Verschiedene kleine Fehler behoben.

# Version 1.6.3 - 18.01.2012
# Verschiedene kleine Fehler behoben.
# Das Auslagern der generierten Bilder verbessert

# Version 1.6.4 - 25.01.2012
# Thumb-File-Verwaltung verbessert
# Sprachfiles FR und NL hinzugefügt
# Insert-Funktion auch im Frontend

# Version 1.6.4 - 07.02.2012
# Probleme mit Windows-Installationen behoben
# Probleme mit Inline-Grafiken (data:image) behoben


# Version 1.6.5 - 01.03.2012
# Probleme mit Sonderzeichen in Bildnamen
# Joomla-FTP-Funktion wieder eingebaut
#
# Danke an Michael Plass für die Unterstützung
# Danke an Christopher Schmidt (Verbesserung deutsches Sprachfile)

# Version 1.6.6 - 21.03.2012
# Probleme bei Installationen im Unterordner behoben.

# Version 1.6.7 - 15.08.2012
# Probleme mit großer Bildskalierung behoben (Es gab Probleme mit dem Pfad)

# Version 2.5.0 - Final - Last Version.

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
define('_IMAGESIZER_IS_LOAD',true);
jimport( 'joomla.plugin.plugin' );

class plgSystemimagesizer extends JPlugin {

	public $_redim_id="198";
	public $_redim_name="ImageSizer";
	public $_redim_version="2.5.0";

	public $errors=array();
	public $created_pics=0;
	public $deleted_pics=0;
	public $counter=0;
	public $article=NULL;

	private $load_java="";


	public function redim_imagesizer(){
	   
        $dat = new stdClass();
		$dat->_id=$this->_redim_id;
		$dat->_name=$this->_redim_name;	
		$dat->_version=$this->_redim_version;
		
		return $dat;
		
	}


	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

    }

	public function onContentBeforeSave($context, &$article, $isNew)
	{

		if(isset($article->introtext)){
			$article->introtext = preg_replace_callback('/\{(imagesizer)\s*(.*?)\}/i',array($this,"imagesizer_cmd"), $article->introtext);
		}
		if(isset($article->fulltext)){
			$article->fulltext = preg_replace_callback('/\{(imagesizer)\s*(.*?)\}/i',array($this,"imagesizer_cmd"), $article->fulltext);
		}
		if(isset($article->text)){
			$article->text = preg_replace_callback('/\{(imagesizer)\s*(.*?)\}/i',array($this,"imagesizer_cmd"), $article->text);
		}

	}

	public function onContentAfterSave($context, &$article, $isNew)
	{
		$app = JFactory::getApplication();
		$this->_loadLanguage();

	 	$text="";

	 	if(isset($article->introtext)){
			$text.=$article->introtext;
		}
	 
	 	if(isset($article->fulltext)){
			$text.=$article->fulltext;
		}

	 	if(isset($article->description)){
			$text.=$article->description;
		}

/*
		$tmptext=$text;
		if($this->params->get("deltc",1)==1){
			$text=strip_tags($text,"<img>");
		    $regex="/\<img (.*?)\>/i";
		    $text=preg_replace_callback($regex,array($this,"imagesizer_del"),$text);
		}
*/

		if($this->params->get("generate",2)!=2){
			return true;
		}
		
	
		$text=strip_tags($text,"<img>");
			
	    $regex="/\<img (.*?)\>/i";
	    $text=preg_replace_callback($regex,array($this,"imagesizer"),$text);	    
		unset($text);

		if($this->created_pics>0){
			$app->enqueueMessage(JText::sprintf('IMAGESIZER_X_IMAGES_CREATED',$this->created_pics));
		}
	
		$this->get_errors();
	
		return true;
	}
	
	public function Includefiles(){
		
		if(defined("imagesizer_filesload")){
			return;
		}
		jimport( 'joomla.html.parameter' );
		define("imagesizer_filesload",1);
	
		$file=$this->params->get("lbscript","");
		if($file=="-1" or $file==""){$file="default.php";}
		$file=JPATH_SITE.DS."plugins".DS."system".DS."imagesizer".DS."lbscripts".DS.$file;

		if(file_exists($file)){
			include_once($file);
		}		
		
	}

	private function send_helpdata($email="",$text=""){

		if(empty($email)){
			$user = JFactory::getUser();
			$email = $user->get("email");	
		}


		jimport('joomla.mail.helper');
		
	 	if(!JMailHelper::isEmailAddress($email)){
			return false;
		}

		$ar=array();
		$config = JFactory::getConfig();

		#echo $config->getValue('sitename');

		$ar["redim_id"]=$this->_redim_id;
		$ar["redim_name"]=$this->_redim_name;
		$ar["redim_version"]=$this->_redim_version;
		$ar["joomla"]=JVERSION;
		$ar["site"]=JURI::root(false);
		$ar["file"]=__FILE__;	
		
		$ar["ftp_enable"]=$config->getValue('ftp_enable',"");
		
		$e='picclass,linkclass,lbscript,generate,generate2,deltc,insert,minsizex,minsizey,maxsizex,maxsizey,modus,chmod,pro';
		
		$e=explode(",",$e);
		foreach($e as $k){
			$ar[$k]=$this->params->get($k,"");
		}
		
		$ar["thumbspath"]=$this->params->get("thumbspath","");
		$ar["chmod"]=JPath::clean(JPATH_SITE.DS.$ar["thumbspath"]);
		$ar["chmod"]=substr(decoct(fileperms($ar["chmod"])),1);
		
		if ( is_writeable (JPATH_SITE.DS.$ar["thumbspath"] ) ){
			$ar["dirwriteable"]="yes";	
		}else{
			$ar["dirwriteable"]="no";			
		}
		
		$body="";
		$body.=$text."\n\n\n";
		
		foreach($ar as $k => $v){
			$body.=$k.": ".$v."\n";	
		}


		jimport('joomla.mail.mail');
		// Create a JMail object
		$mail		= JMail::getInstance();
		$mail->IsHTML(false);
		$mail->addRecipient( "support@redim.de" );	
		$mail->setSender( array( $email , $email ) );
		$mail->addReplyTo( array( $email , $email ) );

		$title="Help&Support: ".$this->_redim_name." - ".$this->_redim_id;
	
		$mail->setSubject($title);
	#	$body=$this->sethtmlheader($title,$body);

		$mail->setBody( $body );
		
		return $mail->Send();	

	}

	public function onAfterInitialise()
	{

		$app = JFactory::getApplication();

		if ($app->getName() == 'administrator') {
			if(JRequest::getCMD("code","")=="redim-helper"){
				$user = JFactory::getUser();
		#		$lang = JFactory::getLanguage();
		#		$lang->load('plg_system_imagesizer', JPATH_ADMINISTRATOR);
				$this->_loadLanguage();
				if($user->id>0){
					$email=JRequest::getVAR("email","");
					$text=JRequest::getVAR("text","");		
					
					if($this->send_helpdata($email,$text)){
						echo JText::_("IMAGESIZER_HELP_EMAIL_ISSEND"); 
					}else{
						echo JText::_("IMAGESIZER_HELP_EMAIL_NOTSEND");					
					}
				
					die();
				}
			}
		}
	
	
		if($this->params->get("generate2","prepare")!="render"){
			return;
		}

		if ($app->getName() != 'site') {
			return true;
		}


		$this->Includefiles();

	}

	public function onAfterDispatch()
	{

		if($this->params->get("insert","0")!="1"){
			return;
		}

		$app = JFactory::getApplication();

		if ($app->getName() == 'site') {

			$ch=strtolower(JRequest::getVar('option','').$view=JRequest::getVar('view',''));	

			if ($ch!="com_mediaimagescomponent" and $ch!="com_mediaimages"){		
				return true;
			}			
		}

		$document   = JFactory::getDocument();
		
		if(!isset($document->_scripts[JURI::root(true)."/media/media/js/popup-imagemanager.js"])){
			return true;
		}

		unset($document->_scripts[JURI::root(true)."/media/media/js/popup-imagemanager.js"]);

		$java='var imagesizer2_x='.$this->params->get("minsizex",122).';';
		$java.=' var imagesizer2_y='.$this->params->get("minsizey",122).';';	

		$document->addScript(JURI::ROOT().'plugins/system/imagesizer/js/popup-imagemanager.js');
		$document->addScriptDeclaration($java);		
		unset($java);

#		JHtml::_('script','plugins/system/imagesizer/js/popup-imagemanager.js', true, true);

	}


	private function redim_support(){

		if(defined("redim_support") or !defined("imagesizer_filesload")){
			return;
		}
			
		$keys=" ".JFactory::getDocument()->getMetadata('keywords');
		
		$c="";
		if(stristr ( $keys,"webdesign" )) {
			$c="PG5vc2NyaXB0PjwhLS0gZnJlZSB2ZXJzaW9uIG9mIGltYWdlc2l6ZXIgLSBkbyBub3QgcmVtb3ZlIHRoaXMgbGluayAtLT48YSB0aXRsZT0iV2ViZGVzaWduIEpvb21sYSEiIGhyZWY9Imh0dHA6Ly93d3cucmVkaW0uZGUvbGVpc3R1bmdlbi93ZWJkZXNpZ24vIj5Kb29tbGEhIFdlYmRlc2lnbjwvYT48L25vc2NyaXB0Pg==";		
		}elseif(stristr ( $keys," seo " )) {
			$c="PG5vc2NyaXB0PjwhLS0gZnJlZSB2ZXJzaW9uIG9mIGltYWdlc2l6ZXIgLSBkbyBub3QgcmVtb3ZlIHRoaXMgbGluayAtLT48YSB0aXRsZT0iU3VjaG1hc2NoaW5lbm9wdGltaWVydW5nIiBocmVmPSJodHRwOi8vd3d3LnJlZGltLmRlL2xlaXN0dW5nZW4vc3VjaG1hc2NoaW5lbm9wdGltaWVydW5nLXNlby8iPlNFTyBm/HIgSWhyZSBXZWJzaXRlPC9hPjwvbm9zY3JpcHQ+";		
		}elseif(stristr ( $keys,"programm" )) {
			$c="PG5vc2NyaXB0PjwhLS0gZnJlZSB2ZXJzaW9uIG9mIGltYWdlc2l6ZXIgLSBkbyBub3QgcmVtb3ZlIHRoaXMgbGluayAtLT48YSB0aXRsZT0iUHJvZ3JhbW1pZXJlciBKb29tbGEhIiBocmVmPSJodHRwOi8vd3d3LnJlZGltLmRlL2xlaXN0dW5nZW4vd2ViZW50d2lja2x1bmcvIj5Kb29tbGEhIFByb2dyYW1taWVydW5nPC9hPjwvbm9zY3JpcHQ+";			
		}elseif(stristr ( $keys,"website" )) {
			$c="PG5vc2NyaXB0PjwhLS0gZnJlZSB2ZXJzaW9uIG9mIGltYWdlc2l6ZXIgLSBkbyBub3QgcmVtb3ZlIHRoaXMgbGluayAtLT48YSB0aXRsZT0iV2ViZGVzaWduIGFuZCBQcm9ncmFtbWluZyBmb3IgSm9vbWxhISIgaHJlZj0iaHR0cDovL3d3dy5yZWRpbS5kZS8iPldlYmRlc2lnbiAtIFByb2dyYW1taWVydW5nPC9hPjwvbm9zY3JpcHQ+";		
		}else {
			$c="PG5vc2NyaXB0PjwhLS0gZnJlZSB2ZXJzaW9uIG9mIGltYWdlc2l6ZXIgLSBkbyBub3QgcmVtb3ZlIHRoaXMgbGluayAtLT48YSB0aXRsZT0iV2ViZGVzaWduIG1pdCBKb29tbGEhIiBocmVmPSJodHRwOi8vd3d3LnJlZGltLmRlLyI+V2ViZGVzaWduIEpvb21sYSE8L2E+PC9ub3NjcmlwdD4=";			
		}
		
		if(!empty($c)){
		
		    define("redim_support",1);
			$c="\n".base64_decode($c)."\n";
			$buffer = JResponse::getBody();
			if($buffer = preg_replace('/<\/body>(?!.*<\/body>)/is',$c.'$0',$buffer,1)){	JResponse::setBody($buffer);}
			unset($buffer,$c);
		
		}	

	 }

	public function onAfterRender(){

		$app = JFactory::getApplication();
			 	
		if ($app->getName() != 'site') {
			return true;
		}
	
		
		$this->redim_support();		
						 		
		if($this->params->get("generate2","prepare")!="render"){
			return;
		}
	
		$buffer = JResponse::getBody();
		$this->_imagesizer_preg($buffer);
		JResponse::setBody($buffer);
			 				
		unset($buffer);
	}
	
	public function onContentPrepare($context, &$row, &$params, $page = 0){

		if($this->params->get("generate2","prepare")!="prepare"){
			return;
		}

 	#   $regex="/\<img (.*?)\>/i";
	#	$regex="/\<a (.*?)>(.*?(?=<img ).*?)\<\/a>/i";
	#	$regex="/(?=<a )\<img (.*?)\>/i";
		if(!isset($row->id)){
			$row->id=$this->counter;
		}

		$this->article=$row;

		if(isset($row->text)){
			$this->_imagesizer_preg($row->text);			
		}
		
		if(isset($row->introtext)){
			$this->_imagesizer_preg($row->introtext);			
		}
		
		if(isset($row->fulltext)){
			$this->_imagesizer_preg($row->fulltext);			
		}	

		$this->counter++;
		
	}		
	
	private function _imagesizer_preg(&$text){

#		$regex="/\<a (.*?)>(.*?(?=\<img ).*?)\<\/a>/i";
		$regex="/\<a (.*?)>(.*?)\<\/a>/i";
		$text = preg_replace_callback($regex,array($this,"imagesizer"),$text);

	    $regex="/\<img (.*?)\>/i";
	    $text = preg_replace_callback($regex,array($this,"imagesizer"),$text);	    
		$text = preg_replace("/<#img /i","<img ",	$text );

		$this->get_errors();
#		$text = preg_replace_callback('/\{(imagesizer)\s*(.*?)\}/i',array($this,"imagesizer_cmd"), $text);
		
	}
	
	private function imagesizer_cmd(&$matches){
		
		if(!isset($matches[2])){
			return $matches[0];
		}

		jimport('joomla.filesystem.file');

		$p=$this->match_to_params($matches[2]);
		
		$path=JPath::clean(trim($p->get("path","")));
		$limit=$p->get("limit","");
		$limit=explode(",",$limit);
		if(isset($limit[1])){
			$start=$limit[0];
			$limit=$limit[1];
		}else{
			$start=0;
			$limit=(int) $limit[0];
		}

		if(substr($path,-1,1)==DS){
			$path=substr($path,0,-1);
		}

		$files	= JFolder::files(JPATH_SITE.DS.$path, '\.png$|\.gif$|\.jpg$|\.PNG$|\.GIF$|\.JPG$',false,false);
        $LiveSite = JURI::root();
        
		$imagesizer2_x=$this->params->get("minsizex",120);
		$imagesizer2_y=$this->params->get("minsizey",120);
		$class=$p->get("class","");

		if(!empty($class)){
			$class='class="'.$class.'" ';	
		}

		$imgs=array();
		
		$ii=0;
		foreach($files as $i => $file){
			
			if( ($i>=$start and $ii<$limit) or $limit==0){
			
				if($info = @getimagesize(JPATH_SITE.DS.$path.DS.$file)){
				
					if(count($info)>2){
						
						$ii++;
	
			            if ($info[0] > $imagesizer2_x OR $info[1] > $imagesizer2_y){
			
			                $faktor = 0;
			
			   				if ($info[0]>$info[1] || $info[0]==$info[1]){
			   					$faktor = $info[0] / $imagesizer2_x ;
			   				}
			
			    			if ($info[0]<$info[1]){
			   					$faktor =  $info[1] / $imagesizer2_y ;
			   				}
			
			                if ($faktor>0){
			                   $xx = round( $info[0] / $faktor , 0);
			                   $yy = round( $info[1] / $faktor , 0);
			                }
						}else{
							$xx=$info[0];
							$yy=$info[1];
						}
				
		 
						$xx=$p->get("width",$xx);		
						$yy=$p->get("height",$yy);
		 
						$imgs[]='<img src="'.$LiveSite.str_replace("\\","/",$path.DS.$file).'" width="'.$xx.'" height="'.$yy.'" '.$class.'/>';
		
					}			
				
				}
				
			}
			
			
		}

		if(count($imgs)){
			return implode("",$imgs);
		}
		
		return $matches[0];

	}

	private function calc_size($ar,$info){
		
		$ar["width"]=trim($ar["width"]);
		$ar["height"]=trim($ar["height"]);

		if(substr($ar["width"],-1,1)=="%"){
			$ar["width"]=@round(($info[0]/100)*intval($ar["width"]));
		}

		if(substr($ar["height"],-1,1)=="%"){
			$ar["height"]=@round(($info[1]/100)*intval($ar["height"]));			
		}		
		
		$ar["width"]=intval($ar["width"]);
		$ar["height"]=intval($ar["height"]);
		
		if($ar["width"]>0 and $ar["height"]==0){
			$factor=@round($info[0] / $ar["width"], 2);
			$ar["height"]= @round($info[1] / $factor, 0);
			unset($factor);
		}elseif($ar["width"]==0 and $ar["height"]>0){		
			$factor=@round($info[1] / $ar["height"], 2);	
			$ar["width"]= @round($info[0] / $factor, 0);
			unset($factor);			
		}

		return $ar;		

	}

	private function check_imgparams($ar){

		$ar["ext"]="";


		if(empty($ar["src"])){
			return $ar;
		}

		$url=parse_url($ar["src"]);

		if(isset($url["path"])){
			$ar["src"]=$url["path"];
		}

		if(isset($url["scheme"])){
			$url["scheme"]="http";
		}

		if(substr($ar["src"],0,1)!="/"){
			$ar["src"]="/".$ar["src"];
		}
		
		if(strtolower(substr($ar["src"],0,11))=="/templates/"){
			return $ar;		
		}

		if(isset($url["host"])){

			if(substr($ar["src"],0,1)=="/"){
				$ar["src"]=substr($ar["src"],1);
			}		 

			if(substr($url["host"],-1,1)!="/"){
				$url["host"]=$url["host"]."/";
			}		 
			
			$url2=parse_url(JURI::root());
			if(strtolower($url2["host"]."/")!=strtolower($url["host"])){
				$ar["ext"]=$url["scheme"]."://".$url["host"].$ar["src"];
			}
			unset($url2);
		}

		unset($url);
		return $ar;
	}

	private function checkmode_from_class($class,$default="equal"){

		$class="  ".$class."  ";

		if ( strpos ( $class , ' imgcut ' ) )
		{
		 	return "cut";
		}
		
		if ( strpos ( $class , ' imgzoom ' ) )
		{
		 	return "zoom";
		}
		
		if ( strpos ( $class , ' imgbig ' ) )
		{
		 	return "big";
		}
		
		if ( strpos ( $class , ' imgsmall ' ) )
		{
		 	return "small";
		}

		return $default;
		
	}

	
	private function clean_url($var){

	    $var = str_replace('&amp;', '&', $var);
	    $var = str_replace('&lt;', '<', $var);
	    $var = str_replace('&gt;', '>', $var);
	    $var = str_replace('&euro;', '€', $var);
	    $var = str_replace('&szlig;', 'ß', $var);
	    $var = str_replace('&uuml;', 'ü', $var);
	    $var = str_replace('&Uuml;', 'Ü', $var);
	    $var = str_replace('&ouml;', 'ö', $var);
	    $var = str_replace('&Ouml;', 'Ö', $var);
	    $var = str_replace('&auml;', 'ä', $var);
	    $var = str_replace('&Auml;', 'Ä', $var);
	    
	    if(substr($var,0,1)!="/"){
	     	if(substr($var,0,7)!="http://"){
				$var="/".$var;				
			}
		}
		
#		$p=JURI::root(true);
		$p=JURI::root();		
		
		
		$l=strlen($p);
		if(substr($var,0,$l)==$p){
			$var=substr($var,$l);
		}
		
		    
		return $var;
		
	}

	private function get_baseurl(){
		
		if(!defined("imagesizer_uribase")){
		
			$url=JURI::base(true);
			if(!empty($url)){
				if(substr($url,-1,1)!="/"){
					$url.="/";	
				}	
			}
			
			define("imagesizer_uribase",$url);
			unset($url);
		
		}

		return imagesizer_uribase;

	}
	
	private function combine_path($a,$b){
		
		if(!empty($a)){
			if(substr($a,-1,1)!="/"){
				$a.="/";	
			}			
		}
		if(!empty($b)){	
			if(substr($b,0,1)=="/"){
				$b=substr($b,1);
			}	
		}
		
		return $a.$b;
		
	}

	private function imagesizer(&$matches){

		$sharpit=false;

		if(count($matches)>2){
			if(isset($matches[2])){
				$ar=$this->make_arrays($matches[2],'/([a-zA-Z0-9._-]+)="(.*?)"/');
				$sharpit=true;

			}else{
				return $matches[0];
			}
		}else{
			$ar=$this->make_arrays($matches[1],'/([a-zA-Z0-9._-]+)="(.*?)"/');			
		}

		if(!isset($ar["src"])){
			return $matches[0];
		}else{
		 	if(preg_match("/data\:image\//i",$ar["src"])){
				return $matches[0];
			}
			if(isset($ar["class"])){
		    	if(preg_match("/ nothumb /i"," ".$ar["class"]." ")){
					return $matches[0];
				}
			}
			
		}

        $LiveSite = JURI::root();

		$this->Includefiles();

		$cachefolder=$this->params->get("thumbspath","cache");

		$output=array();

		$ar["width"]=intval($ar["width"]);
		$ar["height"]=intval($ar["height"]);

		$ar=$this->check_imgparams($ar);
		
		if(empty($ar["src"])){
			return $matches[0];
		}else{
			$ar["src"]=$this->clean_url($ar["src"]);
		}

		if(empty($ar["width"]) AND empty($ar["height"])){
			return $matches[0];
		}
	
		if(isset($ar["class"])){
			$ar["class"].=" ".$this->params->get("picclass","thumb");
		}else{
			$ar["class"]=$this->params->get("picclass","thumb");	
		}
		$ar["class"]=trim($ar["class"]);

		$mode=$this->checkmode_from_class($ar["class"].$this->params->get("imgmode","equal"));

		if(!empty($ar["ext"])){	
			$ar["src"]=$ar["ext"];			
		}
		
		$url_array = parse_url($ar["src"]);

		$ar["src"]=str_replace(JURI::base(true),"",$ar["src"]);
		$ar["href"]=$this->combine_path(JURI::base(true),$ar["src"]);

	
		if(!empty($ar["ext"])){
			$info=@getimagesize($ar["ext"]);
		}else{
			#$info=@getimagesize(JPath::clean(JPATH_ROOT.DS.$ar["src"]));	
			$info=@getimagesize(JPath::clean(JPATH_ROOT.DS.urldecode($ar["src"])));			
		}
		if(!$info){
			$this->_loadLanguage();
			$this->set_error("ERROR-".$ar["src"],JText::sprintf('IMAGESIZER_ERR_ACCESS',JPath::clean($ar["src"]))); 
		}


		$ar=$this->calc_size($ar,$info);

		if($ar["width"]==$info[0] AND $ar["height"]==$info[1]){
			return $matches[0];
		}

		if($info[0]<2 AND $info[1]<2){
			return $matches[0];
		}

		if(isset($this->article->id)){
			$id=intval($this->article->id);
		}else{
			$id=0;
		}
		
		if($id==0){	$id="i".JRequest::getINT("Itemid");	}

		if(!empty($ar["ext"])){
			if($this->params->get("urldecode",1)==1){
				$file=urldecode($ar["ext"]);	
			}else{
				$file=$ar["ext"];
			}
		}else{		
			if($this->params->get("urldecode",1)==1){
				$file=urldecode($ar["src"]);	
			}else{
				$file=$ar["src"];
			}
		}

		$maxx=$this->params->get("maxsizex",800);
		$maxy=$this->params->get("maxsizey",800);
		$chmod=$this->params->get('chmod',"0775");

		if($info[0]>$maxx or $info[1]>$maxy){	
			$maxfile = $this->_get_imagesrc($file,$maxx,$maxy,"big",false,$cachefolder,$chmod);
		}else{
			$maxfile=$file;
		}

		if(empty($ar["ext"])){	 
			$file=JPath::clean(JPATH_ROOT.DS.$file);
		}

		$tmp=$file;

		$thumbfile = $this->_get_imagesrc($file,$ar["width"],$ar["height"],$mode,false,$cachefolder,$chmod);
		
		if($mode != "equal" and $mode !="zoom"){
			unset($ar["width"],$ar["height"]);
		}


		$temp_src=$ar["src"];

#		$ar["src"]=str_replace('\\',"/",$thumbfile);
#		$ar["href"]=JURI::base(true).$maxfile;

		$ar["src"]=str_replace('\\',"/",$thumbfile);
		$ar["href"]=$this->combine_path(JURI::base(true),$maxfile);


		#if(strlen($ar["href"])>1){
		#	if(substr($ar["href"],-1,1)!="/"){$ar["href"].="/";}		
		#}
		

		if(preg_match("/ nolightbox /i"," ".$ar["class"]." ")){
			$sharpit=true;
		}

		if($sharpit==true){
			$output=$this->onlythumb($ar,$img);
			if(substr($temp_src,0,1)=="/"){
				$temp_src=substr($temp_src,1);
			}
			$output=str_replace($temp_src,$ar["src"],$matches[0]);
			$output = preg_replace("/<img /i","<#img ",	$output );
		}else{
			$output=ImageSizer_addon_GetImageHTML($ar,$img,$this);				
		}
			
		unset($img);

        return $output;
		
	}

	private function get_errors(){
		if(count($this->errors)>0){
			foreach($this->errors as $k => $err){
				JError::raiseNotice($k,$err);		
			}
			$this->errors=array();			
		}
		return true;
		
	}

	private function set_error($id,$error){
		$id=JApplication::getHash($id);
		$this->errors[$id]=$error;
		
	}

	private function get_ReadmoreImageHTML($ar=array(),$img){

		$output=plgSystemimagesizer::make_img_output($ar);

		if(isset($ar["title"])){
			$title=' title="'.$ar["title"].'"';
		}else{
			$title="";
		} 

		$output='<a class="'.trim($this->params->get("linkclass","linkthumb")).'" target="_self" title="'.$ar["title"].'" href="'.$ar["href"].'"><img '.$output.' /></a>';	
	
		return $output;
		
	}

	private function onlythumb(&$ar,&$img){

		$output=plgSystemimagesizer::make_img_output($ar,true);
			
		return $output;
	
	}

	public function make_img_output($ar,$protect=false){

		$output=array();

		foreach($ar as $key => $value){
		 
		 	if(trim($value)!=""){
		 	 
				switch($key){
					
					case 'href':
					case 'owidth':
					case 'oheight':
					break;
					
					default:
					$output[]=$key.'="'.$value.'"';
					break;
				}
			 
			}
		}
		$output=implode(" ",$output);

		return $output;
	}

	public function make_arrays($matches,$regex='/([a-zA-Z0-9._-]+)=[\'\"](.*?)[\'\"]/'){
 			
 		$ar=array();
 		$matches2=array();
 
        preg_match_all($regex, $matches, $matches2);
				
        foreach($matches2[1] as $key => $value) {
            $value=trim($value);
            if (isset($ar[strtolower($value)])){
				$value=strtolower($value);
			}
            $ar[$value]=$matches2[2][$key];
        }
        
 		if (isset($ar["style"])){
			$ar2=plgSystemimagesizer::Get_WH_From_Style($ar["style"]);
			if (isset($ar2["width"])){$ar["width"]=$ar2["width"];}
			if (isset($ar2["height"])){$ar["height"]=$ar2["height"];}
			unset($ar2);
		}       
		
 		if (isset($ar["width"])){	
        	$ar["width"]=intval($ar["width"]);
        }else{
			$ar["width"]="";
		}
    
 		if (isset($ar["height"])){	
        	$ar["height"]=intval($ar["height"]);
        }else{
			$ar["height"]="";
		}
        
		return $ar;
	}


	private function Get_WH_From_Style($style){
		$style.=";";
		
		$matches=array();
		$ar=array();

		$regex='/(border-width|width|height):(.*?)(\;)/i';
 		preg_match_all($regex, $style, $matches);

		foreach($matches[1] as $key => $value) {
			if (isset($matches[2][$key])){
			 	$matches[2][$key]=trim($matches[2][$key]);
			 	if(substr($matches[2][$key],-1,1)!="%"){
					$k=strtolower(trim($value));
					$ar[$k]=trim($matches[2][$key]);
				}
			}
		}		
	
		return $ar;
	}



	private function match_to_params($match){

		$ar=array();
		$ar["style"]="";
		$m=array();
		$str="";
		
		preg_match_all('/(.*?)=(.*?)[\'\"](.*?)[\'\"]/', $match, $m);	
		
		if (count($m[1])>0){
			foreach($m[1] as $key => $value) {
				$ar[strtolower(trim($value))]=$m[3][$key];
			   	$str.=strtolower(trim($value))."=".$m[3][$key]."\n";
			   
			}
		}
		
		preg_match_all("/(.*?)=(.*?)[\'\"](.*?)[\'\"]/", $match, $m);

		if ($ar["style"]!=""){
			$b=plgSystemimagesizer::Get_WH_From_Style($ar["style"]);
			if(count($b)>0){
				foreach($b as $key => $value) {
				 $m[1][$key]=$key;
				 $m[3][$key]=$value;
				}				
			}
		}

		if (count($m[1])>0){
			foreach($m[1] as $key => $value) {
			  	 $ar[strtolower(trim($value))]=$m[3][$key];
				 $str.=strtolower(trim($value))."=".$m[3][$key]."\n";
			}

		}

		$params = new JParameter($str);
		$params->img_data=$ar;

		return $params;  		
	}



	public function get_folderandfile(&$file,$width=0,$height=0,$modus="big",$cachefolder="cache",$chmod=0777){

		$l=strlen(JPATH_SITE);
		if(substr($file,0,$l)==JPATH_SITE){
			$file=substr($file,$l);
		}
		$temp=substr($file,0,1);
		if($temp=="/" or $temp=="\\"){
			$file=substr($file,1);
		}
		
		
		$typename = substr(strrchr($file,'.'),1);
		
	 	$newfile=JApplication::getHash($file.$width."x".$height."-".$modus).".".$typename;
		$c=substr(strtolower($newfile),1,1);
		$c=$cachefolder.DS.$c;

		if(!file_exists(JPATH_SITE.DS.$c)){
		 	jimport('joomla.filesystem.folder'); 
			jimport('joomla.client.helper');

			$FTPOptions=JClientHelper::getCredentials('ftp');
			if($FTPOptions['enabled']==1){
			 	$chmod=0777;
			}else{
				$chmod=base_convert(intval($chmod), 8, 10);
			}

			if(isset($this->params)){
				if($this->params->get("jfile",1)==1){
			        if(!JFolder::create(JPATH_SITE.DS.$c,$chmod)){	 
			 			$this->_loadLanguage();
						$this->set_error("ERROR-".JPath::clean($c),JText::sprintf('IMAGESIZER_ERR_ACCESS',JPath::clean($c))); 			 
					}
				}else{				
					if(!@mkdir(JPATH_SITE.DS.$c)){
			 			$this->_loadLanguage();
						$this->set_error("ERROR-".JPath::clean($c),JText::sprintf('IMAGESIZER_ERR_ACCESS',JPath::clean($c))); 			 
					}				
				}
			}else{
				@mkdir(JPATH_SITE.DS.$c);
			}

		}
		/*
		if($checkcache>0){
			jimport('joomla.filesystem.folder');
			jimport('joomla.filesystem.file');			
			$files	= JFolder::files($c, "|.jpg|.gif|.png",false,true);	
			foreach($files as $i => $file){
				$t= round((time()-filectime($file))/60);
				if($t<$checkcache){
					unset($files[$i]);
				}
						
			}

			if(JFile::delete($files)){
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::sprintf('IMAGESIZER_X_IMAGES_DELETED',count($files)));	
			}	
	
		}
		*/		
		
		
		$newfile=$c.DS.$newfile;		
		
		return $newfile;
	}

	private function _loadLanguage($extension = '', $basePath = JPATH_ADMINISTRATOR)
	{
		if(defined("IMAGESIER_LANG_LOAD")){
			return;
		}
		$lang = JFactory::getLanguage();
 		$lang->load("plg_system_imagesizer.sys",JPATH_ADMINISTRATOR);
		define("IMAGESIER_LANG_LOAD",1);

	}

	private function _get_imagesrc($file,&$width=0,&$height=0,$modus="big",$updatecache=false,$cachefolder="cache",$chmod=0777){
		
		if($width>0 and $height==0){
			$height=$width;
		}elseif($width==0 and $height>0){
			$width=$height;
		}		
		
		
		$this->_loadLanguage();

		$newfile=plgSystemimagesizer::get_folderandfile($file,$width,$height,$modus,$cachefolder,$chmod);			

		if(!file_exists(JPATH_SITE.DS.$newfile) or $updatecache==true){
		 	if($width>0 and $height>0){
					
				$temp=plgSystemimagesizer::get_folderandfile($file,$width,$height,$modus,$cachefolder,$chmod);
					
				if (substr($file,0,7)=="http://"){			
					if(@copy($file,$temp)){
						$file=$temp;
					}else{
						return $file;
					}
				}else{
					if($this->params->get("jfile",1)==1){
	                    jimport('joomla.filesystem.file');
	
	                    if( JFile::copy(JPATH_SITE.DS.$file, JPATH_SITE.DS.$temp)){
							$file=$temp;
						}else{
							return $file;
						}
					}else{
						if(@copy($file,$temp)){
							$file=$temp;
						}else{
							return $file;
						}
					}

				}

				include_once(JPATH_SITE.DS."plugins".DS."system".DS."imagesizer".DS.'libraries'.DS."redim_img.php");

				$img= new PicEdit(JPATH_SITE.DS.urldecode($file));
				$img->create($width,$height,$modus,JPATH_SITE.DS.$newfile);
		        if (!empty($chmod)){
		         #base_convert(intval($chmod), 8, 10)
		          @chmod(JPATH_SITE.DS.$newfile,base_convert(intval($chmod), 8, 10));
		        }	
				if(count($img->err)>0){
		 			$this->_loadLanguage();
					foreach($img->err as $temp){
						$this->set_error("ERR-".$newfile,JText::sprintf("IMAGE_CREATE_ERROR",$newfile));
					} 
				}else{
					$this->created_pics++;
					$width=$img->new_width;
					$height=$img->new_height;
				}				
							
				unset($img);

				
			}else{
				$newfile=$file;
			}
		}

		return $newfile;
		
	}


	public function get_imagesrc($file,$width=0,$height=0,$modus="big",$updatecache=false,$cachefolder="cache",$chmod="0777"){
		
		if($width>0 and $height==0){
			$height=$width;
		}elseif($width==0 and $height>0){
			$width=$height;
		}
	
		$newfile=plgSystemimagesizer::get_folderandfile($file,$width,$height,$modus,$cachefolder,$chmod);

		if(!file_exists(JPATH_SITE.DS.$newfile) or $updatecache==true){
		 	if($width>0 and $height>0){
				include_once(JPATH_SITE.DS."plugins".DS."system".DS."imagesizer".DS.'libraries'.DS."redim_img.php");
				$img= new PicEdit(JPATH_SITE.DS.$file);
				$img->create($width,$height,$modus,JPATH_SITE.DS.$newfile);
		        if ($chmod!=0){
		          @chmod(JPATH_SITE.DS.$newfile,base_convert($chmod, 8, 10));
		        }				
				unset($img);			
			}else{
				$newfile=$file;
			}
		}

		return $newfile;
		
	}

}
