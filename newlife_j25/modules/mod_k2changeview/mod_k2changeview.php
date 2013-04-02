<?php

defined('_JEXEC') or die('Restricted access');

$session = JFactory::getSession();
$view_mode  = $session->get('view_mode', 'short');
?>
    <ul class="switch_buttons">

        <li class="full <?php if ($view_mode == 'full'):?>active<?php endif; ?>">
            <?php if ($view_mode == 'full'):?>
                <span>long</span>
            <?php else: ?>
                <a href="/index.php?option=com_k2ajaxsearch&task=change_view">long</a>
            <?php endif; ?>
        </li>
        <li class="short <?php if ($view_mode == 'short'):?>active<?php endif; ?>">
            <?php if ($view_mode == 'short'):?>
            <span>short</span>
            <?php else: ?>
            <a href="/index.php?option=com_k2ajaxsearch&task=change_view">short</a>
            <?php endif; ?>
        </li>
    </ul>




