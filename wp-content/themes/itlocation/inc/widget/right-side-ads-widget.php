<?php

class rightSideAdsWidgetItlocation extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {

        parent::__construct(
                'right_side_ads_widget_itlocation', // Base ID
                'Right Side Ads', // Name
                array('description' => __('Display Ads On Right Side', 'twentyten')) // Args
        );

        $this->directory = plugins_url('/', __FILE__);
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    function widget($args, $instance) {
        extract($args);
        extract($instance);
		echo $before_widget;
		
		$ads = '';
		if( $instance['ads_kind'] != '' ) {
			if (get_option($instance['ads_kind'])) {
				$ads = stripslashes(get_option($instance['ads_kind']));
			}
		}
		
		if ($ads) {
			_e($ads);
		} else {
?>
			<div style="width:280px;height:280px;background:url(http://www.placehold.it/280x280/AFAFAF/fff&text=280x280)">
				<?php _e('Please insert your ads code in Appearance -> Theme Options -> tab "Ads" -> option name "Text of ads on right side" in admin', 'twentyten'); ?>
			</div>
<?php
		}
		
		echo $after_widget;
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
		
		$instance['ads_kind'] = $new_instance['ads_kind'];
		
        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    function form($instance) {
		$defaults = array('ads_kind' => '');
		$instance = wp_parse_args((array) $instance, $defaults);
?>
		Please select ads : 
		<select name="<?php echo $this->get_field_name('ads_kind'); ?>">
			<option value="">None</option>
			<option value="itlocation_ads_txt_right_side" <?php echo $instance['ads_kind'] == 'itlocation_ads_txt_right_side' ? 'selected="selected"' : ''; ?>>Contribution Page Ad</option>
			<option value="itlocation_ads_txt_last_right_side"  <?php echo $instance['ads_kind'] == 'itlocation_ads_txt_last_right_side' ? 'selected="selected"' : ''; ?>>Latest Page Ad</option>
		</select>
<?php
    }
}

// class DPE_Flexible_Posts_Widget
