<div class="row-fluid" id="jquery-live-validation-login">
    <div id="login-part">
        <form class="form-horizontal" name="mylogin-form" id="mylogin-form" action="" method="post">
            <?php wp_nonce_field('login-itlocation', 'login-security'); ?>
            <div class="control-group margin-only-bottom-10">
                <div class="controls">
                    <div class="alert alert-error margin-only-bottom-0" style="display: none"><?php _e('username and password is wrong', 'twentyten'); ?></div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="username"><?php _e('Username', 'twentyten') ?></label>
                <div class="controls">
                    <input type="text" id="username" name="username" class="span10" placeholder="<?php _e('Type your Username', 'twentyten') ?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="user_password"><?php _e('Password', 'twentyten') ?></label>
                <div class="controls">
                    <input type="password" id="user_password" name="user_password" class="span10" placeholder="<?php _e('Type your password', 'twentyten') ?>">
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input type="submit" class="btn btn-primary" id="login-btn" value="<?php _e('Sign in', 'twentyten') ?>" />
                    <div class="clearfix"></div>
                    <label class="inline"><?php _e('Forget password', 'twentyten') ?> ? <a href="#" id="go-forgot-pw"><?php _e('Click here', 'twentyten') ?>.</a></label>
                </div>
            </div>
        </form>
    </div>
    <div id="forgot-password-part" style="display: none">
        <form class="form-horizontal" name="forgot-password-form" id="forgot-password-form" action="" method="post">
            <?php wp_nonce_field('forgot-password-itlocation', 'forgot-password-itlocation-security'); ?>
            <div class="control-group margin-only-bottom-10">
                <div class="controls margin-only-left-90">
                    <div class="alert alert-error margin-only-bottom-0" style="display: none"><?php _e('Please check your email.', 'twentyten'); ?></div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label width-123" for="username_email"><?php _e('Username Or Email', 'twentyten') ?></label>
                <div class="controls margin-left-130">
                    <input type="text" id="username_email" name="username_email" class="span12" placeholder="<?php _e('Type your Username or Email', 'twentyten') ?>">
                </div>
            </div>
            <div class="control-group">
                <div class="controls margin-left-130">
                    <input type="submit" class="btn btn-primary" id="forgot-password-btn" value="<?php _e('OK', 'twentyten') ?>" />
                    <input type="button" class="btn btn-primary pull-right" id="go-login-from-forgot-pw-btn" value="<?php _e('Sign', 'twentyten') ?>" />
                </div>
            </div>
        </form>
    </div>

</div>
