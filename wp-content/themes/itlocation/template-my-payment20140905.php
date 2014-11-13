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

<article>
    <div class="row-fluid">
        <div class="span12" id="user-payment">
            <div id="pricing-select" style="display: inline">
                <div class="span4 pricing-table animate go-up pricing-free">
                    <ul>
                        <li class="pricing-header-row-1">
                            <div class="package-title">
                                <h2 class="no-bold"><?php _e('Free Listing', 'twentyten') ?></h2>
                            </div>
                        </li>
                        <li class="pricing-header-row-2 pricing-content-row-odd">
                            <div class="package-price position-relative">
                                <h1 class="freeuser">FREE</h1>
                                <img src="<?php echo get_bloginfo('template_url') ?>/images/marker-free.png" class="pull-right position-absolute top-0 right-10">
                            </div>
                        </li>
                        <li class="pricing-content-row-even">
                            <?php _e('Single Location', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-odd">
                            <?php _e('Basic Services & Industry Tags (3/3)', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-even">
                            <?php _e('Increase SEO Value of Your Website', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-odd">
                            <?php _e('Participate in IT Locator Research', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-even">
                            <?php _e('Stay up on Latest IT VAR News & Research', 'twentyten') ?>
                        </li>
                        <li class="pricing-footer">
                            <button class="btn go-payment-panel" disabled="disabled" val="free"><i class="icon-shopping-cart"></i> Next</button>
                        </li>
                    </ul>
                </div>            
                <div class="span4 pricing-table animate go-up pricing-member">
                    <ul>
                        <li class="pricing-header-row-1">
                            <div class="package-title">
                                <h2 class="no-bold">Member</h2>
                            </div>
                        </li>
                        <li class="pricing-header-row-2 pricing-content-row-odd">
                            <div class="package-price position-relative">
                                <h1 class="no-bold member">$395<span class="cents"> /yr</span></h1>
                                <img src="<?php echo get_bloginfo('template_url') ?>/images/marker-basic.png" class="pull-right position-absolute top-0 right-10">
                            </div>
                        </li>
                        <li class="pricing-content-row-even">
                            <?php _e('Profile 3 Locations', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-odd">
                            <?php _e('Publish Content to Site & VAR Network', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-even">
                            <?php _e('Expand Services & Industry Tags (10/5)', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-odd">
                            <?php _e('Add Collateral & Case Studies (3/1)', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-even">
                            <?php _e('Add Certifications & Partner Tags (3/5)', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-odd">
                            <?php _e('Expand SEO Value', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-even">
                            <?php _e('Participate in Selected IT Locator Promotions', 'twentyten') ?>
                        </li>
                        <li class="pricing-footer">
                            <button class="btn go-payment-panel" <?php echo ($current_company->user_role == 1 ) ? 'disabled="disabled"' : '' ?> val="member"><i class="icon-shopping-cart"></i> Next</button>
                        </li>
                    </ul>
                </div>
                <div class="span4 pricing-table animate go-up pricing-premium">
                    <ul>
                        <li class="pricing-header-row-1">
                            <div class="package-title">
                                <h2 class="no-bold">Premium</h2>
                            </div>
                        </li>
                        <li class="pricing-header-row-2 pricing-content-row-odd">
                            <div class="package-price position-relative">
                                <h1 class="no-bold premium">$595<span class="cents"> /yr</span></h1>
                                <img src="<?php echo get_bloginfo('template_url') ?>/images/marker-premium.png" class="pull-right position-absolute top-0 right-10">
                            </div>
                        </li>
                        <li class="pricing-content-row-even">
                            <?php _e('Profile All Locations', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-odd">
                            <?php _e('Unlimited Services & Industry Tags', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-even">
                            <?php _e('Publish Content in Premium Position', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-odd">
                            <?php _e('Prominent Position in Listings/Searches', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-even">
                            <?php _e('Unlimited Collateral & Case Studies', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-odd">
                            <?php _e('Promote Customer Ratings', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-even">
                            <?php _e('Maximize SEO Value', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-odd">
                            <?php _e('Direct & Promote IT Locator Research', 'twentyten') ?>
                        </li>
                        <li class="pricing-content-row-even">
                            <?php _e('Participate in All IT Locator Promotions', 'twentyten') ?>
                        </li>
                        <li class="pricing-footer">
                            <button class="btn go-payment-panel" <?php echo ($current_company->user_role == 2 ) ? 'disabled="disabled"' : '' ?> val="premium"><i class="icon-shopping-cart"></i> Next</button>
                        </li>
                    </ul>
                </div>
            </div>
            <?php
            if ($current_company->user_role != 1)
                echo do_shortcode('[payment-form-itlocation id="member" level="1"/]');
            if ($current_company->user_role != 2)
                echo do_shortcode('[payment-form-itlocation id="premium" level="2"/]');
            ?>
            <br/><br/>
            <a class="btn btn-small btn-success font-size-14" id="go-pricing-select" href="#" style="display: none;">Return</a>
            <?php

            if ($current_company->user_role == 0){
			echo	"<div id='upgradecoupon-paypal-1' class='display-none'>";
                //echo do_shortcode('[payment-form-itlocation id="member" level="1"/]');
			echo do_shortcode(stripslashes(get_option('itlocation_payment_shortcode_paypal_level_1')));
			
			echo "<div>";
			}

            if ($current_company->user_role == 1){
			echo	"<div id='upgradecoupon-paypal-2' class='display-none'>";
            #echo do_shortcode('[payment-form-itlocation id="premium" level="2"/]');
			echo do_shortcode(stripslashes(get_option('itlocation_payment_shortcode_paypal_level_2')));
			
			echo "<div>";
			}

            ?>
        </div>
    </div>
</article>
<?php get_footer(); ?>
