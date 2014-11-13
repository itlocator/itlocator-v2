<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
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
							<!-- <h2 class="aCenter" style="margin-bottom:40px; font-weight:normal; text-transform:uppercase; font-size:4em">STAY TUNED<br><span style=" font-size:0.6em">OUR WEBSITE IS Relaunching Soon</span></h2>-->
							<?php while ( have_posts() ) : the_post(); ?>
								<?php get_template_part( 'content', 'page' ); ?>
								<?php //comments_template( '', true ); ?>
							<?php endwhile; // end of the loop. ?>
						
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
								<h4 style="color:#015888; text-transform:uppercase;">The Fastest Growing online Directory of Local it Experts</h4>
								<p style="color:#818181; font-size:14px;">This super awesome website is not quite ready, but its gonna rock your world. Give me your email address and you will be the first to know when it's ready. In the meantime, follow me on facebook or twitter to hear the lastest news. Thanks!</p>
							</div>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- Highlight box ends -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>