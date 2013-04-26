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
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/k2.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/html/jw_allvideo/Classic/css/template.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/sp_quickcontact.css" type="text/css" />

</head>


<body>

    <div id="wrapper">
        <div class="header_wrap">
                <div id="header">
                    <h1 class="logo"><a href="#">Нове життя</a></h1>
                    <jdoc:include type="modules" name="position-1" style="xhtml"/>
                    <jdoc:include type="modules" name="position-2" />
                </div>
        </div><!--header_wrap-->
        <div class="content_wrap">
            <div class="logo_bg">
                <div class="title_navigation"></div>
                <div id="content">
                    <?php if(($app->input->get('option') == 'com_k2' && $app->input->get('view') == 'itemlist')):?>
                        <jdoc:include type="modules" name="position-10" />
                    <?php endif; ?>
                    <jdoc:include type="modules" name="position-11" />
                    <div class="sidebar">
                        <?php if ($app->input->get('option') == 'com_k2' && $app->input->get('view') == 'itemlist'): ?>
                            <jdoc:include type="modules" name="position-8" style="xhtml" />
                        <?php endif; ?>

                        <?php if (!($app->input->get('option') == 'com_k2' && $app->input->get('view') == 'itemlist' && $app->input->get('Itemid') == 106)): ?>
                           <jdoc:include type="modules" name="position-3" style="xhtml" />
                        <?php endif; ?>
                        <jdoc:include type="modules" name="position-4" style="xhtml" />
                    </div>
                    <div class="container">
                        <jdoc:include type="component" style="xhtml" />
                        <jdoc:include type="modules" name="position-5" style="xhtml" />
                        <jdoc:include type="modules" name="position-6" style="xhtml" />
                    </div>
                </div><!--content-->
                <div class="footer_wrap">
                        <div id="footer">
                            <p>2011, &copy; Церква «Нове життя»</p>
                            <span>Jason-designer</span>
                            <div class="geekhub_logo">
                                <span>&nbsp;</span>
                                <p>Powered by geekhub</p>
                            </div>

                        </div>
                </div><!--footer_wrap-->
            </div><!--logo_bg-->
        </div><!--content_wrap-->
    </div><!--wrapper-->


</body>


</html>
