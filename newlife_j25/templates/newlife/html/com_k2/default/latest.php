<?php
/**
 * @version		$Id: latest.php 1812 2013-01-14 18:45:06Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

?>
<?php if (($this->params->get('page_title')) =='Головна'):  ?>

<h2 class="late">Оновлення</h2>
<!--<p>Вітальний текст Вітальний текст Вітальний текст Вітальний текст Вітальний текст Вітальний текст Вітальний текст Вітальний текст Вітальний текст Вітальний текст Вітальний текст Вітальний текст Вітальний текст Вітальний текст Вітальний текст</p>-->
<?php else: ?>
<?php if($this->params->get('show_page_title')): ?>
    <!-- Page title -->
    <div class="componentheading<?php echo $this->params->get('pageclass_sfx')?>">
        <?php echo $this->escape($this->params->get('page_title')); ?>
    </div>
    <?php endif; ?>
<?php endif; ?>
<!-- Start K2 Latest Layout -->
<div id="k2Container" class="latestView<?php if($this->params->get('pageclass_sfx')) echo ' '.$this->params->get('pageclass_sfx'); ?>">

        <ul class="latestItemList">
        <?php foreach($this->blocks as $key=>$block): ?>
            <!-- Start Items list -->


                <?php if($this->params->get('latestItemsDisplayEffect')=="first"): ?>

                <?php foreach ($block->items as $itemCounter=>$item): K2HelperUtilities::setDefaultImage($item, 'latest', $this->params); ?>
                    <li>
                    <?php if($itemCounter==0): ?>
                        <?php $this->item=$item; echo $this->loadTemplate('item'); ?>
                        <?php else: ?>
                        <h2 class="latestItemTitleList">
                            <?php if ($item->params->get('latestItemTitleLinked')): ?>
                            <a href="<?php echo $item->link; ?>">
                                <?php echo $item->title; ?>
                            </a>
                            <?php else: ?>
                            <?php echo $item->title; ?>
                            <?php endif; ?>
                        </h2>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>

                <?php else: ?>

                <?php foreach ($block->items as $item): K2HelperUtilities::setDefaultImage($item, 'latest', $this->params); ?>

                    <?php if($item->categoryalias == 'video'): ?>
                    <li class="video">
                        <?php $this->item=$item; echo $this->loadTemplate('item'); ?>
                    </li>
                    <?php elseif(($item->categoryalias) == 'audio'): ?>
                    <li class="audio">
                       <?php $this->item=$item; echo $this->loadTemplate('item'); ?>
                    </li>
                        <?php elseif(($item->categoryalias) == 'whats-new'): ?>
                    <li class="whats_new">
                        <?php $this->item=$item; echo $this->loadTemplate('item'); ?>
                    </li>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php endif; ?>

            <!-- End Item list -->




            <?php if(($key+1)%($this->params->get('latestItemsCols'))==0): ?>

            <?php endif; ?>

        <?php endforeach; ?>
        </ul>

</div>
<!-- End K2 Latest Layout -->
