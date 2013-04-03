<?php
/**
 * @version		$Id: category.php 1812 2013-01-14 18:45:06Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

?>


<?php if(isset($this->category) || ( $this->params->get('subCategories') && isset($this->subCategories) && count($this->subCategories) )): ?>
<!-- Blocks for current category and subcategories -->
<div class="itemListCategoriesBlock">

    <?php if(isset($this->category) && ( $this->params->get('catImage') || $this->params->get('catTitle') || $this->params->get('catDescription') || $this->category->event->K2CategoryDisplay )): ?>
    <!-- Category block -->
    <div class="itemListCategory">

        <?php if(isset($this->addLink)): ?>
        <!-- Item add link -->
        <span class="catItemAddLink">
				<a class="modal" rel="{handler:'iframe',size:{x:990,y:650}}" href="<?php echo $this->addLink; ?>">
                    <?php echo JText::_('K2_ADD_A_NEW_ITEM_IN_THIS_CATEGORY'); ?>
                </a>
			</span>
        <?php endif; ?>

        <?php if($this->params->get('catImage') && $this->category->image): ?>
        <!-- Category image -->
        <img alt="<?php echo K2HelperUtilities::cleanHtml($this->category->name); ?>" src="<?php echo $this->category->image; ?>" style="width:<?php echo $this->params->get('catImageWidth'); ?>px; height:auto;" />
        <?php endif; ?>



        <?php if($this->params->get('catDescription')): ?>
        <!-- Category description -->
        <p><?php echo $this->category->description; ?></p>
        <?php endif; ?>

        <!-- K2 Plugins: K2CategoryDisplay -->
        <?php echo $this->category->event->K2CategoryDisplay; ?>

        <div class="clr"></div>
    </div>
    <?php endif; ?>

    <?php if($this->params->get('subCategories') && isset($this->subCategories) && count($this->subCategories)): ?>
    <!-- Subcategories -->
    <div class="itemListSubCategories">
        <h3><?php echo JText::_('K2_CHILDREN_CATEGORIES'); ?></h3>

        <?php foreach($this->subCategories as $key=>$subCategory): ?>

        <?php
        // Define a CSS class for the last container on each row
        if( (($key+1)%($this->params->get('subCatColumns'))==0))
            $lastContainer= ' subCategoryContainerLast';
        else
            $lastContainer='';
        ?>

        <div class="subCategoryContainer<?php echo $lastContainer; ?>"<?php echo (count($this->subCategories)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('subCatColumns'), 1).'%;"'; ?>>
            <div class="subCategory">
                <?php if($this->params->get('subCatImage') && $subCategory->image): ?>
                <!-- Subcategory image -->
                <a class="subCategoryImage" href="<?php echo $subCategory->link; ?>">
                    <img alt="<?php echo K2HelperUtilities::cleanHtml($subCategory->name); ?>" src="<?php echo $subCategory->image; ?>" />
                </a>
                <?php endif; ?>

                <?php if($this->params->get('subCatTitle')): ?>
                <!-- Subcategory title -->
                <h2>
                    <a href="<?php echo $subCategory->link; ?>">
                        <?php echo $subCategory->name; ?><?php if($this->params->get('subCatTitleItemCounter')) echo ' ('.$subCategory->numOfItems.')'; ?>
                    </a>
                </h2>
                <?php endif; ?>

                <?php if($this->params->get('subCatDescription')): ?>
                <!-- Subcategory description -->
                <p><?php echo $subCategory->description; ?></p>
                <?php endif; ?>

                <!-- Subcategory more... -->
                <a class="subCategoryMore" href="<?php echo $subCategory->link; ?>">
                    <?php echo JText::_('K2_VIEW_ITEMS'); ?>
                </a>

                <div class="clr"></div>
            </div>
        </div>
        <?php if(($key+1)%($this->params->get('subCatColumns'))==0): ?>
            <div class="clr"></div>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="clr"></div>
    </div>
    <?php endif; ?>

</div>
<?php endif; ?>

<!----------------------------------------------------------------------->
<?php if($this->category->alias == 'video' && $this->view_mode == 'short'): ?>
    <?php if((isset($this->leading) || isset($this->primary) || isset($this->secondary) || isset($this->links)) && (count($this->leading) || count($this->primary) || count($this->secondary) || count($this->links))): ?>
        <!-- Item list -->
        <!--	<ul class="itemList">-->
        <div id="video_content">
        <?php if(isset($this->leading) && count($this->leading)): ?>
            <!-- Leading items -->
            <ul id="itemListLeading" class="video_list_short">
                <?php foreach($this->leading as $key=>$item): ?>

                <?php
                // Define a CSS class for the last container on each row
                if( (($key+1)%($this->params->get('num_leading_columns'))==0) || count($this->leading)<$this->params->get('num_leading_columns') )
                    $lastContainer= ' itemContainerLast';
                else
                    $lastContainer='';
                ?>

                <li class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($this->leading)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('num_leading_columns'), 1).'%;"'; ?>>
                    <?php
                    // Load category_item.php by default
                    $this->item=$item;
                    echo $this->loadTemplate('item');
                    ?>
                </li>
                <?php if(($key+1)%($this->params->get('num_leading_columns'))==0): ?>
                    <!--			<li class="clr"></li>-->
                    <?php endif; ?>
                <?php endforeach; ?>
                <!--			<li class="clr"></li>-->
            </ul>
            <?php endif; ?>

        <?php if(isset($this->primary) && count($this->primary)): ?>
            <!-- Primary items -->
            <ul id="itemListPrimary">
                <?php foreach($this->primary as $key=>$item): ?>

                <?php
                // Define a CSS class for the last container on each row
                if( (($key+1)%($this->params->get('num_primary_columns'))==0) || count($this->primary)<$this->params->get('num_primary_columns') )
                    $lastContainer= ' itemContainerLast';
                else
                    $lastContainer='';
                ?>

                <li class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($this->primary)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('num_primary_columns'), 1).'%;"'; ?>>
                    <?php
                    // Load category_item.php by default
                    $this->item=$item;
                    echo $this->loadTemplate('item');
                    ?>
                </li>
                <?php if(($key+1)%($this->params->get('num_primary_columns'))==0): ?>
                    <!--			<li class="clr"></li>-->
                    <?php endif; ?>
                <?php endforeach; ?>
                <!--			<li class="clr"></li>-->
            </ul>
            <?php endif; ?>

        <?php if(isset($this->secondary) && count($this->secondary)): ?>
            <!-- Secondary items -->
            <ul id="itemListSecondary">
                <?php foreach($this->secondary as $key=>$item): ?>

                <?php
                // Define a CSS class for the last container on each row
                if( (($key+1)%($this->params->get('num_secondary_columns'))==0) || count($this->secondary)<$this->params->get('num_secondary_columns') )
                    $lastContainer= ' itemContainerLast';
                else
                    $lastContainer='';
                ?>

                <li class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($this->secondary)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('num_secondary_columns'), 1).'%;"'; ?>>
                    <?php
                    // Load category_item.php by default
                    $this->item=$item;
                    echo $this->loadTemplate('item');
                    ?>
                </li>
                <?php if(($key+1)%($this->params->get('num_secondary_columns'))==0): ?>
                    <!--			<li class="clr"></li>-->
                    <?php endif; ?>
                <?php endforeach; ?>
                <!--			<li class="clr"></li>-->
            </ul>
            <?php endif; ?>

        <?php if(isset($this->links) && count($this->links)): ?>
            <!-- Link items -->
            <ul id="itemListLinks">
                <h4><?php echo JText::_('K2_MORE'); ?></h4>
                <?php foreach($this->links as $key=>$item): ?>

                <?php
                // Define a CSS class for the last container on each row
                if( (($key+1)%($this->params->get('num_links_columns'))==0) || count($this->links)<$this->params->get('num_links_columns') )
                    $lastContainer= ' itemContainerLast';
                else
                    $lastContainer='';
                ?>

                <li class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($this->links)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('num_links_columns'), 1).'%;"'; ?>>
                    <?php
                    // Load category_item_links.php by default
                    $this->item=$item;
                    echo $this->loadTemplate('item_links');
                    ?>
                </li>
                <?php if(($key+1)%($this->params->get('num_links_columns'))==0): ?>
                    <!--			<li class="clr"></li>-->
                    <?php endif; ?>
                <?php endforeach; ?>
                <!--			<li class="clr"></li>-->
            </ul>
            <?php endif; ?>

        <!--	</ul>-->

        <!-- Pagination -->
        <?php if(count($this->pagination->getPagesLinks())): ?>
            <div class="k2Pagination">
                <?php if($this->params->get('catPagination')) echo $this->pagination->getPagesLinks(); ?>
            </div>
            <?php endif; ?>

        <?php endif; ?>
        </div>
        <div id="search_content" style="display: none"></div>
        <div id="loader" style="display: none"><img src="/images/350.gif"></div>
    <!-----------------video short--------->
<?php else: ?>
<!--  whats new and other ctegories  -->
<?php if((isset($this->leading) || isset($this->primary) || isset($this->secondary) || isset($this->links)) && (count($this->leading) || count($this->primary) || count($this->secondary) || count($this->links))): ?>

        <div id="video_content">

    <!-- Item list -->
    <!--	<ul class="itemList">-->

    <?php if(isset($this->leading) && count($this->leading)): ?>
        <!-- Leading items -->
        <ul id="itemListLeading" class="video_list">
            <?php foreach($this->leading as $key=>$item): ?>

            <?php
            // Define a CSS class for the last container on each row
            if( (($key+1)%($this->params->get('num_leading_columns'))==0) || count($this->leading)<$this->params->get('num_leading_columns') )
                $lastContainer= ' itemContainerLast';
            else
                $lastContainer='';
            ?>

            <li class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($this->leading)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('num_leading_columns'), 1).'%;"'; ?>>
                <?php
                // Load category_item.php by default
                $this->item=$item;
                echo $this->loadTemplate('item');
                ?>
            </li>
            <?php if(($key+1)%($this->params->get('num_leading_columns'))==0): ?>
                <!--			<li class="clr"></li>-->
                <?php endif; ?>
            <?php endforeach; ?>
            <!--			<li class="clr"></li>-->
        </ul>
        <?php endif; ?>

    <?php if(isset($this->primary) && count($this->primary)): ?>
        <!-- Primary items -->
        <ul id="itemListPrimary">
            <?php foreach($this->primary as $key=>$item): ?>

            <?php
            // Define a CSS class for the last container on each row
            if( (($key+1)%($this->params->get('num_primary_columns'))==0) || count($this->primary)<$this->params->get('num_primary_columns') )
                $lastContainer= ' itemContainerLast';
            else
                $lastContainer='';
            ?>

            <li class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($this->primary)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('num_primary_columns'), 1).'%;"'; ?>>
                <?php
                // Load category_item.php by default
                $this->item=$item;
                echo $this->loadTemplate('item');
                ?>
            </li>
            <?php if(($key+1)%($this->params->get('num_primary_columns'))==0): ?>
                <!--			<li class="clr"></li>-->
                <?php endif; ?>
            <?php endforeach; ?>
            <!--			<li class="clr"></li>-->
        </ul>
        <?php endif; ?>

    <?php if(isset($this->secondary) && count($this->secondary)): ?>
        <!-- Secondary items -->
        <ul id="itemListSecondary">
            <?php foreach($this->secondary as $key=>$item): ?>

            <?php
            // Define a CSS class for the last container on each row
            if( (($key+1)%($this->params->get('num_secondary_columns'))==0) || count($this->secondary)<$this->params->get('num_secondary_columns') )
                $lastContainer= ' itemContainerLast';
            else
                $lastContainer='';
            ?>

            <li class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($this->secondary)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('num_secondary_columns'), 1).'%;"'; ?>>
                <?php
                // Load category_item.php by default
                $this->item=$item;
                echo $this->loadTemplate('item');
                ?>
            </li>
            <?php if(($key+1)%($this->params->get('num_secondary_columns'))==0): ?>
                <!--			<li class="clr"></li>-->
                <?php endif; ?>
            <?php endforeach; ?>
            <!--			<li class="clr"></li>-->
        </ul>
        <?php endif; ?>

    <?php if(isset($this->links) && count($this->links)): ?>
        <!-- Link items -->
        <ul id="itemListLinks">
            <h4><?php echo JText::_('K2_MORE'); ?></h4>
            <?php foreach($this->links as $key=>$item): ?>

            <?php
            // Define a CSS class for the last container on each row
            if( (($key+1)%($this->params->get('num_links_columns'))==0) || count($this->links)<$this->params->get('num_links_columns') )
                $lastContainer= ' itemContainerLast';
            else
                $lastContainer='';
            ?>

            <li class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($this->links)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('num_links_columns'), 1).'%;"'; ?>>
                <?php
                // Load category_item_links.php by default
                $this->item=$item;
                echo $this->loadTemplate('item_links');
                ?>
            </li>
            <?php if(($key+1)%($this->params->get('num_links_columns'))==0): ?>
                <!--			<li class="clr"></li>-->
                <?php endif; ?>
            <?php endforeach; ?>
            <!--			<li class="clr"></li>-->
        </ul>
        <?php endif; ?>

    <!--	</ul>-->

    <!-- Pagination -->
        <?php if(count($this->pagination->getPagesLinks())): ?>
        <div class="k2Pagination">
            <?php if($this->params->get('catPagination')) echo $this->pagination->getPagesLinks(); ?>
        </div>
        <?php endif; ?>
        </div>

    <div id="search_content" style="display: none"></div>
    <div id="loader" style="display: none"><img src="/images/350.gif"></div>

    <?php endif; ?>
<?php endif; ?>
<!--</div>-->
<!-- End K2 Category Layout -->
