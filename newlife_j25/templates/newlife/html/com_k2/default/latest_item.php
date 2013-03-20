<?php
/**
 * @version		$Id: latest_item.php 1812 2013-01-14 18:45:06Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

?>

<!-- Start K2 Item Layout -->


	<!-- Plugins: BeforeDisplay -->
	<?php echo $this->item->event->BeforeDisplay; ?>

	<!-- K2 Plugins: K2BeforeDisplay -->
	<?php echo $this->item->event->K2BeforeDisplay; ?>

    <?php if($this->item->categoryalias == 'video'): ?>

        <?php if($this->item->params->get('latestItemDateCreated')): ?>
        <!-- Date created -->
        <span class="latestItemDateCreated">
		    <?php echo JHTML::_('date', $this->item->created , 'j.m.Y'); ?>
	    </span>
        <?php endif; ?>
        <a class="video_link" href="<?php echo $this->item->link; ?>">&nbsp;</a>
        <?php if($this->params->get('latestItemVideo') && !empty($this->item->video)): ?>
        <!-- Item video -->
        <div class="latestItemVideoBlock">
            <span class="latestItemVideo<?php if($this->item->videoType=='embedded'): ?> embedded<?php endif; ?>"><?php echo $this->item->video; ?></span>
        </div>
        <?php endif; ?>



        <div class="latestItemBody">
          <?php if($this->item->params->get('latestItemTitle')): ?>
          <!-- Item title -->
          <h3 class="latestItemTitle">
            <?php if ($this->item->params->get('latestItemTitleLinked')): ?>
            <a href="<?php echo $this->item->link; ?>">
                <?php echo $this->item->title; ?>
            </a>
            <?php else: ?>
            <?php echo $this->item->title; ?>
            <?php endif; ?>
          </h3>
          <?php endif; ?>
            <!-- Plugins: AfterDisplayTitle -->
            <?php echo $this->item->event->AfterDisplayTitle; ?>

            <!-- K2 Plugins: K2AfterDisplayTitle -->
            <?php echo $this->item->event->K2AfterDisplayTitle; ?>

            <?php if($this->item->params->get('latestItemIntroText')): ?>
            <!-- Item introtext -->
            <div class="latestItemIntroText">
                <?php echo $this->item->introtext; ?>
            </div>
            <?php endif; ?>
        </div>
<!--        <span class="video_arrow"><a class="video_link_arrow" href="--><?php //echo $this->item->link; ?><!--">&nbsp;</a></span>-->
          <!-- Plugins: BeforeDisplayContent -->
          <?php echo $this->item->event->BeforeDisplayContent; ?>

          <!-- K2 Plugins: K2BeforeDisplayContent -->
          <?php echo $this->item->event->K2BeforeDisplayContent; ?>

          <!-- Plugins: AfterDisplayContent -->
          <?php echo $this->item->event->AfterDisplayContent; ?>

          <!-- K2 Plugins: K2AfterDisplayContent -->
          <?php echo $this->item->event->K2AfterDisplayContent; ?>


          <?php if($this->item->params->get('latestItemCategory') || $this->item->params->get('latestItemTags')): ?>
          <div class="latestItemLinks">

                <?php if($this->item->params->get('latestItemCategory')): ?>
                <!-- Item category name -->
                <div class="latestItemCategory">
                    <span><?php echo JText::_('K2_PUBLISHED_IN'); ?></span>
                    <a href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->name; ?></a>
                </div>
                <?php endif; ?>
          </div>
          <?php endif; ?>

          <!-- Plugins: AfterDisplay -->
          <?php echo $this->item->event->AfterDisplay; ?>

          <!-- K2 Plugins: K2AfterDisplay -->
          <?php echo $this->item->event->K2AfterDisplay; ?>
    <!-------------------------- audio -------------------------------------------------------------------------------->
    <?php elseif(($this->item->categoryalias) == 'audio'): ?>
        <!-- Plugins: BeforeDisplay -->
        <?php echo $this->item->event->BeforeDisplay; ?>

        <!-- K2 Plugins: K2BeforeDisplay -->
        <?php echo $this->item->event->K2BeforeDisplay; ?>


        <?php if($this->item->params->get('latestItemDateCreated')): ?>
            <!-- Date created -->
            <span class="latestItemDateCreated">
                <?php echo JHTML::_('date', $this->item->created , 'j.m.Y'); ?>
            </span>
            <?php endif; ?>
        <div class="latestItemBody">

            <?php if($this->item->params->get('latestItemTitle')): ?>
            <!-- Item title -->
            <h3 class="latestItemTitle">
                <?php if ($this->item->params->get('latestItemTitleLinked')): ?>
                <a href="<?php echo $this->item->link; ?>">
                    <?php echo $this->item->title; ?>
                </a>
                <?php else: ?>
                <?php echo $this->item->title; ?>
                <?php endif; ?>
            </h3>
            <?php endif; ?>
            <!-- Plugins: AfterDisplayTitle -->
            <?php echo $this->item->event->AfterDisplayTitle; ?>

            <!-- K2 Plugins: K2AfterDisplayTitle -->
            <?php echo $this->item->event->K2AfterDisplayTitle; ?>

            <?php if($this->params->get('latestItemVideo') && !empty($this->item->video)): ?>
            <!-- Item audio -->
            <div class="latestItemVideoBlock">
                <span class="latestItemVideo<?php if($this->item->videoType=='embedded'): ?> embedded<?php endif; ?>"><?php echo $this->item->video; ?></span>
            </div>
            <?php endif; ?>

            <?php if($this->item->params->get('latestItemIntroText')): ?>
            <!-- Item introtext -->
            <div class="latestItemIntroText">
                <?php echo $this->item->introtext; ?>
            </div>
            <?php endif; ?>
        </div>


            <!-- Plugins: BeforeDisplayContent -->
            <?php echo $this->item->event->BeforeDisplayContent; ?>

            <!-- K2 Plugins: K2BeforeDisplayContent -->
            <?php echo $this->item->event->K2BeforeDisplayContent; ?>

            <!-- Plugins: AfterDisplayContent -->
            <?php echo $this->item->event->AfterDisplayContent; ?>

            <!-- K2 Plugins: K2AfterDisplayContent -->
            <?php echo $this->item->event->K2AfterDisplayContent; ?>


            <?php if($this->item->params->get('latestItemCategory') || $this->item->params->get('latestItemTags')): ?>
                <div class="latestItemLinks">

                    <?php if($this->item->params->get('latestItemCategory')): ?>
                    <!-- Item category name -->
                    <div class="latestItemCategory">
                        <span><?php echo JText::_('K2_PUBLISHED_IN'); ?></span>
                        <a href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->name; ?></a>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Plugins: AfterDisplay -->
            <?php echo $this->item->event->AfterDisplay; ?>

            <!-- K2 Plugins: K2AfterDisplay -->
            <?php echo $this->item->event->K2AfterDisplay; ?>
        <!------------------------------------ whats new ---------------------------------------------------------------------->
    <?php elseif ($this->item->categoryalias == 'whats-new'): ?>
        <div class="news">
        <?php if($this->item->params->get('latestItemDateCreated')): ?>
            <!-- Date created -->
            <span class="latestItemDateCreated">
                    <?php echo JHTML::_('date', $this->item->created , 'j.m.Y'); ?>
                </span>
            <?php endif; ?>
<!--            <span class="img_icon">&nbsp;</span>-->
        </div><!--news-->

        <?php if($this->item->params->get('itemImage') && !empty($this->item->image)): ?>
        <!-- Item Image -->
            <div class="latestItemImageBlock">
               <a href="<?php echo $this->item->link; ?>" title="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>">
                  <img src="<?php echo $this->item->imageSmall; ?>" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" style="width:<?php echo $this->item->imageWidth; ?>px;height:100%;" />
               </a>

            </div>
        <?php endif; ?>
<!--        <pre>-->
<!--            --><?php //print_r($this->item); die();?>
<!--        </pre>-->
        <div class="latestItemBody">
            <?php if($this->item->params->get('latestItemTitle')): ?>
            <!-- Item title -->
            <h3 class="latestItemTitle">
                <?php if ($this->item->params->get('latestItemTitleLinked')): ?>
                <a href="<?php echo $this->item->link; ?>">
                    <?php echo $this->item->title; ?>
                </a>
                <?php else: ?>
                <?php echo $this->item->title; ?>
                <?php endif; ?>
            </h3>
            <?php endif; ?>
            <!-- Plugins: AfterDisplayTitle -->
            <?php echo $this->item->event->AfterDisplayTitle; ?>

            <!-- K2 Plugins: K2AfterDisplayTitle -->
            <?php echo $this->item->event->K2AfterDisplayTitle; ?>

            <?php if($this->item->params->get('latestItemIntroText')): ?>
            <!-- Item introtext -->
            <div class="latestItemIntroText">
                <?php echo $this->item->introtext; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Plugins: BeforeDisplayContent -->
        <?php echo $this->item->event->BeforeDisplayContent; ?>

        <!-- K2 Plugins: K2BeforeDisplayContent -->
        <?php echo $this->item->event->K2BeforeDisplayContent; ?>

        <!-- Plugins: AfterDisplayContent -->
        <?php echo $this->item->event->AfterDisplayContent; ?>

        <!-- K2 Plugins: K2AfterDisplayContent -->
        <?php echo $this->item->event->K2AfterDisplayContent; ?>


        <?php if($this->item->params->get('latestItemCategory') || $this->item->params->get('latestItemTags')): ?>
            <div class="latestItemLinks">

                <?php if($this->item->params->get('latestItemCategory')): ?>
                <!-- Item category name -->
                <div class="latestItemCategory">
                    <span><?php echo JText::_('K2_PUBLISHED_IN'); ?></span>
                    <a href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->name; ?></a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
    <?php endif; ?>

    <!-- Plugins: AfterDisplay -->
    <?php echo $this->item->event->AfterDisplay; ?>

    <!-- K2 Plugins: K2AfterDisplay -->
    <?php echo $this->item->event->K2AfterDisplay; ?>

<!-- End K2 Item Layout -->
