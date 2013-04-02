<?php
/**
 * ------------------------------------------------------------------------
 * JA K2 Search Plugin for Joomla 2.5
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

if ($params->get('description','')!=''):?>
	<h3><?php echo $params->get('description','')?></h3>
<?php 	 endif; ?>
<?php if ($params->get('show_extra_fields_groups', 0) == 1): ?>
<div class="ja-slider">
   <label for="<?php echo $this->groupname; ?>efgroups"><?php echo JText::_("JAK2_FILTER_EXTRA_FIELDS_GROUPS"); ?></label>
   <?php echo $extra_fields_groups_html; ?>
</div>
<?php endif; ?>
<?php
if ($params->get('show_cats',0)==1): ?>
<div class="ja-slider">
   <label for= "<?php echo $this->groupname; ?>catid"><?php echo  JText::_('JAK2_FILTER_CATEGORY') ?></label>
   <?php echo $cat_html;?>
</div>
<?php endif; ?>
<?php if ($params->get('filter_author',0)==1): ?>
	<div class="ja-slider">
	  <label for= "<?php echo $this->groupname; ?>created_by"><?php echo  JText::_('JAK2_FILTER_AUTHOR') ?></label>
		<?php echo $author_html;?>
	</div>
<?php endif; ?>
<?php
if (is_array($jaextrafields)):
foreach ($jaextrafields as $j=>$group) : ?>
<div id="plg_ja_group_<?php echo $j ?>">
<?php foreach ($group as $extrafield):?>
	<div class="ja-slider">
	<?php
		if ($extrafield->type=='ja_range'):
			$options = $extrafield->options;
		?>
			<?php if (count($options)):?>
				<?php
					$i=1;
					foreach ($options as $option):?>
						<label for= "<?php echo $option->id ?>"><?php echo  $option->label_html ?></label>
						<input name="<?php echo $option->input_name ?>" id="<?php echo $option->id ?>" type="text"  class="inputbox field" value="<?php echo $option->default_value ?>" />
					<?php
					if ($i<count($options)) echo "-";
					$i++;
					endforeach;
				?>
			<?php endif;?>
		<?php else :?>
		 <label for= "<?php echo $extrafield->id ?>"><?php echo  $extrafield->label_html ?></label>
		<?php
			switch ($extrafield->type)
			{
		 		case 'text':?>
				<input name="<?php echo $extrafield->input_name ?>" id="<?php echo $extrafield->id ?>" type="text"  class="inputbox" value="<?php echo $extrafield->default_value ?>" />
				<?php
				break;
		  case 'select':
			  	echo JHTML::_('select.genericlist', $extrafield->options , $extrafield->input_name, 'class="inputbox" size="1" ','value','text',$extrafield->default_value);
			  	break;
		  case 'multi':
			  	for($i=0;$i<count($extrafield->options);$i++):
			  		$option =$extrafield->options[$i];
			  		$check ='';
			  		if (in_array($option->value,$extrafield->default_value)) $check ='checked';
			  		?>
			  		<div class="ja-checkbox">
			  			<input name="<?php echo $extrafield->input_name ?>" type="checkbox" value="<?php echo $option->value ?>" id="<?php echo $extrafield->id.($i+1) ?>" class="inputbox"  <?php echo $check ?> />
						<label for="<?php echo $extrafield->id.($i+1) ?>" > <?php echo $option->text ?></label>
					</div>
			<?php
				endfor;
			 	break;
		  case 'radio':
				for($i=0;$i<count($extrafield->options);$i++):
			  		$option =$extrafield->options[$i];
			  		$check ='';
			  		if (in_array($option->value,$extrafield->default_value))
			  		{
			  			$check ='checked';
			  		}

			  		if ($option->value==$extrafield->default_value) $check ='checked';?>
			  		<div class="ja-checkbox">
			  			<input name="<?php echo $extrafield->input_name ?>" type="radio" value="<?php echo $option->value ?>" id="<?php echo $extrafield->id.($i+1) ?>" class="inputbox"  <?php echo $check ?> />
						<label for="<?php echo $extrafield->id.($i+1) ?>" > <?php echo $option->text ?></label>
					</div>
			  		<?php
				endfor;
				break;
		  default:
			  	?>
			  	<input name="<?php $extrafield->input_name ?>" id="<?php echo $extrafield->id ?>" type="text"  value="<?php echo $extrafield->default_value ?>" class="inputbox"/>
			  	<?php
			  	break;
			}
		endif;
	 ?>

	</div>
<?php endforeach; ?>
</div>
<?php
endforeach;
endif;
?>
<input type="hidden"  id="jak2_filter" value="" name="jak2_filter">
<input type="hidden"  id="ja_searchword" value="customsearch" name="searchword">