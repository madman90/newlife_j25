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




    { ?>


<!--           html view start-->


        <?php
        $twitter_show = json_decode(file_get_contents ('https://api.twitter.com/1/users/show.json?screen_name=newlife_ck_ua'));
        $twitter_timeline = json_decode(file_get_contents('https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=newlife_ck_ua&count=2'));
//        echo "\n" . "ссылка на картинку: " . $twitter_show->profile_image_url_https;
//        echo "\n" . "твит: " . $twitter_timeline[0]->text;
//        echo "\n" . "время: " . $twitter_timeline[0]->created_at;
//        echo "\n" . "tweet_id: " . $twitter_timeline[0]->id_str;
        $twitter_timeline_array = explode(" ", $twitter_timeline[0]->text);
//        формируем ссылку непосредственно в твите
        $tweet_link = (array_pop($twitter_timeline_array));
        $tweet = implode(" ",($twitter_timeline_array));
//        формируем отображение даты создания твита
        $date_array = explode(" ",$twitter_timeline[0]->created_at);
        $month_array = array ('Jan' => 'січня' , 'Feb' => 'лютого' , 'Mar' => 'березня' , 'Apr' => 'квітня' ,
            'May' => 'травня' , 'Jun' => 'червень' , 'Jul' => 'липня' , 'Aug' => 'серпня' , 'Sep' => 'вересня' ,
            'Oct' => 'жовтня' , 'Nov' => 'листопада' , 'Dec' => 'грудня');
        $day_of_week_array = array('Sun'=>'Неділя','Mon'=>'Понеділок','Tue'=>'Вівторок','Wed'=>'Середа',
            'Thu'=>'Четвер','Fri'=>'П\'ятниця','Sat'=>'Субота' );
        $day_of_week = $day_of_week_array[$date_array[0]];
        $day= $date_array[2];
        $month= $month_array[$date_array[1]];
        $year = $date_array[5];


        ?>

        <ul class="quote">
            <li>
                <img class="avatar" src="<?php echo $twitter_show->profile_image_url_https;?>">
                <p class="date"><?php echo $day_of_week?>, <br><?php echo $day. " " . $month. " " . $year;?></p>
            </li>
            <li class="last_tweet">
                <p><?php echo $tweet;?> <a href="<?php echo $tweet_link;?>"><?php echo $tweet_link;?></a></p></p>
            </li>
        </ul>
        <a class = "tw_link" href="https://twitter.com/<?php echo $params->get('username')?>">слідкуйте за нами в twitter</a>

<!--            html view end-->




<?php }




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
