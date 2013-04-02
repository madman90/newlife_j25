<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

?>
<div class="blog<?php echo $this->pageclass_sfx;?>">
    <?php if ($this->params->get('show_page_heading')) : ?>
    <h2>
        <?php echo $this->escape($this->params->get('page_heading')); ?>
    </h2>
    <?php endif; ?>

    <?php
    $introcount=(count($this->intro_items));
    $counter=0;
    ?>
    <?php if (!empty($this->intro_items)) : ?>
    <ul class="small_groups">
        <?php foreach ($this->intro_items as $key => &$item) : ?>
        <li>
            <?php
            $this->item = &$item;
            echo $this->loadTemplate('item');
            ?>
        </li>
        <?php endforeach; ?>
    </ul>

    <?php endif; ?>

    <?php if (!empty($this->link_items)) : ?>

    <?php echo $this->loadTemplate('links'); ?>

    <?php endif; ?>


    <?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
    <div class="pagination">
        <?php  if ($this->params->def('show_pagination_results', 1)) : ?>
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>

        <?php endif; ?>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
    <?php  endif; ?>

</div>
