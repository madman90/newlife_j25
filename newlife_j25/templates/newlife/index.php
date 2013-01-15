<?php

// No direct access.
defined('_JEXEC') or die;

$app= JFactory::getApplication();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >

<head>

    <jdoc:include type="head" />
<!--    <link rel="stylesheet" href="--><?php //echo $this->baseurl ?><!--/templates/--><?php //echo $this->template; ?><!--/css/reset.css" type="text/css" />-->
    <link rel="stylesheet" href="/templates/system/css/system.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />

</head>


<body>
<div id="wrapper">
    <div class="inner_wrapper">
        <div id="header">
            <h1 class="logo"><a href="#">Нове життя</a></h1>

<!--            <ul class="navigation">-->
                <jdoc:include type="modules" name="position-1" style="rounded"/>
<!--            </ul>-->

<!--            <ul class="slideshow">-->
                <jdoc:include type="modules" name="position-2" style="rounded"/>

<!--            </ul>-->
        </div>

        <div class="guidance">
            <p>"Служити б радий, та прислужувати тошно" А.Грибоєдов</p>
            <h2>Вітаємо на сайті</h2>
            <ul class="social_links">
                <li class="fb"><a href="#">Facebook</a></li>
                <li class="tw"><a href="#">Twitter</a></li>
            </ul>
        </div>
        <div id="content">
            <div class="sidebar">
                <jdoc:include type="modules" name="position-3" style="rounded"/>

            </div>


            <div class="container">
                <jdoc:include type="component" />
                <jdoc:include type="modules" name="position-4" style="rounded"/>

            </div>
        </div>

        <div id="footer">
            <p>2011, &copy; Церква «Нове життя» <a href="#">Jason-designer</a></p>

        </div>
    </div>
</div>

<jdoc:include type="modules" name="position-5" style="rounded"/>
<jdoc:include type="modules" name="position-6" style="rounded"/>
<jdoc:include type="modules" name="position-7" style="rounded"/>
<jdoc:include type="modules" name="position-8" style="rounded"/>
<jdoc:include type="modules" name="position-9" style="rounded"/>
<jdoc:include type="modules" name="position-10" style="rounded"/>
<jdoc:include type="modules" name="position-11" style="rounded"/>
<jdoc:include type="modules" name="position-12" style="rounded"/>
<jdoc:include type="modules" name="position-13" style="rounded"/>
<jdoc:include type="modules" name="position-14" style="rounded"/>
<jdoc:include type="modules" name="debug" style="rounded"/>

<?//
//
//echo  mail ("madman-90@mail.ru","test message",
//    "test message","From:no-reply@gmail.com");
//
//?>


</body>


</html>
