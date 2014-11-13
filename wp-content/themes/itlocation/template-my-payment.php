<?php

/*

  Template Name: My Payment Page

 */

if (!is_user_logged_in()):

    wp_redirect(get_site_url());

endif;
global $current_user, $current_company;
$user = new WP_User($current_user->ID);
if ($user->roles[0] == 'administrator') {
    wp_redirect(get_site_url() . '/wp-admin');
}

get_header();
?>
<!-- Extra div starts -->
</div>
</div>
</div>
</div>
<!-- Extra div ends -->

<div class="page-sub-page inner-page">
    <div class="container">
        <div class="" id="user-payment">
        
            <section id="pricing">
                <div class="row">
                    <div class="col-md-4 col-sm-6">
                        <div class="price-box">
                            <header style="background:#b8bbbc;"><h2><?php _e('Free Listing', 'twentyten') ?></h2></header>
                            <div class="price" style="background:#6d6e71;">
                                <img src="<?php echo get_bloginfo('template_url') ?>/images/marker-free.png">
                                <figure>Free</figure>
                                <small>forever</small>
                            </div>
                            <ul>
                                <li><?php _e('Single Location', 'twentyten') ?></li>
                                <li><?php _e('Basic Services & Industry Tags (3/3)', 'twentyten') ?></li>        
                                <li><?php _e('Increase SEO Value of Your Website', 'twentyten') ?></li>        
                                <li><?php _e('Participate in IT Locator Research', 'twentyten') ?></li>        
                                <li><?php _e('Stay up on Latest IT VAR News & Research', 'twentyten') ?></li>
                                <li><i class="fa fa-remove"></i></li>
                                <li><i class="fa fa-remove"></i></li>
                                <li><i class="fa fa-remove"></i></li>
                                <li><i class="fa fa-remove"></i></li>
                            </ul>
                             <button value="free" class="btn go-payment-panel" onClick="return check_plan(this.value);" disabled="disabled" val="free"><i class="icon-shopping-cart"></i> Next</button>
                        </div><!-- /.price-box -->
                    </div><!-- /.col-md-3 -->
                    <div class="col-md-4 col-sm-6">
                        <div class="price-box">
                            <header><h2>Member</h2></header>
                            <div class="price relative">
                                 <img src="<?php echo get_bloginfo('template_url') ?>/images/marker-basic.png">
                                 <figure>$395</figure>
                                <small>/ yr</small>
                            </div>
                            <ul>
                                <li><?php _e('Profile 3 Locations', 'twentyten') ?></li>        
                                <li><?php _e('Publish Content to Site & VAR Network', 'twentyten') ?></li>        
                                <li><?php _e('Expand Services & Industry Tags (10/5)', 'twentyten') ?></li>        
                                <li><?php _e('Add Collateral & Case Studies (3/1)', 'twentyten') ?></li>        
                                <li><?php _e('Add Certifications & Partner Tags (3/5)', 'twentyten') ?></li>
                                <li><?php _e('Expand SEO Value', 'twentyten') ?></li>        
                                <li><?php _e('Participate in Selected IT Locator Promotions', 'twentyten') ?></li>
                                <li><i class="fa fa-remove"></i></li>
                                <li><i class="fa fa-remove"></i></li>
                            </ul>
                            <button value="1" name="" onClick="return check_plan(this.value);" class="btn go-payment-panel" <?php echo ($current_company->user_role == 1 ) ? 'disabled="disabled"' : '' ?> val="member"><i class="icon-shopping-cart"></i> Next</button>
                        </div><!-- /.price-box -->
                    </div><!-- /.col-md-3 -->
                    <div class="col-md-4 col-sm-6">
                        <div class="price-box promoted">
                            <header><h2>Premium</h2></header>
                            <div class="price">
                                <img src="<?php echo get_bloginfo('template_url') ?>/images/marker-premium.png" width="25">
                                <figure>$595</figure>
                                <small>/ yr</small>
                            </div>
                            <ul>
                                <li><?php _e('Profile All Locations', 'twentyten') ?></li>        
                                <li><?php _e('Unlimited Services & Industry Tags', 'twentyten') ?></li>        
                                <li> <?php _e('Publish Content in Premium Position', 'twentyten') ?></li>        
                                <li><?php _e('Prominent Position in Listings/Searches', 'twentyten') ?></li>        
                                <li><?php _e('Unlimited Collateral & Case Studies', 'twentyten') ?></li>        
                                <li><?php _e('Promote Customer Ratings', 'twentyten') ?></li>        
                                <li><?php _e('Maximize SEO Value', 'twentyten') ?></li>        
                                <li><?php _e('Direct & Promote IT Locator Research', 'twentyten') ?></li>        
                                <li><?php _e('Participate in All IT Locator Promotions', 'twentyten') ?></li>
                            </ul>
                            
                             <button value="2" name="" onClick="return check_plan(this.value);" class="btn go-payment-panel" <?php echo ($current_company->user_role == 2 ) ? 'disabled="disabled"' : '' ?> val="premium"><i class="icon-shopping-cart"></i> Next</button>
                        </div><!-- /.price-box -->
                    </div><!-- /.col-md-3 -->
                </div><!-- /.row -->
            </section><!-- /#pricing -->

        <form id="upgradeform" name="upgradeform" method="post" >
        
         <?php wp_nonce_field('upgradecoupon-itlocation', 'signup-security'); ?>
         
        <div id="upgrade-coupon" style="display:none;">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group" id="coupon-code">
                        <input type="text" id="coupon_code" name="coupon_code" class="form-control" placeholder="<?php _e('Coupon Code', 'twentyten') ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="control-group">
                        <div class="controls" id="upgrade-now" >
                            <input type="submit" class="btn btn-primary" id="signup-btn" value="<?php _e('Upgrade now', 'twentyten') ?>" />
                         <!--   <input type="submit" class="btn btn-primary" id="credit_card_update" value="<?php _e('Upgrade now Via Credit card!', 'twentyten') ?>" />  -->                          <a class="btn btn-success" id="go-pricing-select" href="#" style="display: none;">Return</a>
                            <img id="singn-up-form-process-img" class="hide" src="<?php echo get_template_directory_uri(); ?>/images/loading.gif" />
                        </div>
                    </div>
                </div>
            </div>
        </div> 
       <script>
	   function check_plan(plan)
	   {
		  // var plan=document.getElementById();
           document.getElementById("member_level_type").value=plan;
		    document.getElementById("member_level").value=plan;
	   
	   }
	   
	   </script>
        <?php 
	
		 if ($current_company->user_role == 0){
			 echo "<input type='hidden' id='member_level' value='1' level='1'>";
		}
		 if ($current_company->user_role == 1){
			 echo "<input type='hidden' id='member_level' value='2' level='2'>";
		 }
		
		?>
        <input type="hidden" id="member_level_type" value="" name="member_level_type">
        </form>
       
      
<?php
	
	/*		echo	"<div id='upgradecoupon-paypal-1' class='display-none'>";
                //echo do_shortcode('[payment-form-itlocation id="member" level="1"/]');
			echo do_shortcode(stripslashes(get_option('itlocation_payment_shortcode_paypal_level_1')));
			
			echo "<div>";
		
			echo	"<div id='upgradecoupon-paypal-2' class='display-none'>";
            #echo do_shortcode('[payment-form-itlocation id="premium" level="2"/]');
			echo do_shortcode(stripslashes(get_option('itlocation_payment_shortcode_paypal_level_2')));
			
			echo "<div>";*/
		
            ?>
  <div id="upgradecoupon-paypal-1" class="display-none">

    <?php

    echo do_shortcode(stripslashes(get_option('itlocation_payment_shortcode_paypal_level_1')));

    ?>

</div>

<div id="upgradecoupon-paypal-2" class="display-none">

    <?php

    echo do_shortcode(stripslashes(get_option('itlocation_payment_shortcode_paypal_level_2')));

    ?>

</div>  

        </div>

    </div>

</div>

<?php get_footer(); ?>

