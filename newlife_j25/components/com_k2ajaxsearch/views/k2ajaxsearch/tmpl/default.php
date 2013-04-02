<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<!--    <pre>-->
<!--    --><?php //print_r($this->items); ?>
<!--</pre>-->
<?php if(count($this->items)==0): ?>

    <div>Нічого не знайдено</div>
<?php else: ?>

<ul id="itemListLeading" class="video_list">
    <?php foreach($this->items as $key=>$item): ?>
    <?php if ($this->view_mode == 'short'): ?>
            <li class="video_list_short">
                <div class="short_view">
                    <span class="ItemDateCreated">
                        <?php echo JHTML::_('date', $item->created , 'j.m.Y'); ?>
                    </span>
                    <h3 class="ItemTitle">
                        <a href="/index.php/video/item/<?php echo $item->id; ?><?php echo $item->alias; ?>">
                            <?php echo $item->title; ?>
                        </a>
                    </h3>
                </div>
            </li>
    <?php else: ?>
        <li class="itemContainer">
            <?php
            // Load category_item.php by default
            $this->item = $item; ?>

    <!--    Item start    -->
            <div class="catItemHeader">
                <div class="category_custom_video">
                    <img src="<?php if ($this->item->imageSmall):?><?php echo $this->item->imageSmall; ?><?php else: ?>/images/video_preview.jpg<?php endif; ?>" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" style="width:<?php echo $this->item->imageWidth; ?>px;height:100%;" />
                    <a class="category_custom_link" href="/index.php/video/item/<?php echo $this->item->id; ?><?php echo $this->item->alias; ?>">&nbsp;</a>
                </div>
            </div>
            <!---------------------------------------------------------------------------------------------------------------->
            <div class="catItemBody">

            <?php if ($this->item->extra_fields[0]->value != ''): ?>
                <h3><?php echo $this->fields['series'][$this->item->extra_fields[0]->value]->name ?></h3>
            <?php endif; ?>
        <!------------------------------------------------------------------------------------------------------------->
            <h3 class="catItemTitle">
                <a href="/index.php/video/item/<?php echo $this->item->id; ?><?php echo $this->item->alias; ?>">
                    <?php echo $this->item->title; ?>
                </a>
            </h3>

        <!-- DATE CREATED -->
            <span class="catItemDateCreated">
                <?php echo JHTML::_('date', $this->item->created , 'j.m.Y'); ?>
            </span>
        <!------------------------------------------------------------------------------------------------------------->


        <!------------------------------------------------------------------------------------------------------------->
        <!-- ITEM INTROTEXT -->
            <div class="catItemIntroText">
                <?php echo $this->item->introtext; ?>
            </div>

        <!------------------------------------------------------------------------------------------------------------->
            <?php if($this->item->params->catItemImage && !empty($this->item->image)): ?>
                <!-- Item Image -->
                <div class="catItemImageBlock">
                      <span class="catItemImage">
                        <a href="<?php echo $this->item->link; ?>" title="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>">
                            <img src="<?php echo $this->item->image; ?>" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" style="width:<?php //echo $this->item->imageWidth; ?>-px; height:auto;" />
                        </a>
                      </span>
                </div>
            <?php endif; ?>
        <!------------------------------------------------------------------------------------------------------------->


            <?php if($this->item->params->catItemCategory): ?>
                <!-- Item category name -->
                <div class="catItemCategory">
                    <span><?php echo JText::_('K2_PUBLISHED_IN'); ?></span>
                    <a href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->name; ?></a>
                </div>
            <?php endif; ?>

        </li>
    <?php endif; ?>
    <?php endforeach; ?>
</ul>
<?php endif;?>

<?php die(); ?>