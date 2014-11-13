<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
<!-- Footer area starts -->
	<div class="footer visible-desktop" style="background:#1b1b1b;">
		<div class="container dMx" style="margin-top:2%; max-width:50%;">
			<div class="row-fluid">
				
				<div class="span12">
					<div class="displayB aCenter dMxx">
						<div class="input-append">
						<form action="" method="post">
							<input class="input-xxlarge" id="appendedInputButton" type="text" name="email" placeholder="Enter your email address ..." style="height:54px; background:#fff; font-size:20px; border:none;">
							<input  class="btn btn-success btn-large" style="padding-top:20px; padding-bottom:20px;" type="submit" value="Notify Me" name="submit">
						</form>
						<?php 
						if(isset($_POST['submit'])){
							echo "email";
						}
						?>
						</div>
					</div>
					
					<div class="displayB aCenter tMx">
						<ul class="inline">
							<li><a href=""><img src="<?php bloginfo("template_url"); ?>/img/fb.png" /></a></li>
							<li><a href=""><img src="<?php bloginfo("template_url"); ?>/img/tw.png" /></a></li>
							<li><a href=""><img src="<?php bloginfo("template_url"); ?>/img/yt.png" /></a></li>
							<li><a href=""><img src="<?php bloginfo("template_url"); ?>/img/gg.png" /></a></li>
						</ul>
					</div>
					
					<div class="displayB aCenter">
						<strong style="font-size:14px; color:#4e4d4d;">Copyright &copy; YourCompany and All Right Reserved</strong>
					</div>
				</div>
				
			</div>
		</div>
	</div><!-- Footer ends -->
	
	<!-- footer on tablet and phone starts-->
	<div class="footer footer-phone hidden-desktop lg-grey">
		<div class="container">
			<div class="row-fluid">
				<div class="span6 pull-left">
					<p class="noMargin">&copy; 2009-2013 Example Company name.</p>
				</div>
				
				<div class="span5 pull-right">
					<ul class="nav nav-pills noMargin phn-foooter-link">
						<li class="active"><a href="#"> Privacy Policy</a></li>
						<li><a href="#"> How it works</a></li>
						<li><a href="#"> Support</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div><!-- footer on tablet and phone starts-->
<script>
jQuery(document).ready(function(){ 
	
	
	jQuery('.wysija-submit').addClass('btn btn-success btn-large');
	jQuery('.validate[required,custom[email]]').addClass('input-xxlarge');
	jQuery("#form-wysija-3-abs-firstname").hide();
	jQuery("#form-wysija-3-abs-lastname").hide();
	jQuery("#form-wysija-3-abs-email").hide();
});
</script>
<?php wp_footer(); ?>
</body>
</html>