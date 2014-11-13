<?php
	add_action('admin_init', 'member_logo_metabox_admin_init');
	add_action('save_post', 'member_logo_save');
	
	add_action('admin_print_scripts', 'member_logo_scripts');
	
	function member_logo_scripts() {
		$wp_version = get_bloginfo('version');
		if ($wp_version  < 3.5) {
			wp_enqueue_script('custom-options-ph-admin-media-less-3.5', get_template_directory_uri() . '/js/admin-media-less-3.5.js');
		} else {
			wp_enqueue_media();
			wp_register_script('custom_options_ph_media', get_template_directory_uri() . '/js/admin-media-3.5.js', array('jquery'), '1.0.0', true);
			wp_localize_script('custom_options_ph_media', 'custom_options_ph_media', array(
				'title' => __('Upload or Choose Your Custom Image File', 'base_shortcode'),
				'button' => __('Insert Image into Input Field', 'base_shortcode'))
			);
			wp_enqueue_script('custom_options_ph_media');
		}
	}
	
	function member_logo_metabox_admin_init() {
		add_meta_box("member_logo_metabox", "Member Detail Information", "member_logo_metabox", "member-contributions", "normal", "low");
	}
	
	function member_logo_metabox() {
		global $post;
		$custom = get_post_custom($post->ID);
		
		$contribution_default_logo = get_option('contribution_default_logo');
		$contribution_default_full_name = get_option('contribution_default_full_name');
		$contribution_default_title = get_option('contribution_default_title');
		$contribution_default_phone = get_option('contribution_default_phone');
		$contribution_default_email = get_option('contribution_default_email');
		$contribution_default_web_address = get_option('contribution_default_web_address');
	
		$logo_image_url = ($custom['logo_image_url'][0] != '') ? $custom['logo_image_url'][0] : $contribution_default_logo;
		$your_title = ($custom['your_title'][0] != '') ? $custom['your_title'][0] : $contribution_default_full_name;
		$your_full_name = ($custom['your_full_name'][0] != '') ? $custom['your_full_name'][0] : $contribution_default_title;
		$your_phone = ($custom['your_phone'][0] != '') ? $custom['your_phone'][0] : $contribution_default_phone;
		$your_email = ($custom['your_email'][0] != '') ? $custom['your_email'][0] : $contribution_default_email;
		$your_web_address = ($custom['your_web_address'][0] != '') ? $custom['your_web_address'][0] : $contribution_default_web_address;
?>
		<label>Logo Image Url : <input type="text" name="logo_image_url" id="logo_image_url" value="<?php echo $logo_image_url; ?>" size="50"/></label>
		<input type="button" class="button button-primary" id="set_logo_image_btn" value="Set Image"/>
		<input type="button" class="button button-primary" id="remove_logo_image_btn" value="Remove Image"/>
		<p></p>
		<p></p>
		<div id="logo_image_section">
		<?php
			if( $logo_image_url != '' ){
		?>
			<img src="<?php echo $logo_image_url; ?>"/>
		<?php
			}
		?>
		</div>
		<p></p>
		<p></p>
		<label><span style="display:inline-block;width:90px;">Your Full Name </span>: <input type="text" name="your_full_name" id="your_full_name" value="<?php echo $your_full_name; ?>" size="25"/></label>
		<p></p>
		<label><span style="display:inline-block;width:90px;">Title  </span>: <input type="text" name="your_title" id="your_title" value="<?php echo $your_title; ?>" size="25"/></label>
		<p></p>
		<label><span style="display:inline-block;width:90px;">Phone  </span>: <input type="text" name="your_phone" id="your_phone" value="<?php echo $your_phone; ?>" size="25"/></label>
		<p></p>
		<label><span style="display:inline-block;width:90px;">Email  </span>: <input type="text" name="your_email" id="your_email" value="<?php echo $your_email; ?>" size="25"/></label>
		<p></p>
		<label><span style="display:inline-block;width:90px;">Web address  </span>: <input type="text" name="your_web_address" id="your_web_address" value="<?php echo $your_web_address; ?>" size="25"/>&nbsp;&nbsp;&nbsp;<span style="color:#AAA;font-style:italic;">don't forget to enter 'http://'</span></label>
<?php
	}
	
	function member_logo_save(){
		global $post;
		
		$logo_image_url = $_POST['logo_image_url'];
		$your_title = $_POST['your_title'];
		$your_full_name = $_POST['your_full_name'];
		$your_phone = $_POST['your_phone'];
		$your_email = $_POST['your_email'];
		$your_web_address = $_POST['your_web_address'];
		
		update_post_meta($post->ID, "logo_image_url", $logo_image_url);
		update_post_meta($post->ID, "your_title", $your_title);
		update_post_meta($post->ID, "your_full_name", $your_full_name);
		update_post_meta($post->ID, "your_phone", $your_phone);
		update_post_meta($post->ID, "your_email", $your_email);
		update_post_meta($post->ID, "your_web_address", $your_web_address);
	}
?>