<link rel='stylesheet' id='select2-css'  href='<?php echo get_bloginfo('template_url'); ?>/plugins/select2-3.4.3/select2.css' type='text/css' media='all' />
<script type='text/javascript' src='<?php echo get_bloginfo('template_url'); ?>/plugins/select2-3.4.3/select2.min.js?ver=3.6.1'></script>
<script>
    jQuery(document).ready(function() {
        jQuery("#change-author").select2({
            minimumInputLength: 2,
            ajax: {
                url: "<?php echo get_bloginfo('template_url'); ?>/ajax/get_users.php",
                dataType: 'json',
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    console.log(data);
                    return { results: data };
                }
            }
        });
    });
</script>

<label for="change-author"><?php _e("Change author", 'twentyten'); ?></label>
<input type="text" id="change-author" name="change-author" style="width: 160px" value="" />
