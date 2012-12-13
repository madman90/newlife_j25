<?php


defined('_JEXEC') or die('Restricted access'); 
ini_set('display_errors',0);
$doc =& JFactory::getDocument();
$show_jquery=$params->get('show_jquery');
$moduleid=$module->id;
if($params->get('avatar') == 'yes')

{

$avatar="true";

}



else

{



$avatar="false";



}





if($params->get('anchors') == 'yes')

{

$anchors="true";

}

else

{

$anchors="false";

}





if($params->get('bird') == 'yes')

{

$bird="true";

}

else

{

$bird="false";

}



if($params->get('animation') == 'fade')

{

$animation="0";

}

else

{

$animation="1";

}









$jspath=JUri::root()."modules/mod_flying_tweets/js/jquery.twitter.search.js";

$jspath2=JUri::root()."modules/mod_flying_tweets/js/jquery.corner.js";

$jspath3=JUri::root()."modules/mod_flying_tweets/js/chili-1.7.pack.js";
$jspath4=JUri::root()."modules/mod_flying_tweets/js/jquery-1.4.2.js";


?>



<style type="text/css"> 



img.twitterSearchProfileImg{ width:<?php echo $params->get('imgwidth'); ?>px; height:<?php echo $params->get('imgheight'); ?>px; float:left; margin:5px;}

</style> 



<div class="joomla_flyingtweets<?php echo $moduleclass_sfx?>">



<?php if($show_jquery=="yes")
{?>
<script type="text/javascript" src="<?php echo $jspath4;?>"></script> 
<?php } ?>


<script type="text/javascript" src="<?php echo $jspath2;?>"></script> 

<script type="text/javascript" src="<?php echo $jspath3;?>"></script> 

<script type="text/javascript" src="<?php echo $jspath;?>"></script> 

<script type="text/javascript"> 

 

$(document).ready(function() {

	$('h1.title').corner('bevelfold tr 12px');

	

$('#twitter<?php echo $moduleid; ?>').twitterSearch({ 

    term:  '<?php echo addslashes($params->get('term')); ?>', 

    title: '<?php echo addslashes($params->get('title')); ?>', 

    titleLink: '<?php echo addslashes($params->get('titlelink')); ?>', 

    birdLink:  '<?php echo addslashes($params->get('birdlink')); ?>', 

	animOut: { opacity: <?php echo $animation; ?> }, 

	avatar:  <?php echo $avatar; ?>, 

    anchors: <?php echo $anchors; ?>, 

    bird:    <?php echo $bird; ?>, 

    colorExterior: '<?php echo addslashes($params->get('extcolor')); ?>', 

    colorInterior: '<?php echo addslashes($params->get('intcolor')); ?>', 

    pause:   true, 

    time:    false, 

    timeout: <?php echo $params->get('timeout'); ?> * 1000 ,

    css: {  

      img: {  

        width: '<?php echo $params->get('imgwidth'); ?>px', height: '<?php echo $params->get('imgheight'); ?>px' }  

      } 

    

});	

	

	

});

 

</script> 

<div id="twitter<?php echo $moduleid; ?>"  style="height: <?php echo addslashes($params->get('height')); ?>px; margin: auto; width:<?php echo addslashes($params->get('width')); ?>px; "></div>


<!-- Joomla Twitter END -->

</div>
<div align="right" style="color:#999;margin-bottom:3px;font-size:9px">By <a target="_blank" class="external" title="www.crayfishstudios.com" href="http://www.crayfishstudios.com"><span style="color:#999;margin-bottom:3px;font-size:9px" >Crayfishstudios.com</span></a></div>
