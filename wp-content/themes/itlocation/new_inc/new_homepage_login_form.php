<div id="jquery-live-validation-login">
	<div id="login-part">
		<form class="" name="mylogin-form" id="mylogin-form" action="" method="post">
			<?php wp_nonce_field('login-itlocation', 'login-security'); ?>
            <div class="form-group">
                <div class="alert alert-danger alert-error" style="display: none"><?php _e('Username or Password is wrong.', 'twentyten'); ?></div>
            </div>
            <div class="form-group">
                <label class="sr-only"><?php _e('Username', 'twentyten') ?></label>
                <input type="text" id="username" name="username" class="form-control" placeholder="<?php _e('Username', 'twentyten') ?>">
            </div>
            <div class="form-group">
                <label class="sr-only"><?php _e('Password', 'twentyten') ?></label>
                <input type="password" id="user_password" name="user_password" class="form-control" placeholder="<?php _e('Password', 'twentyten') ?>">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" id="login-btn" value="<?php _e('Sign in', 'twentyten') ?>" />
                <img id="login-form-process-img" class="hide" src="<?php echo get_template_directory_uri(); ?>/images/loading.gif" />
                <label class="btn btn-link forgot"><?php _e('Forget password', 'twentyten') ?> ? <a href="#" id="go-forgot-pw"><?php _e('Click here', 'twentyten') ?>.</a></label>
            </div>
		</form>
	</div>
	<div id="forgot-password-part" style="display: none">
		<form name="forgot-password-form" id="forgot-password-form" action="" method="post">
			<?php wp_nonce_field('forgot-password-itlocation', 'forgot-password-itlocation-security'); ?>
			<div class="form-group">
				<div class="alert alert-error alert-success" style="display: none"><?php _e('Please check your email.', 'twentyten'); ?></div>
			</div>
            <div class="form-group">
                <label class=""><?php _e('Username Or Email', 'twentyten') ?></label>
                <input type="text" id="username_email" name="username_email" class="form-control" placeholder="<?php _e('Type your Username or Email', 'twentyten') ?>">
            </div>
			<div class="form-group">
                <input type="submit" class="btn btn-primary" id="forgot-password-btn" value="<?php _e('Proceed', 'twentyten') ?>" />
                <img id="forget-password-form-process-img" class="hide" src="<?php echo get_template_directory_uri(); ?>/images/loading.gif" />
                <input type="button" class="btn btn-link" id="go-login-from-forgot-pw-btn" value="<?php _e('Go back to Signin', 'twentyten') ?>" />
			</div>
		</form>
	</div>
</div>