<?php
/**
 * Flexible Posts Widget: Widget Admin Form 
 */
// Block direct requests
if (!defined('ABSPATH'))
    die('-1');
?>
<div class="itlocation-widget">

    <div class="section title">
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget title:', 'flexible-posts-widget'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
    </div>

    <div class="section getemby">
        <h4><?php _e('Get posts by', 'flexible-posts-widget'); ?></h4>
        <div class="inside">
            <?php $this->posttype_checklist($posttype); ?>
        </div><!-- .inside -->
    </div>

    <div class="section display">
        <h4><?php _e('Display options', 'flexible-posts-widget'); ?></h4>
        <p class="cf">
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'flexible-posts-widget'); ?></label> 
            <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
        <p class="cf">
            <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order:', 'flexible-posts-widget'); ?></label> 
            <select name="<?php echo $this->get_field_name('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>">
                <?php
                foreach ($this->orders as $key => $value) {
                    echo '<option value="' . $key . '" id="' . $this->get_field_id($key) . '"', $order == $key ? ' selected="selected"' : '', '>', $value, '</option>';
                }
                ?>
            </select>		
        </p>
    </div>

    <div class="section thumbnails">
        <p class="check">
            <input class="itlocation-thumbnail" id="<?php echo $this->get_field_id('thumbnail'); ?>" name="<?php echo $this->get_field_name('thumbnail'); ?>" type="checkbox" value="1" <?php checked('1', $thumbnail); ?>/>
            <label class="font-weight-bold" for="<?php echo $this->get_field_id('thumbnail'); ?>"><?php _e('Display thumbnails?', 'flexible-posts-widget'); ?></label> 
        </p>
        <p <?php echo $thumbnail ? '' : 'style="display:none;"' ?>  class="thumb-size">	
            <label for="<?php echo $this->get_field_id('thumbsize'); ?>"><?php _e('Select a thumbnail size to show:', 'flexible-posts-widget'); ?></label> 
            <select class="widefat" name="<?php echo $this->get_field_name('thumbsize'); ?>" id="<?php echo $this->get_field_id('thumbsize'); ?>">
                <?php
                foreach ($this->thumbsizes as $option) {
                    echo '<option value="' . $option . '" id="' . $this->get_field_id($option) . '"', $thumbsize == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                ?>
            </select>		
        </p>
    </div>
</div><!-- .itlocation-widget -->
<script>
    jQuery(document).ready(function() {
        jQuery('#widgets-right').on("change", 'input.itlocation-thumbnail', function(event) {
            if( this.checked ) {
                jQuery(this).parent().next().slideDown('fast');
            } else {
                jQuery(this).parent().next().slideUp('fast');
            }
        });
    });
</script>