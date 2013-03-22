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
        <?php foreach($data['series'] as $seria): ?>
            <input autocomplete="off" class="seria" type="checkbox" value="<?php echo $seria->value; ?>" name="query[seria][]" id="seria-<?php echo $seria->value; ?>">
            <label class="preaches_seria" for="seria-<?php echo $seria->value; ?>"><?php echo $seria->name; ?></label>
        <?php endforeach; ?>
        <input id="reset" type="button" value="Скасувати">
    </form>
</div>
<script type="text/javascript">
    function search(e)
    {
        if (jQuery('#books').val() == '' && jQuery('.seria:checked').length == 0)
        {
            jQuery('#video_content').show();
            jQuery('#search_content').hide();
            jQuery('.seria').removeAttr('checked');
            jQuery('#books').val('');
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

    jQuery(document).ready(function(){
        jQuery('.seria').removeAttr('checked');
        jQuery('#books').val('');
        jQuery('.seria').bind('click', search);
        jQuery('#books').bind('change', search);
        jQuery('#reset').click(function(){
            jQuery('#video_content').show();
            jQuery('#search_content').hide();
            jQuery('.seria').removeAttr('checked');
            jQuery('#books').val('');
        });
    });
</script>