<div style="position:relative; overflow:hidden;" class="hidden-xs">
	<div id="map-canvas" class="width-100-perc height-600"></div>
    <div class="map-tag-line">Find your next technology partner here</div>
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
			} else { ?>
			<div class="position-absolute"><?php _e('Please insert your ads code in Appearance -> Theme Options -> tab "Ads" in admin', 'twentyten'); ?></div>
			<img src="http://www.placehold.it/160x600/AFAFAF/fff&text=160x600">
		<?php } ?>
		</div>
	</div>
</div>

	<!-- /* Search area starts */ -->
    <div class="is-section is-search">
    	<div class="pattren-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12">
               	 <h2 class="heading"><span><?php _e('The best IT solutions<br> come from', 'twentyten') ?> </span>
				 	<?php _e('Local Technology Partners', 'twentyten') ?></h2>
                    
                    <!-- <div style="display: table; height: 400px; overflow: hidden;">
                        <div style="display: table-cell; vertical-align: middle;">
                            <div>
                            	everything is vertically centered in modern IE8+ and others.
                            </div>
                        </div>
                    </div>-->
                </div>
                <div class="col-lg-7 col-md-7 col-sm-12">
                    <form method="post" action="" name="search-map-form" id="search-map-form" enctype="multipart/form-data" style="margin-bottom:0;">
                    <?php wp_nonce_field('new-search-map-itlocation', 'new-search-map-security'); ?>
                        <div class="form-group">
                            <div class="row row-custom">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-custom services">
                                    <label class="sr-only">Select services</label>
                                    <?php echo do_shortcode('[services-ctrl-itlocation kind="services" id="se" class="form-control" placeholder="Select Services"/]'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
							<div class="row row-custom">
                            	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-custom">
                                    <label class="sr-only">My Location</label>
                                    <div class="form-control"><?php echo do_shortcode('[countries-my-location-ctrl-itlocation id="countries" selected_option="mylocation"/]'); ?></div>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-custom">
                                    <label class="sr-only">Address</label>
                                    <input type="text" class="form-control" name="lo" id="lo" placeholder="Address" value=""/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row row-custom">
                            	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-custom">
                                    <label class="sr-only">Keyword</label>
                                    <input type="text" class="form-control" name="keywords" id="keywords" placeholder="Keywords" value="" >
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-custom">
                                <input type="submit" value="<?php _e('Search Now', 'twentyten') ?>" class="btn btn-default btn-block btn-search" id="search-map-btn" disabled="disabled" />
                                <?php
                                $pid = get_option( 'itlocation_generals_advanced_search_page' );
                                $tmp_url = get_permalink( $pid );
                                ?>
                                <a id="advance-search-map-btn" class="btn advance-search" style="font-size:11px" onclick="homepage_go_advanced_search()"><?php _e('Advanced Search', 'twentyten') ?></a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- /* Search area Ends */ -->