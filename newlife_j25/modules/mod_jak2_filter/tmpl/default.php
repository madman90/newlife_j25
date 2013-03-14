<?php

/**
 * ------------------------------------------------------------------------
 * JA K2 Fillter Module for Joomla 2.5
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die('Restricted access');

?>

<script type="text/javascript">

function ja_k2filter_submit() {
	if($('input_searchword')) {
		input = $('input_searchword');
		v_label = ($$('label[for=input_searchword]')[0].innerHTML)+'...';
		if((input.value !='')&&(input.value!=v_label)) {
			$('mod_ja_searchword').value =input.value;
		}
	}

	build_jafilter('ja_extrafields','mod_ja_group_');
	$('ja_filterform').submit();
}

</script>

<script type="text/javascript" >

var show_cats = <?php echo $plgparams->get('show_cats',0) ?>;
var filter_author = <?php echo $plgparams->get('filter_author',0) ?>;

window.addEvent('domready', function()
{
	var show_extra_fields_groups = '<?php echo $plgparams->get('show_extra_fields_groups', 0); ?>';
	var inline_label = '<?php echo $params->get('inline_label',0);?>';

	label_sliding(inline_label,'ja_extrafields');

	if($('input_searchword') !=null) {
		$('input_searchword').addEvent('keydown', function(e) {
			e = e || window.event;
		 	var code = e.code || e.which;  //Fix key code
			if(code == 13) {
				ja_k2filter_submit();
				return false;
			}
		});
	}

	// Get submit data
    jak2_filter = Cookie.read('jak2_filter');
    if (jak2_filter) jak2_filter = eval('(' + jak2_filter + ')');

    // Restore extra fields groups selection
    if (jak2_filter && show_extra_fields_groups == 1) {
        var sbEFGroups = $('ja_filterform').getElementById('mod_ja_group_efgroups');
        if (sbEFGroups) {
            var options = sbEFGroups.options;
            var efgroups = jak2_filter['extra_fields_groups'];
            for(i = 0; i < options.length; i++) {
                if (options[i].value == efgroups) {
                    options[i].selected = 1;
                    break;
                }
            }
        }
    }

	if (show_extra_fields_groups == 1) load_categories($('ja_filterform').getElementById('mod_ja_group_efgroups').value, 'mod_ja_group_');

	// Restore categories selection
    if (jak2_filter && show_cats) {
        var options = $('ja_filterform').getElementById('mod_ja_group_catid').options;
        var catid   = jak2_filter['catid'];
        for(i = 0; i < options.length; i++) {
            if (options[i].value == catid) {
                options[i].selected = 1;
                break;
            }
        }
    }

	if(show_cats==1) {
	    load_extrafields($('ja_filterform').getElementById('mod_ja_group_catid').value,'mod_ja_group_');
	}

	if(filter_author==1) {
	    load_extrafields($('ja_filterform').getElementById('mod_ja_group_created_by').value,'mod_ja_group_');
	}

	expand_catoptions('mod_ja_group_');
});

</script>

<?php if ($plgparams->get('description','')!=''):?>
    <h3><?php echo $params->get('description','')?></h3>
<?php endif; ?>

<!--
Fix bug after typing in search box and enter, webpage was refreshed
however the keyword in search box was be alternated by 'custom search'
-->
    <form
        action="<?php echo JRoute::_('index.php?option=com_search&view=search&task=search'); ?>"
        method="post" name="ja_filterform" id="ja_filterform"
        onsubmit="ja_k2filter_submit();"
    >
		<input type="hidden" name="option" value="com_search" />
		<input type="hidden" name="view" value="search" />
		<input type="hidden" name="task" value="search" />
		<input type="hidden" name="areas[]" value="jak2_filter" />
		<div id="ja_extrafields">
		<?php if ($params->get('searchbox',0) != 0): ?>
		<div class="ja-slider">
			<label for="input_searchword"><?php echo JText::_('JAK2_FILTER_KEYWORDS')?></label>
			<input type="text" name="input_searchword" id="input_searchword" class="inputbox" value="<?php echo $search_word?>" />
		</div>
		<?php endif;?>

		<?php if ($plgparams->get('show_extra_fields_groups', 0) == 1): ?>
	    <div class="ja-slider">
	       <label for="<?php echo JA_K2_FILTER_GROUP; ?>efgroups"><?php echo JText::_('JAK2_FILTER_EXTRA_FIELDS_GROUPS'); ?></label>
	       <?php echo $extra_fields_groups_html; ?>
	    </div>
	    <?php endif; ?>

		<?php if ($plgparams->get('show_cats',0)==1):?>
		<div class="ja-slider">
            <label for= "<?php echo JA_K2_FILTER_GROUP; ?>catid"><?php echo  JText::_('JAK2_FILTER_CATEGORY') ?></label>
			<?php echo $cat_html;?>
		</div>
		<?php endif;?>

		<?php if ($plgparams->get('filter_author',0)==1):?>
		<div class="ja-slider">
		    <label for= "<?php echo JA_K2_FILTER_GROUP; ?>created_by"><?php echo  JText::_('JAK2_FILTER_AUTHOR') ?></label>
			<?php echo $author_html;?>
		</div>
		<?php endif;?>
	<?php

	foreach ($jaextrafields as $j=>$group) : ?>
	<div id="mod_ja_group_<?php echo $j ?>">
	<?php foreach ($group as $extrafield):?>
		<div class="ja-slider">
			 <!--for Ja-range not input options-->
			<?php
			if ($extrafield->type=='ja_range'):
				$options = $extrafield->options;
			?>
				<?php if (count($options)):?>
					<?php
						$i =1;
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
					  		if (in_array($option->value,$extrafield->default_value)) $check ='checked';
					  		?>
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
	<?php endforeach; ?>

	<input type="hidden"  id="mod_ja_searchword" value="customsearch" name="searchword"/>
	</div>
    <input type="button" value="<?php echo $params->get('button_text', JText::_('JAK2_FILTER_SEARCH'));?>" class="button" onclick="ja_k2filter_submit()" />
  </form>

