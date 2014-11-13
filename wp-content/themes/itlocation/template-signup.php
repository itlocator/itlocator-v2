<?php
/* Template Name: Signup Page */
if (is_user_logged_in()):
    wp_redirect(get_site_url());
endif;
?>

<?php //@get_header('signup'); 
 get_header(); 
?>
</div>
</div>
</div>
</div>

<script type="text/javascript" src="<?php echo get_bloginfo('template_url') ?>/new_inc/js/new_search_map.js"></script>

<div class="container">

<!-- Map pins starts -->  
        <!--<div class="media map-info">
            <div class="media-body">
                <h4 class="media-heading">Visionpace-IT</h4>
                <p class="noMargin">Visionpace has provided IT services since 1992.</p>
            </div>
        </div>
        
        <div class="media map-info member">
            <div class="pull-left"><a href="" class="img-wrap"><img src="http://placehold.it/60x60"></a></div>
            <div class="media-body">
                <h4 class="media-heading">Visionpace-IT</h4>
                <p class="noMargin">Visionpace has provided IT services since 1992.</p>
            </div>
        </div>
        
        <div class="media map-info premium">
            <div class="pull-left"><a href="" class="img-wrap"><img src="http://placehold.it/80x80"></a></div>
            <div class="media-body">
                <h4 class="media-heading">Visionpace-IT</h4>
                <p class="noMargin">Visionpace has provided IT services since 1992. From custom software ...</p>
                <ul class="list-inline">
                	<li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star-o"></i></li>
                </ul>
            </div>
        </div> -->
        <!-- Map pins ends -->  
        
	<h2 class="aCenter">Select Your Membership Level &amp; Join 1,200 IT Providers</h2>
    
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
                    <a href="#signup-modal" data-toggle="modal" class="btn btn-success btn_for_modal" role="button" val="free"><i class="fa fa-shopping-cart"></i> Sign Up</a>
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
                    <a href="#signup-modal" data-toggle="modal" class="btn btn-success btn_for_modal" role="button" val="member"><i class="fa fa-shopping-cart"></i> Sign Up</a>
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
                        <li><?php _e('Publish Content in Premium Position', 'twentyten') ?></li>        
                        <li><?php _e('Prominent Position in Listings/Searches', 'twentyten') ?></li>        
                        <li><?php _e('Unlimited Collateral & Case Studies', 'twentyten') ?></li>        
                        <li><?php _e('Promote Customer Ratings', 'twentyten') ?></li>        
                        <li><?php _e('Maximize SEO Value', 'twentyten') ?></li>        
                        <li><?php _e('Direct & Promote IT Locator Research', 'twentyten') ?></li>        
                        <li><?php _e('Participate in All IT Locator Promotions', 'twentyten') ?></li>
        
                    </ul>
                    
                    <a href="#signup-modal" data-toggle="modal" class="btn btn-success btn_for_modal" role="button" val="premium"><i class="fa fa-shopping-cart"></i> Sign Up</a>
                </div><!-- /.price-box -->
            </div><!-- /.col-md-3 -->
        </div><!-- /.row -->
    </section><!-- /#pricing -->
    
</div>

<?php get_footer(); ?>