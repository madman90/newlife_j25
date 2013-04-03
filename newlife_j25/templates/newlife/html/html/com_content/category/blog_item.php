

<?php
// no direct access
defined('_JEXEC') or die;


// Create a shortcut for params.
$params = &$this->item->params;
$images = json_decode($this->item->images);
$canEdit	= $this->item->params->get('access-edit');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.framework');
?>

<?php $this->item->images = json_decode($this->item->images); ?>
<?php if($this->item->category_alias=='small-groups'): ?>
    <?php if ($this->item->state == 0) : ?>
    <div class="system-unpublished">
    <?php endif; ?>

    <?php if ($params->get('show_intro')) : ?>
            <?php echo $this->item->event->afterDisplayTitle; ?>
    <?php endif; ?>
        <div class="pastors_photo">
            <img src="<?php if ($this->item->images->image_intro) { echo $this->item->images->image_intro; } ?>">
        </div>
<!--        <div class="small_map">{phocamaps view=map|id=2}{phocamaps view=link|id=9|text=Link to Map}</div>-->
        <?php echo $this->item->event->beforeDisplayContent; ?>
        <div class="groups_info">
            <?php if ($params->get('show_title')) : ?>
            <h3>
                <?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
                <a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">
                    <?php echo $this->escape($this->item->title); ?></a>
                <?php else : ?>
                <?php echo $this->escape($this->item->title); ?>
                <?php endif; ?>
            </h3>
            <?php endif; ?>
            <?php echo ($this->item->introtext) ?>
        </div>
        <?php if ($this->item->state == 0) : ?>
        </div>
        <?php endif; ?>
    <?php echo $this->item->event->afterDisplayContent; ?>
<?php else: ?>
    <?php if ($this->item->state == 0) : ?>
        <div class="system-unpublished">
        <?php endif; ?>
        <?php if ($params->get('show_intro')) : ?>
            <?php echo $this->item->event->afterDisplayTitle; ?>
            <?php endif; ?>
        <div class="pastors_photo">
            <img src="<?php if ($this->item->images->image_intro) { echo $this->item->images->image_intro; } ?>">
        </div>
        <?php echo $this->item->event->beforeDisplayContent; ?>
        <div class="intro">
            <?php if ($params->get('show_title')) : ?>
            <h3>
                <?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
                <a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">
                    <?php echo $this->escape($this->item->title); ?></a>
                <?php else : ?>
                <?php echo $this->escape($this->item->title); ?>
                <?php endif; ?>
            </h3>
            <?php endif; ?>
            <?php echo ($this->item->introtext) ?>
        </div>
        <?php if ($this->item->state == 0) : ?>
            </div>
            <?php endif; ?>
    <?php echo $this->item->event->afterDisplayContent; ?>
<?php endif; ?>