<div class="wrap nosubsub">	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>	<h2>Contribution Default Meta Value</h2>	<p></p><?php	if( isset($_POST['act']) && $_POST['act'] == 'save' ) {		$logo_image_url = isset($_POST['logo_image_url']) ? $_POST['logo_image_url'] : '';		$your_full_name = isset($_POST['your_full_name']) ? $_POST['your_full_name'] : '';		$your_title = isset($_POST['your_title']) ? $_POST['your_title'] : '';		$your_phone = isset($_POST['your_phone']) ? $_POST['your_phone'] : '';		$your_email = isset($_POST['your_email']) ? $_POST['your_email'] : '';		$your_web_address = isset($_POST['your_web_address']) ? $_POST['your_web_address'] : '';				delete_option('contribution_default_logo');		delete_option('contribution_default_full_name');		delete_option('contribution_default_title');		delete_option('contribution_default_phone');		delete_option('contribution_default_email');		delete_option('contribution_default_web_address');				add_option('contribution_default_logo', $logo_image_url);		add_option('contribution_default_full_name', $your_full_name);		add_option('contribution_default_title', $your_title);		add_option('contribution_default_phone', $your_phone);		add_option('contribution_default_email', $your_email);		add_option('contribution_default_web_address', $your_web_address);?>	<div id="message" class="updated"><p>Contribution Default Values <strong>saved</strong>.</p></div><?php	}		$contribution_default_logo = get_option('contribution_default_logo');	$contribution_default_full_name = get_option('contribution_default_full_name');	$contribution_default_title = get_option('contribution_default_title');	$contribution_default_phone = get_option('contribution_default_phone');	$contribution_default_email = get_option('contribution_default_email');	$contribution_default_web_address = get_option('contribution_default_web_address');		if( $contribution_default_web_address == '' ) {		$contribution_default_web_address = 'http://';	}?>	<form method="post" action="">		<input type="hidden" name="act" value="save" />		<label>Logo Image Url : <input type="text" name="logo_image_url" id="logo_image_url" value="<?php echo $contribution_default_logo; ?>" size="50"/></label>		<input type="button" class="button" id="set_logo_image_btn" value="Set Image"/>		<input type="button" class="button" id="remove_logo_image_btn" value="Remove Image"/>		<p></p>		<p></p>		<div id="logo_image_section">		<?php			if( $contribution_default_logo != '' ){		?>			<img src="<?php echo $contribution_default_logo; ?>"/>		<?php			}		?>		</div>		<p></p>		<p></p>		<label><span style="display:inline-block;width:90px;">Your Full Name </span>: <input type="text" name="your_full_name" id="your_full_name" value="<?php echo $contribution_default_full_name; ?>" size="25"/></label>		<p></p>		<label><span style="display:inline-block;width:90px;">Title  </span>: <input type="text" name="your_title" id="your_title" value="<?php echo $contribution_default_title; ?>" size="25"/></label>		<p></p>		<label><span style="display:inline-block;width:90px;">Phone  </span>: <input type="text" name="your_phone" id="your_phone" value="<?php echo $contribution_default_phone; ?>" size="25"/></label>		<p></p>		<label><span style="display:inline-block;width:90px;">Email  </span>: <input type="text" name="your_email" id="your_email" value="<?php echo $contribution_default_email; ?>" size="25"/></label>		<p></p>		<label><span style="display:inline-block;width:90px;">Web address  </span>: <input type="text" name="your_web_address" id="your_web_address" value="<?php echo $contribution_default_web_address; ?>" size="25"/>&nbsp;&nbsp;&nbsp;<span style="color:#AAA;font-style:italic;">don't forget to enter 'http://'</span></label>		<p></p>		<input type="submit" class="button button-primary" value="Save" />	</form></div>