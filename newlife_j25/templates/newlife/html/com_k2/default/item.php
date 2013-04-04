<?php
/**
 * @version		$Id: item.php 1812 2013-01-14 18:45:06Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

?>
<!-- Start K2 Item Layout -->

<div id="k2Container" class="itemView<?php echo ($this->item->featured) ? ' itemIsFeatured' : ''; ?><?php if($this->item->params->get('pageclass_sfx')) echo ' '.$this->item->params->get('pageclass_sfx'); ?>">

    <!-- Plugins: BeforeDisplay -->
    <?php echo $this->item->event->BeforeDisplay; ?>

    <!-- K2 Plugins: K2BeforeDisplay -->
    <?php echo $this->item->event->K2BeforeDisplay; ?>



    <!-- Plugins: AfterDisplayTitle -->
    <?php echo $this->item->event->AfterDisplayTitle; ?>

    <!-- K2 Plugins: K2AfterDisplayTitle -->
    <?php echo $this->item->event->K2AfterDisplayTitle; ?>

    <!--    Priches Series-->
    <?php if (count($this->item->extra_fields)>0 && $this->item->extra_fields[0]->value != ''): ?>
    <h2><?php echo $this->item->extra_fields[0]->value ?></h2>
    <?php endif; ?>

    <?php if($this->item->params->get('itemTitle')): ?>
    <!-- Item title -->
    <h3 class="itemTitle">
        <?php if(isset($this->item->editLink)): ?>
        <!-- Item edit link -->
        <span class="itemEditLink">
				<a class="modal" rel="{handler:'iframe',size:{x:990,y:550}}" href="<?php echo $this->item->editLink; ?>">
                    <?php echo JText::_('K2_EDIT_ITEM'); ?>
                </a>
			</span>
        <?php endif; ?>

        <?php echo $this->item->title; ?>

        <?php if($this->item->params->get('itemFeaturedNotice') && $this->item->featured): ?>
        <!-- Featured flag -->
        <span>
		  	<sup>
                  <?php echo JText::_('K2_FEATURED'); ?>
              </sup>
	  	</span>
        <?php endif; ?>

    </h3>
    <?php endif; ?>

    <?php if($this->item->params->get('itemDateCreated')): ?>
    <!-- Date created -->
    <span class="itemDateCreated">
			<?php echo JHTML::_('date', $this->item->created , 'j.m.Y'); ?>
	</span>
    <?php endif; ?>
        <!-- Plugins: BeforeDisplayContent -->
        <?php echo $this->item->event->BeforeDisplayContent; ?>

        <!-- K2 Plugins: K2BeforeDisplayContent -->
        <?php echo $this->item->event->K2BeforeDisplayContent; ?>

    <?php if($this->item->params->get('itemImage') && !empty($this->item->image)): ?>
    <!-- Item Image -->
    <div class="itemImageBlock">
		  <span class="itemImage">
		  	<a class="modal" rel="{handler: 'image'}" href="<?php echo $this->item->imageXLarge; ?>" title="<?php echo JText::_('K2_CLICK_TO_PREVIEW_IMAGE'); ?>">
                  <img src="<?php echo $this->item->image; ?>" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>"  />
              </a>
		  </span>

        <?php if($this->item->params->get('itemImageMainCaption') && !empty($this->item->image_caption)): ?>
        <!-- Image caption -->
        <span class="itemImageCaption"><?php echo $this->item->image_caption; ?></span>
        <?php endif; ?>

        <?php if($this->item->params->get('itemImageMainCredits') && !empty($this->item->image_credits)): ?>
        <!-- Image credits -->
        <span class="itemImageCredits"><?php echo $this->item->image_credits; ?></span>
        <?php endif; ?>


    </div>
    <?php endif; ?>


    <?php if($this->item->params->get('itemIntroText')): ?>
    <!-- Item introtext -->
    <div class="itemIntroText">
        <?php echo $this->item->introtext; ?>
    </div>
    <?php endif; ?>



    <?php if($this->item->params->get('itemDateModified') && intval($this->item->modified)!=0): ?>
    <!-- Item date modified -->
    <span class="itemDateModified">
				<?php echo JText::_('K2_LAST_MODIFIED_ON'); ?> <?php echo JHTML::_('date', $this->item->modified, JText::_('K2_DATE_FORMAT_LC2')); ?>
			</span>
    <?php endif; ?>


    <!-- Plugins: AfterDisplayContent -->
    <?php echo $this->item->event->AfterDisplayContent; ?>

    <!-- K2 Plugins: K2AfterDisplayContent -->
    <?php echo $this->item->event->K2AfterDisplayContent; ?>

    <?php if($this->item->params->get('itemCategory') || $this->item->params->get('itemTags') || $this->item->params->get('itemAttachments')): ?>
    <div class="itemLinks">

        <?php if($this->item->params->get('itemCategory')): ?>
        <!-- Item category -->
        <div class="itemCategory">
            <span><?php echo JText::_('K2_PUBLISHED_IN'); ?></span>
            <a href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->name; ?></a>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if($this->item->params->get('itemVideo') && !empty($this->item->video)): ?>
    <!-- Item video -->
    <a name="itemVideoAnchor" id="itemVideoAnchor"></a>

    <div class="itemVideoBlock">
        <?php if($this->item->videoType=='embedded'): ?>
        <div class="itemVideoEmbedded">
            <?php echo $this->item->video; ?>
        </div>
        <?php else: ?>
        <span class="itemVideo"><?php echo $this->item->video; ?></span>
        <?php endif; ?>

        <?php if($this->item->params->get('itemVideoCaption') && !empty($this->item->video_caption)): ?>
        <span class="itemVideoCaption"><?php echo $this->item->video_caption; ?></span>
        <?php endif; ?>

        <?php if($this->item->params->get('itemVideoCredits') && !empty($this->item->video_credits)): ?>
        <span class="itemVideoCredits"><?php echo $this->item->video_credits; ?></span>
        <?php endif; ?>


    </div>
    <?php endif; ?>

    <?php if($this->item->params->get('itemImageGallery') && !empty($this->item->gallery)): ?>
    <!-- Item image gallery -->
    <a name="itemImageGalleryAnchor" id="itemImageGalleryAnchor"></a>
    <div class="itemImageGallery">
        <h3><?php echo JText::_('K2_IMAGE_GALLERY'); ?></h3>
        <?php echo $this->item->gallery; ?>
    </div>
    <?php endif; ?>



    <!-- Plugins: AfterDisplay -->
    <?php echo $this->item->event->AfterDisplay; ?>

    <!-- K2 Plugins: K2AfterDisplay -->
    <?php echo $this->item->event->K2AfterDisplay; ?>





</div>
<!-- End K2 Item Layout -->
