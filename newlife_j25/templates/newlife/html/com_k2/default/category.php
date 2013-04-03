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



<!-----------------------------what's new------------------------------------------>
<?php if ($this->category->alias == 'whats-new'): ?>
<?php if ((isset($this->leading) || isset($this->primary) || isset($this->secondary) || isset($this->links)) && (count($this->leading) || count($this->primary) || count($this->secondary) || count($this->links))): ?>
    <h2>Що нового</h2>
    <!-- Item list -->
    <!--	<ul class="itemList">-->
        <div id="whatsnew_content">
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
<!----------------------------------------------------------------------->
<?php elseif ($this->category->alias == 'video' && $this->view_mode == 'short'): ?>
    <?php if ((isset($this->leading) || isset($this->primary) || isset($this->secondary) || isset($this->links)) && (count($this->leading) || count($this->primary) || count($this->secondary) || count($this->links))): ?>
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
