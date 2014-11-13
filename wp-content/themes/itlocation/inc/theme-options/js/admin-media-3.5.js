var custom_options_ph_media_frame;
jQuery(document).ready(function () {
    jQuery('#custom-options-ph .file-upload').live('click', function(){
        if ( custom_options_ph_media_frame ) {
            custom_options_ph_media_frame.open();
            return;
        } else {
            custom_options_ph_media_frame = wp.media.frames.custom_options_ph_media_frame = wp.media({
                className: 'media-frame custom_options_ph_media',
                frame: 'select',
                multiple: false,
                title: custom_options_ph_media.title,
                library: {
                    type: 'image'
                },
                button: {
                    text:  custom_options_ph_media.button
                },
                displaySettings: true,
                displayUserSettings: true
            });
            custom_options_ph_media_frame.open();
        }
		
        $obj = this;

        custom_options_ph_media_frame.on('select', function(){
            var media_attachment = custom_options_ph_media_frame.state().get('selection').first().toJSON();
            jQuery('input', jQuery($obj).parent()).val(media_attachment.url);
            jQuery('input', jQuery($obj).parent()).removeClass('required');
            jQuery('.image-region img', jQuery($obj).parent()).remove();
            jQuery('div.delete-img', jQuery($obj).parent()).removeClass('hide');
            jQuery('div.image-region', jQuery($obj).parent()).append('<img src="'+media_attachment.url+'"/>');

            custom_options_ph_media_frame = false;
        });
    });
});