<div>
    <form id="k2_ajax_form">
        <div id="bible_books">
            <label for="books">Книга:</label>
            <select autocomplete="off" id="books" name="query[book][]">
                <option value="">Виберіть книгу Біблії</option>
                <?php foreach($data['books'] as $book): ?>
                    <option value="<?php echo $book->value; ?>"><?php echo $book->name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <h4>Серії проповідей:</h4>
        <ul class="series_of_preaches">
            <?php foreach($data['series'] as $seria): ?>
                <li>
                    <input autocomplete="off" class="seria" type="checkbox" value="<?php echo $seria->value; ?>" name="query[seria][]" id="seria-<?php echo $seria->value; ?>">
                    <label class="preaches_seria" for="seria-<?php echo $seria->value; ?>"><?php echo $seria->name; ?></label>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if (count($tags)>0): ?>
        <h3 class="moduletabletag">Пошук по ключовим словам:</h3>
        <div id="k2ModuleBox<?php echo $module->id; ?>" class="k2TagCloudBlock<?php if($params->get('moduleclass_sfx')) echo ' '.$params->get('moduleclass_sfx'); ?>">
            <?php foreach ($tags as $tag): ?>
            <?php if(!empty($tag->tag)): ?>
                <a class="tag_link" href="#" value="<?php echo $tag->id; ?>" style="font-size:<?php echo $tag->size; ?>%" title="<?php echo $tag->count.' '.JText::_('K2_ITEMS_TAGGED_WITH').' '.K2HelperUtilities::cleanHtml($tag->tag); ?>">
                    <?php echo $tag->tag; ?>
                </a>
                <?php endif; ?>
            <?php endforeach; ?>
            <div class="clr"></div>
        </div>

        <?php endif; ?>


        <input id="reset" type="button" value="Скасувати">
    </form>
</div>
<script type="text/javascript">
    function search(e)
    {
        if (jQuery('#books').val() == '' && jQuery('.seria:checked').length == 0 && jQuery('.hidden_tag').length == 0)
        {
            reset();
        }
        else
        {
            jQuery('#video_content').hide();
            jQuery('#search_content').hide();
            jQuery('#loader').show();
            jQuery.ajax({
                url: '/index.php?option=com_k2ajaxsearch&'+jQuery('#k2_ajax_form').serialize(),
                type: 'get',
                success: function(data)
                {
                    jQuery('#search_content').empty();
                    jQuery('#search_content').append(jQuery(data));
                    jQuery('#loader').hide();
                    jQuery('#search_content').show();
                }

            });
        }
    }

    function reset(){
        jQuery('#video_content').show();
        jQuery('#search_content').hide();
        jQuery('.seria').removeAttr('checked');
        jQuery('#books').val('');
        jQuery('.hidden_tag').each(function(){
            jQuery(this).remove();
        });
        jQuery('.activeTag').each(function(){
            jQuery(this).removeClass('activeTag');
        });
    }


    jQuery(document).ready(function(){
        jQuery('.seria').removeAttr('checked');
        jQuery('#books').val('');
        jQuery('.seria').bind('click', search);
        jQuery('#books').bind('change', search);
        jQuery('#reset').bind('click', reset);
        jQuery('.tag_link').click(function(){
            if (jQuery('#tag_'+jQuery(this).attr('value')).length == 0)
            {
                jQuery('#k2_ajax_form').append(jQuery('<input class="hidden_tag" type="hidden" name="query[tag][]" id="tag_' + jQuery(this).attr('value') +'" value="' + jQuery(this).attr('value') + '" >'));
                jQuery(this).addClass('activeTag');
            }
            else
            {
                jQuery('#tag_'+jQuery(this).attr('value')).remove();
                jQuery(this).removeClass('activeTag');
            }
            search();
            return false;
        });
    });
</script>