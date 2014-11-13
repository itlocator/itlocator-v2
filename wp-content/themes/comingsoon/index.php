<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<!-- Highlight box starts -->
		<div class="container contCustom dMx">
			<div class="row-fluid">
				<div class="span12 cBWhite xRound solid-all relative" style="padding:50px 100px; min-height:500px;">
				
					<div class="visible-desktop ribbon-blue"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ribbon.png" /></div>
					<div class="row-fluid">
						<div class="span12">
							<h2 class="aCenter" style="margin-bottom:40px; font-weight:normal; text-transform:uppercase; font-size:4em">STAY TUNED<br><span style="font-size:0.6em">The future of local technology search is relaunching soon!</span></h2>
						
						    <ul class="thumbnails timer dMxx" id="countdown">
								<li class="span3 dP-all-l aCenter" style="background:#ffae00;">
									<h2 class="days">00</h2>
									 <span class="timeRefDays">Days</span>
								</li>
								
								<li class="span3 dP-all-l aCenter" style="background:#ffae00; box-shadow: 0 0 20px #c48703 inset;">
									<h2 class="hours"> 00</h2>
									<span class="timeRefHours">Hours</span>
								</li>
								
								<li class="span3 dP-all-l aCenter" style="background:#ffae00; box-shadow: 0 0 20px #c48703 inset;">
									<h2 class="minutes"> 00 </h2>
									<span class="timeRefMinutes">Minutes</span>
								</li>
								
								<li class="span3 dP-all-l aCenter" style="background:#ffae00; box-shadow: 0 0 20px #c48703 inset;">
									<h2 class="seconds"> 00 </h2>
									<span class="timeRefSeconds">Seconds</span>
								</li>
							</ul>
							
						<div class="displayB aCenter">
							<div class="" style="width:80%; margin:auto;">
								<h4 style="color:#015888; text-transform:uppercase;">The largest, vendor-neutral, global directory of local IT experts</h4>
								<p style="color:#818181; font-size:14px;">Join the fastest growing online directory of local IT resellers and experts from around the globe to discuss the latest in technology trends, getting the best from your technology spend, contracting best practices to cover technology risks, and everything else that pertains to optimizing the use of technology for your business.

Sign up below to get notified when IT Locator launches.</p>
							</div>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- Highlight box ends -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>