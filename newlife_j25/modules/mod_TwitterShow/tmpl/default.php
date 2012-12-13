<?php



defined('_JEXEC') or die('Restricted access');

$moduleclass_sfx=$params->get('moduleclass_sfx');

if($params->get('toptweets') == 'yes')
{
$toptweets="true";
}
else
{
$toptweets="false";
}


if($params->get('scrollbar') == 'yes')
{
$scrollbar="true";
}
else
{
$scrollbar="false";
}





if($params->get('loop') == 'yes')

{

$loop="true";

}

else

{

$loop="false";



}





if($params->get('live') == 'yes')

{

$live="true";

}

else

{

$live="false";



}



if($params->get('hashtags') == 'yes')

{

$hashtags="true";

}

else

{

$hashtags="false";



}



if($params->get('avatars') == 'yes')

{

$avatars="true";

}

else

{

$avatars="false";



}



if($params->get('timestamp') == 'yes')

{

$timestamp="true";

}

else

{

$timestamp="false";



}





if($params->get('auto') == 'yes')

{

$width="'auto'";

}

else

{

$width=$params->get('width');



}

?>



<div class="joomla_sharethis<?php echo $moduleclass_sfx?>">



<?php if( $params->get('widget_type') == 'profile') { ?>



<?php if( $params->get('display_with') == 'html')



//    html view start

    { ?>

<!--        <script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>-->
<!--        <script type="text/javascript" src="http://twitter.com/statuses/user_timeline/--><?php //echo $params->get('username')?><!--.json?callback=twitterCallback2&count=--><?php //echo $params->get('rpp') ?><!--"></script>-->
        <script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
        <script type="text/javascript" src="http://twitter.com/statuses/user_timeline/newlife_ck_ua.json?callback=twitterCallback2&count=5"></script>


        <h3>Twitter</h3>
        <ul id="twitter_update_list">
        </ul>

<!--        <div class="quote">-->
<!--            <div class="date">-->
<!--                <p>Вівторок,</p>-->
<!--                <p>13 вересня 2011 from Bible</p>-->
<!--             <ul id="twitter_update_list">-->
<!---->
<!--             </ul>-->
<!--            </div>-->
<!--        </div>-->





        <a class = "tw_link" href="#">слідкуйте за нами в twitter</a>


<!--<div id="twitter_div">-->
<!---->
<!---->
<!---->
<!--<ul id="twitter_update_list"></ul>-->
<!---->
<!---->
<!---->
<!--<a href="http://twitter.com/--><?php //echo $params->get('username')?><!--" id="twitter-link" style="display:block;text-align:right;">follow me on Twitter</a>-->
<!---->
<!---->
<!---->
<!--</div>-->



<!--<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>-->



<!--<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/--><?php //echo $params->get('username')?><!--.json?callback=twitterCallback2&amp;count=--><?php //echo $params->get('rpp') ?><!--"></script>-->



<?php }




//    html view end
    else {?>



<script src="http://widgets.twimg.com/j/2/widget.js"></script>



<script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: <?php echo $params->get('rpp') ?>,
  interval: <?php echo $params->get('interval') * 1000 ?>,
  width: <?php echo $width; ?>,
  height: <?php echo $params->get('height') ?>,
  theme: {
    shell: {
      background: '<?php echo $params->get('shell_background') ?>',
      color: '<?php echo $params->get('shell_color') ?>'
    },
    tweets: {
      background: '<?php echo $params->get('tweet_background') ?>',
      color: '<?php echo $params->get('tweet_color') ?>',
      links: '<?php echo $params->get('links_color') ?>'
    }
  },
  features: {
    scrollbar: <?php echo $scrollbar; ?>,
    loop: <?php echo $loop; ?>,
    live: <?php echo $live; ?>,
    hashtags: <?php echo $hashtags; ?>,
    timestamp: <?php echo $timestamp; ?>,
    avatars: <?php echo $avatars; ?>,
    behavior: 'default'
  }
}).render().setUser('<?php echo $params->get('username')?>').start();
</script>
<?php }
}
?>

<?php if( $params->get('widget_type') == 'search') { ?>

<script src="http://widgets.twimg.com/j/2/widget.js"></script>


<script>
new TWTR.Widget({
  version: 2,
  type: 'search',
   search: '<?php echo addslashes($params->get('search_query')) ?>',
  rpp: <?php echo $params->get('rpp') ?>,
  interval: <?php echo $params->get('interval') * 1000 ?>,
  title: '<?php echo addslashes($params->get('search_title')) ?>',
  subject: '<?php echo addslashes($params->get('search_subject')) ?>',
  width: <?php echo $width; ?>,
  height: <?php echo $params->get('height') ?>,
  theme: {
    shell: {
      background: '<?php echo $params->get('shell_background') ?>',
      color: '<?php echo $params->get('shell_color') ?>'
    },
    tweets: {
      background: '<?php echo $params->get('tweet_background') ?>',
      color: '<?php echo $params->get('tweet_color') ?>',
      links: '<?php echo $params->get('links_color') ?>'
    }
  },
  features: {
    scrollbar: <?php echo $scrollbar; ?>,
    loop: <?php echo $loop; ?>,
    live: <?php echo $live; ?>,
    hashtags: <?php echo $hashtags; ?>,
    timestamp: <?php echo $timestamp; ?>,
    avatars: <?php echo $avatars; ?>,
	toptweets:<?php echo $toptweets; ?>,
    behavior: 'default'
  }
}).render().start();
</script>

<?php } ?>

<!--</div>-->
<!--<div align="right" style="color:#999;margin-bottom:3px;font-size:9px">E2: <a target="_blank" class="external" title="empirepromos.com" href="http://www.empirepromos.com"><span style="color:#999;margin-bottom:3px;font-size:9px" >Promotional Products</span></a></div>-->
