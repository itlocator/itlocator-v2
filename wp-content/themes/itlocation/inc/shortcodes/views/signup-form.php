<?php  $my_recaptcha_public_key = get_option('itlocation_generals_recaptcha_public_key'); ?>

<div class="" id="jquery-live-validation-signup">

<form name="mysignup-form" id="mysignup-form" action="" method="post">
	 
        <?php wp_nonce_field('signup-itlocation', 'signup-security'); ?>
		
        <?php wp_nonce_field('check-email-itlocation', 'check-email-security'); ?>
		
        <?php wp_nonce_field('check-username-itlocation', 'check-username-security'); ?>
		
        <input type="hidden" name="member_level" id="member_level" value="0" />
        
        <div class="form-group">
            <label class="sr-only" for="company_name"><?php _e('Company Name', 'twentyten') ?></label>
            <div class="">
                <input type="text" id="company_name" name="company_name" class="form-control" placeholder="<?php _e('Company Name', 'twentyten') ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="sr-only" for="user_email"><?php _e('User Email', 'twentyten') ?></label>
            <div class="">
                <input type="text" id="user_email" name="user_email" class="form-control" placeholder="<?php _e('User Email', 'twentyten') ?>" >
                <div class="loading pull-left" style="display:none"></div>
            </div>
        </div>

        <div class="form-group">
            <label class="sr-only" for="username"><?php _e('Username', 'twentyten') ?></label>
            <div class="">
                <input type="text" id="username" name="username" class="form-control" placeholder="<?php _e('Username', 'twentyten') ?>">
                <div class="loading pull-left" style="display:none"></div>
            </div>
        </div>

        <div class="form-group">
            <label class="sr-only" for="user_password"><?php _e('Password', 'twentyten') ?></label>
            <div class="">
                <input type="password" id="user_password" name="user_password" class="form-control" placeholder="<?php _e('Type your password', 'twentyten') ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="sr-only" for="coupon_code"><?php _e('Coupon Code', 'twentyten') ?></label>
            <div class="">
                <input type="text" id="coupon_code" name="coupon_code" class="form-control" placeholder="<?php _e('Coupon Code', 'twentyten') ?>">
            </div>
        </div>

        <?php if ($my_recaptcha_public_key) { ?>

            <div class="form-group">
                <label class="sr-only"><?php _e('Security check', 'bootstrapwp') ?></label>
                <div class="">
                    <?php 
echo recaptcha_get_html($my_recaptcha_public_key); ?>
                    <div class="clearfix"></div>
                    <div class="alert alert-error span8 padding-only-bottom-5 padding-only-top-5 margin-only-top-10 margin-only-left-3 margin-only-bottom-0" style="display: none"></div>

                </div>

            </div>

            <?php } ?>

        <div class="clearfix"></div>

        <div class="form-group">
            <label class="sr-only">&nbsp;</label>
            <div class="">
                <input type="submit" class="btn btn-primary" id="signup-btn" value="<?php _e('Register now!', 'twentyten') ?>" />
				<img id="singn-up-form-process-img" class="hide" src="<?php echo get_template_directory_uri(); ?>/images/loading.gif" />
            </div>
        </div>

    </form>

</div>

<div id="signup-paypal-1" class="display-none">

    <?php

    echo do_shortcode(stripslashes(get_option('itlocation_payment_shortcode_paypal_level_1')));

    ?>

</div>

<div id="signup-paypal-2" class="display-none">

    <?php

    echo do_shortcode(stripslashes(get_option('itlocation_payment_shortcode_paypal_level_2')));

    ?>

</div>