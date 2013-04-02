<?php

defined('_JEXEC') or die('Restricted access');

$session = JFactory::getSession();
$view_mode  = $session->get('view_mode', 'short');

?>
<ul>
    <li class="short <?php if ($view_mode == 'short'):?>active<?php endif; ?>">
        <a href="/index.php?option=com_k2ajaxsearch&task=change_view"></a>
    </li>
    <li class="full <?php if ($view_mode == 'full'):?>active<?php endif; ?>">
        <a href="/index.php?option=com_k2ajaxsearch&task=change_view"></a>
    </li>
</ul>