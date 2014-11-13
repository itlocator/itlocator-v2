
<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 8]>    <html class="no-js ie8 ie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9 ie" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<meta http-equiv="content-type" content="text/html;charset=UTF-8">
<head>
		<meta charset="utf-8">
		<title><?php wp_title(); ?></title>
		<meta name="description" content="">
		<meta name="author" content="IT Locator | www.itlocator.com">
		<meta name="robots" content="index, follow">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- Styles -->
		<link id="colour-scheme" rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/bootstrap.css">
		<link id="colour-scheme" rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/bootstrap-responsive.css">
		<link id="colour-scheme" rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/style.css">
		
		<!-- Fav and touch icons -->
		<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/ico/favicon.ico">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_stylesheet_directory_uri(); ?>/img/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_stylesheet_directory_uri(); ?>/img/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="<?php echo get_stylesheet_directory_uri(); ?>/img/ico/apple-touch-icon-57-precomposed.png">
		
		<!-- JS Libs -->
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="<?php bloginfo("template_url"); ?>/js/libs/modernizr.js"></script>
		<script src="<?php bloginfo("template_url"); ?>/js/libs/selectivizr-min.js"></script>
		<script src="<?php bloginfo("template_url"); ?>/js/bootstrap/bootstrap.js"></script>
		<!-- -->
		
	
		<script src="<?php bloginfo("template_url"); ?>/js/countdown.js"></script>
	
		<script>
		
			$(document).ready(function(){
				$("#countdown").countdown({
					date: "1 Jan 2014 12:00:00",
					format: "on"
				},
				
				function() {
					// callback function
				});
			});
					
		</script>
		<script>
			$('div.formError').attr('style','');
		</script>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		
	</head>
	<body <?php body_class(); ?>>
<?php if(function_exists("wp_admin_tab")) wp_admin_tab(); ?>
	
		<!-- Absolute div starts -->
		<div class="waves visible-desktop"> </div><!-- Absolute div ends -->
		
		<!-- Top/Logo area starts -->
		<div class="topArea dMx" style="margin-top:60px;">
			<div class="container contCustom dMx">
				<div class="row-fluid">
					<!-- logo area starts -->
					<div class="span12 aCenter">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" class="logo" title="IT Locator"><img src="<?php bloginfo("template_url"); ?>/img/logo.png" alt="IT Locator" /> </a>
					</div><!-- logo area ends -->
				</div>
			</div>
		</div>
		<!-- Top/Logo area ends -->