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

<jdoc:include type="modules" name="debug" style="rounded"/>
<jdoc:include type="modules" name="position-0" style="rounded"/>
<jdoc:include type="modules" name="position-1" style="rounded"/>
<jdoc:include type="modules" name="position-2" style="rounded"/>
<jdoc:include type="modules" name="position-3" style="rounded"/>
<jdoc:include type="modules" name="position-4" style="rounded"/>
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
<jdoc:include type="component" />




</body>


</html>
