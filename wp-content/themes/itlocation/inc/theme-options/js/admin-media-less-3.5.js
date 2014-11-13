$custom_options_ph_media_obj = '';
function send_to_editor(html) {
    imgurl = jQuery('img',html).attr('src');
    jQuery('input', jQuery($custom_options_ph_media_obj).parent()).val(imgurl);
    jQuery('input', jQuery($custom_options_ph_media_obj).parent()).removeClass('required');
    jQuery('.image-region img', jQuery($custom_options_ph_media_obj).parent()).remove();
    jQuery('div.delete-img', jQuery($custom_options_ph_media_obj).parent()).removeClass('hide');
    jQuery('div.image-region', jQuery($custom_options_ph_media_obj).parent()).append('<img src="'+imgurl+'"/>');
    tb_remove();
}
jQuery(document).ready(function () {
    jQuery('#custom-options-ph #content-wrap .ctrls .file-upload').live('click', function(){
        $custom_options_ph_media_obj = this;
    });
});