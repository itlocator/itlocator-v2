<div class="row-fluid position-relative">
	<div id="map-canvas" class="width-100-perc height-600"></div>
	<div class="position-absolute map-search-large-loading top-200 left-50p">
		<img src="<?php echo get_bloginfo('template_url') ?>/images/loading-middle.gif" class="width-50 height-50" />
	</div>
	<div class="position-absolute top-0 right-0">
		<div class="visible-desktop">
		<?php
			$ads = '';
			if( get_option( 'itlocation_ads_txt_map_index' ) ){
				$ads = stripslashes( get_option( 'itlocation_ads_txt_map_index' ) );
			}

			if ($ads) {
				_e($ads);
			} else {
		?>
			<div class="position-absolute"><?php _e('Please insert your ads code in Appearance -> Theme Options -> tab "Ads" in admin', 'twentyten'); ?></div>
			<img src="http://www.placehold.it/160x600/AFAFAF/fff&text=160x600">
		<?php
			}
		?>
		</div>
	</div>
	<div class="search-key-map-wrap">
		<div class="search-key-map container contCustom">
			<div class="txt">
				<div class="txt-inner">
					<div><?php _e('The Best IT Solutions Always Come From', 'twentyten') ?></div>
					<div class="font-size-28"><?php _e('Local Technology Providers', 'twentyten') ?></div>
				</div>
			</div>
			<div class="keys">
				<form method="post" action="" name="search-map-form" style="margin:0;" id="search-map-form" enctype="multipart/form-data">
					<?php wp_nonce_field('new-search-map-itlocation', 'new-search-map-security'); ?>
					<div class="row-fluid">
						<div class="span3">
						<?php
						echo do_shortcode('[services-ctrl-itlocation kind="services" id="se" class="" placeholder="Select Services"/]'); ?>
						</div>
						<div class="span4">
						<input type="text" class="" name="keywords" id="keywords" placeholder="Keywords" style="width:100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; height:31px; margin:0;">
						</div>
						<div class="span3">
						<?php echo do_shortcode('[countries-my-location-ctrl-itlocation id="countries" selected_option="mylocation"/]'); ?>
						</div>
						<div class="span2">
						<input type="submit" style="position:relative; display:block; width:100%;" value="<?php _e('Search Now', 'twentyten') ?>" class="pull-left btn btn-primary" id="search-map-btn" disabled="disabled" />
						<div class="advance-search">
							<a href="">Advanced Search</a>
						</div>
					<?php
						$pid = get_option( 'itlocation_generals_advanced_search_page' );
						$tmp_url = get_permalink( $pid );
					?>
						</div>
					</div>
					
					
					<!--<input type="button" class="btn btn-success pull-right" id="advance-search-map-btn" onclick="homepage_go_advanced_search()" value="<?php _e('Advanced Search', 'twentyten') ?>" />-->
				</form>
			</div>
			
			
		</div>
	</div>
</div>