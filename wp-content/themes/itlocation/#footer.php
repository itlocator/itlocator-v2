</div>
</div><!-- #main -->
<!-- the old footer-->
<div id="footer" class="visible-desktop visible-tablet margin-only-top-20">
    <div class="container contCustom">
        <div class="row-fluid">
            <div class="span6">
                <div class="row-fluid">
                    <div class="span4">
                        <h5><?php _e('Site Navigation', 'twentyten') ?></h5>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer_menu_1',
                            'menu_class' => 'unstyled footNav',
                            'depth' => 1,
                            'walker' => new walkerNavMenuBottomItLocation,
                        ));
                        ?>
                    </div>
                    <div class="span4">
                        <h5><?php _e('Why IT Locator', 'twentyten') ?></h5>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer_menu_2',
                            'menu_class' => 'unstyled footNav',
                            'depth' => 1,
                            'walker' => new walkerNavMenuBottomItLocation,
                        ));
                        ?>
                    </div>
                    <div class="span4">
                        <h5><?php _e('Privacy Links', 'twentyten') ?></h5>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer_menu_3',
                            'menu_class' => 'unstyled footNav',
                            'depth' => 1,
                            'walker' => new walkerNavMenuBottomItLocation,
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="span2 subscribe-form position-relative">
                <h5><?php _e('Get The Latest on Technology', 'twentyten') ?></h5>
                <span class="font-color-f3f3f3 font-size-12"><?php _e('Sign up to receive the latest technology news and opinions from our community of IT experts', 'twentyten') ?></span>
                <div class="clearfix"></div>
                <?php echo do_shortcode('[subscriber-itlocation horizontal="1" text_class="subscriber_email input-block-level"/]'); ?>
            </div>
            <div class="span4">
                <h4 class="font-size-14 font-color-222"><?php _e('Contact us', 'twentyten') ?></h4>
                <h2><?php echo get_option('itlocation_generals_contact_us_phone_number'); ?></h2>
                <a href="#claim-modal" role="button" class="btn btn-success pull-left claim-btn" data-toggle="modal"><?php _e('Claim listing', 'twentyten') ?></a>
                <?php
                $tmp_url = '';
                if (get_option('itlocation_generals_signup_page')) {
                    $pid = get_option('itlocation_generals_signup_page');
                    $tmp_url = get_permalink($pid);
                }
                if (!is_user_logged_in()) {
                    ?>
                    <a href="<?php echo $tmp_url; ?>" class="btn"><?php _e('Create a account', 'twentyten') ?></a>
                    <?php
                }
                ?>
                <div class="clearfix"></div>
                <a href="<?php echo get_site_url(); ?>" class="logo" title="IT Locator"><img src="<?php echo get_bloginfo('template_url') ?>/images/logo-white-blacktag.png" alt="IT Locator"></a>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div id="subscriber-alert-itlocation-modal" class="modal hide fade" role="dialog" aria-labelledby="subscriber-alert-itlocation-modal-label" aria-hidden="true">
    <div class="modal-body">
        <h3 id="subscriber-alert-itlocation-modal-label"><?php _e('Next Step: Confirm Your Email', 'twentyten'); ?></h3>
        <p><?php _e('Thank you for subscribing to IT Locator.  A confirmation email has been sent to <span id="subscriber-alert-email"></span>.  Click on the confirmation link in the email to activate your account.', 'twentyten'); ?></p>
        <p><a class="btn pull-right" id="subscriber_alert_close_btn">Close</a></p>
    </div>
</div>

<div id="copy-right" class="bg-color-7f7f7f">
    <div class="container contCustom copy-right">
        <div class="row-fluid">
            <div class="pull-right">© 2009-2014 <a href="<?php echo get_site_url(); ?>" class="my_comany_name font-color-333">Itlocator</a>. All rights reserved</div>
        </div>
    </div>
</div>
<?php
if (!is_user_logged_in()):
    ?>
    <div id="signup-modal" class="login-modal modal hide fade" role="dialog" aria-labelledby="signup-modal-label" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="signup-modal-label"><?php _e('Signup for Basic IT Locator Listing – It’s Free!', 'twentyten') ?></h3>
        </div>
        <div class="modal-body">
            <h5><?php _e('Become a Member of IT Locator and Begin Expanding the Reach of Your IT VAR business.', 'twentyten') ?></h5>
            <div class="row-fluid">
                <div class="span12">
                    <div class="span7 bg-color-fff border-all-1-solid-cccccc border-all-radios-5 padding-all-10">
                        <h5><?php _e('New User', 'twentyten') ?></h5>
                        <?php
                        echo do_shortcode('[signup-itlocation/]');
                        ?>
                    </div>
                    <div class="span5 bg-color-fff border-all-1-solid-cccccc border-all-radios-5 padding-all-10">
                        <h5><?php _e('Login to IT Locator', 'twentyten') ?></h5>
                        <p><?php _e('Login to IT Locator to modify your profile and contribute content to the network.', 'twentyten') ?></p>
                        <a class="btn" href="#login-modal" id="go-login-btn" data-toggle="modal" role="button"><?php _e('Login', 'twentyten') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
endif;
?>
<div id="claim-modal" class="modal hide fade" role="dialog" aria-labelledby="claim-modal-label" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="claim-modal-label"><?php _e('Claim Listing', 'twentyten') ?></h3>
    </div>
    <div class="modal-body" style="text-align:center;">
        <h3>What's your company name?</h3>
		<input type="text" id="footer-claim-txt" value=""/>
		 <?php wp_nonce_field('send-email-itlocation', 'send-claim-email-itlocation-security'); ?>
		<p>Select your company listing from the search results on the next page</p>
		<p id="footer-claim-info"></p>
		<input type="button" id="footer-claim-btn" class="btn btn-success" value="Search Listings"/>
		<script language="javascript">
			jQuery(document).ready(function(){
				jQuery('#footer-claim-btn').click(function(){
					jQuery.ajax({
						type : "post",
						dataType : "html",
						url : '<?php  echo get_template_directory_uri(); ?>/ajax/footer-claim.php',
						data: {
							'company' : jQuery('#footer-claim-txt').val()						
						},
						success: function(response) {							if( response == -1 ) {								jQuery('#footer-claim-info').html('<p style="color:red;font-weight:bold;">What\'s Your Company Name?</p>');							} else {								jQuery('#footer-claim-info').html('<a style="color:blue;font-weight:bold;" href="' + response + '" target="_blank">Please Click to Claim Listing</p>');							}
						}
					});
				});
			});
		</script>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
