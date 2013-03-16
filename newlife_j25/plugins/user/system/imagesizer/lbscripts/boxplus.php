<?PHP
/*------------------------------------------------------------------------
# mooimagelayer.php for PLG - SYSTEM - IMAGESIZER
# ------------------------------------------------------------------------
# author    reDim - Norbert Bayer
# copyright (C) 2011 redim.de. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.redim.de
# Technical Support:  Forum - http://www.redim.de/kontakt/
-------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Restricted access' );

JHTML::_('behavior.mootools');

$path="plugins/system/imagesizer/lbscripts/boxplus/";

$lang = JFactory::getLanguage();
#$l=substr($lang->getTag(),0,2);
$l=$lang->getTag();
$document   = JFactory::getDocument();


$document->addStyleSheet($path."css/".'boxplus.css','text/css',"screen");
$document->addStyleSheet($path."css/".'boxplus.prettyphoto.css','text/css',"screen");
$document->addStyleSheet($path."css/".'boxplus.prettyphoto.ie8.css','text/css',"screen");

if(!isset($document->_scripts["/plugins/content/boxplus/js/boxplus.min.js"])){
	$document->addScript($path."js/".'boxplus.js');	
}

if(!isset($document->_scripts["/plugins/content/boxplus/js/boxplus.lang.min.js?lang=".$l])){
	$document->addScript($path."js/".'boxplus.lang.js?lang='.$l);	
}

$java='
boxplus.autodiscover(true,{"theme":"prettyphoto","autocenter":true,"autofit":true,"slideshow":0,"loop":false,"captions":"bottom","thumbs":"inside","duration":250,"transition":"sine","contextmenu":true});
';

$document->addScriptDeclaration($java);

unset($path);


function ImageSizer_addon_GetImageHTML(&$ar,&$img,&$imagesizer){

	$output=plgSystemimagesizer::make_img_output($ar);

	$x=explode("/",$ar["href"]);
	$c=count($x)-1;
	$x[$c]=rawurlencode($x[$c]);
	$x=implode("/",$x);

	if(isset($ar["title"])){
		$title=' title="'.$ar["title"].'"';
	}else{
		$title="";
	} 
	
	$id=0;
	
	if(isset($imagesizer->article->id)){
		$id=$imagesizer->article->id;
	}
	
	$output='<a class="'.trim($imagesizer->params->get("linkclass","linkthumb")."").'" rel="boxplus-images" target="_blank"'.$title.' href="'.$x.'"><img '.$output.' /></a>';	

	return $output;

}


