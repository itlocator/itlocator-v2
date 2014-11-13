<!--<div id="subscriber-alert-itlocation-modal" class="modal hide fade" role="dialog" aria-labelledby="subscriber-alert-itlocation-modal-label" aria-hidden="true">
    <div class="modal-body">
        <h3 id="subscriber-alert-itlocation-modal-label"><?php _e('Next Step: Confirm Your Email', 'twentyten'); ?></h3>
        <p><?php _e('Thank you for subscribing to IT Locator.  A confirmation email has been sent to <span id="subscriber-alert-email"></span>.  Click on the confirmation link in the email to activate your account.', 'twentyten'); ?></p>
        <p><a class="btn pull-right" id="subscriber_alert_close_btn">Close</a></p>
    </div>
</div>-->

<?php if( !is_user_logged_in() ){ ?>
    
    <!-- Modal -->
    <div class="modal fade" id="signup-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog pop-up">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="signup-modal-label"><?php _e('Signup for Basic IT Locator Listing – It’s Free!', 'twentyten') ?></h4>
                </div>
                <div class="modal-body">
                   
                    <div class="row">
                        <div class="col-sm-12">
                         <h5 class="noMargin dMx"><?php _e('Become a Member of IT Locator and Begin Expanding the Reach of Your IT VAR business.', 'twentyten') ?></h5>
                            <?php echo do_shortcode('[signup-itlocation/]'); ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <p><?php _e('Login to IT Locator to modify your profile and contribute content to the network.', 'twentyten') ?></p>
                    <a class="" href="#user-login" id="go-login-btn" data-dismiss="modal" data-toggle="modal" role="button"><?php _e('Login', 'twentyten') ?></a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!--<div id="claim-modal1" class="modal hide fade" role="dialog" aria-labelledby="claim-modal-label" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="claim-modal-label"><?php _e('Claim Listing', 'twentyten') ?></h3>
    </div>
    <div class="modal-body" style="text-align:center;">
        <h3>What's your company name?</h3>
		<input type="text" id="footer-claim-txt" value=""/>
		 <?php wp_nonce_field('send-email-itlocation', 'send-claim-email-itlocation-security'); ?>
		<p>Select your company listing from the search results on the next page</p>
		<input type="button" id="footer-claim-btn" class="btn btn-success" value="Search Listings"/>
		<p></p>
		<div id="footer-claim-info" style="text-align:left"></div>
		<div id="footer-claim-loading" class="hide">
			<img src="<?php echo get_bloginfo('template_url') ?>/images/loading-middle.gif" class="width-50 height-50">
		</div>
		<script language="javascript">
			jQuery(document).ready(function(){
				jQuery('.claim-footer-btn').click(function(){ 
					jQuery('#footer-claim-info').html( jQuery("#footer-claim-loading").html() );
					
					jQuery.ajax({
						type : "post",
						dataType : "html",
						url : '<?php  echo get_template_directory_uri(); ?>/ajax/footer-claim.php',
						data: {
							'company' : jQuery('#footer-claim-txt').val()						
						},
						success: function(response) {
							// if( response == -1 ){
								// jQuery('#footer-claim-info').html('<p style="color:red;font-weight:bold;">What\'s Your Company Name?</p>');
							// } else {
								// jQuery('#footer-claim-info').html('<a style="color:blue;font-weight:bold;" href="' + response + '" target="_blank">Please Click to Claim Listing</p>');			
							// }
							jQuery('#footer-claim-info').html( response );
						}
					});
				});
			});
		</script>
    </div>
</div>-->

<!-- Modal -->
<div class="modal fade" id="claim-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog pop-up">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php _e('Claim Listing', 'twentyten') ?></h4>
      </div>
      <div class="modal-body">
        
        <div class="form-group">
        	<label>What's your company name?</label>
			<input type="text" id="footer-claim-txt" class="form-control" value=""/>
        </div>
		 <?php wp_nonce_field('send-email-itlocation', 'send-claim-email-itlocation-security'); ?>
		<div class="form-group">
			<input type="button" id="footer-claim-btn" class="btn btn-success" value="Search Listings"/>
        </div>
		
		<div id="footer-claim-info" style="text-align:left"></div>
		<div id="footer-claim-loading" class="hide">
			<img src="<?php echo get_bloginfo('template_url') ?>/images/loading-middle.gif" class="width-50 height-50">
		</div>
		<script language="javascript">
			jQuery(document).ready(function(){
				jQuery('#footer-claim-btn').click(function(){ 
					jQuery('#footer-claim-info').html( jQuery("#footer-claim-loading").html() );
					
					jQuery.ajax({
						type : "post",
						dataType : "html",
						url : '<?php  echo get_template_directory_uri(); ?>/ajax/footer-claim.php',
						data: {
							'company' : jQuery('#footer-claim-txt').val()						
						},
						success: function(response) {
							 if( response == -1 ){
								 jQuery('#footer-claim-info').html('<p style="color:red;font-weight:bold;">What\'s Your Company Name?</p>');
							 } else {
								 jQuery('#footer-claim-info').html('<a style="color:blue;font-weight:bold;" href="' + response + '" target="_blank">Please Click to Claim Listing</p>');			
							 }
							jQuery('#footer-claim-info').html( response );
						}
					});
				});
			});
		</script>
    </div>
      <div class="modal-footer">
        <p>Select your company listing from the search results on the next page</p>
      </div>
    </div>
  </div>
</div>

<!-- /* Highlight area starts */ -->
    <div class="is-section is-highlight">
    	<div class="pattren-overlay"></div>
    	<div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="page-title white">We're banding local and regional IT services providers <br>together to collectively strengthen our industry</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="highlight-wrap aCenter">
                    	<h2 class="page-title">Claim your business lisitng.</h2>
                        <p>Many of the benefits of Itlocator are free. Update your business details, including hours, payment options and more.</p>
                        <a href="#claim-modal" role="button" class="btn btn-success" data-toggle="modal"><?php _e('Claim Listing', 'twentyten') ?></a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="highlight-wrap aCenter">
                    	<h2 class="page-title"> Join <span><?php echo get_subscribers_number(); ?></span> IT Providers</h2>
                        <p>Select Your Membership Level & Join <?php echo get_subscribers_number(); ?> IT Providers. Become a Member of IT Locator and Begin Expanding the Reach of Your IT VAR business.</p>
                        
                         <?php
							$tmp_url = '';
							if( get_option( 'itlocation_generals_signup_page' ) ){
								$pid = get_option( 'itlocation_generals_signup_page' );
								$tmp_url = get_permalink( $pid );
							} if( !is_user_logged_in() ){ ?>
                            
							<a href="<?php echo $tmp_url; ?>" class="btn btn-success"><?php _e('Create a account', 'twentyten') ?></a>
                            
						<?php } ?>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="highlight-wrap aCenter">
                    	<h2 class="page-title">Advertise with us</h2>
                        <!-- <p>Reach more customers and expand your exposure. We create a custom-tailored programs for your business to generate more leads, broaden your thought leadership, and grow your reach in the markets you serve.</p>-->
                        <p>Reach more customers and expand your exposure. We create custom-tailored programs to generate leads, broaden leadership, and grow your reach.</p>
                        
                        <a href="http://www.itlocator.com/advertising" class="btn btn-success">Try Now!</a>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /* Highlight area Ends */ -->

<!-- /* Footer area starts */ -->
    <div class="is-section is-footer">
    	<div class="container footer-container">
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-7">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="footer-wrap">
                                <h2 class="page-title"><?php _e('Site Navigation', 'twentyten') ?></h2>
                                <?php
									wp_nav_menu(array(
										'theme_location' => 'footer_menu_1',
										'menu_class' => 'footer-nav',
										'depth' => 1,
										'walker' => new walkerNavMenuBottomItLocation,
									));
								?>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="footer-wrap">
                                <h2 class="page-title"><?php _e('Why IT Locator', 'twentyten') ?></h2>
                                <?php
									wp_nav_menu(array(
										'theme_location' => 'footer_menu_2',
										'menu_class' => 'footer-nav',
										'depth' => 1,
										'walker' => new walkerNavMenuBottomItLocation,
									));
								?>
                            </div>
                        </div>
                        <!-- /* Clearfix starts */ -->
                        <div class="clearfix visible-sm-block"></div>
                        <!-- /* Clearfix ends */ -->
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="footer-wrap">
                                <h2 class="page-title"><?php _e('Privacy Links', 'twentyten') ?></h2>
                                <?php
									wp_nav_menu(array(
										'theme_location' => 'footer_menu_3',
										'menu_class' => 'footer-nav',
										'depth' => 1,
										'walker' => new walkerNavMenuBottomItLocation,
									));
								?>
                            </div>
                        </div>
                    </div>
                    <!-- /* Social icons starts */ -->
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="list-inline social-nav">
                                <li><a href="http://www.google.com/+Itlocator" target="_blank"><i class="fa fa-google"></i></a></li>
                                <li><a href="http://www.twitter.com/ITLocator" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="http://www.linkedin.com/company/it-locator" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div><!-- /* Social icons ends */ -->
                    
                </div>
                
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <div class="footer-wrap">
                        <h2 class="page-title"><?php _e('Have questions', 'twentyten') ?></h2>
                        <div class="number-wrap">
                            <h2 class="number"><?php echo get_option('itlocation_generals_contact_us_phone_number'); ?></h2>
                            <h2 class="number">301.841.4663 <small> (International)</small></h2>
                        </div>
                    </div>
                    
                    <div class="footer-wrap">
                        <h2 class="page-title"><?php _e('Get The Latest on Technology', 'twentyten') ?></h2>
                        <div class="form-group newsletter">
                            <div class="newsletter-wrap">
                                <form action="" method="POST" class="form-inline" id="subscriber_itlocation_form">
									<?php wp_nonce_field('subscribe-itlocation', 'subscribe-itlocation-security'); ?>
                                    <input type="text" value="" name="subscriber_email" class="form-control subscriber_email" placeholder="Enter email address">
                                    <button type="submit"name="subscriber_btn" class="newsletter-btn"><span class="fa fa-envelope"></span></button>
                                </form>
                            </div>

                            <span class="help-block"><?php _e('Sign up to receive the latest technology news and opinions from our community of IT experts', 'twentyten') ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer-logo aCenter">
                    	<img id="logo-header" src="<?php echo get_bloginfo('template_url') ?>/img/footer-logo.png" alt="ITL Logo">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright">
                    	<p>Google<sup>&reg;</sup> is a registered mark owned by Google, Inc. Nothing in our tagline shall be construed as an endorsement by Google.Inc. of the products or services offered by IT Locator.</p>
                        <p>&copy; <span class="font-2">2009-2014</span> Itlocator. All rights reserved</p>
                    </div>
                </div>
            </div>
        </div>
        
    </div><!-- /* Footer area Ends */ -->
    
    <!-- Modal -->
    <div class="modal fade" id="subscriber-alert-itlocation-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog pop-up verify-email">
            <div class="modal-content">
                <div class="modal-body">
                    <h3 id="subscriber-alert-itlocation-modal-label"><?php _e('Next Step: Confirm Your Email', 'twentyten'); ?></h3>
                    <p><?php _e('Thank you for subscribing to IT Locator.  A confirmation email has been sent to <span id="subscriber-alert-email"></span>.  Click on the confirmation link in the email to activate your account.', 'twentyten'); ?></p>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
       
	<?php wp_footer(); ?>
	<!-- Footer script starts  -->
        <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script> -->
        <!-- Latest compiled and minified JavaScript -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>        
       <!-- <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>  -->          
        <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,700' rel='stylesheet' type='text/css'>
        <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
</body>
</html>