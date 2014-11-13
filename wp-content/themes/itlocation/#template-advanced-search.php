<?php
/*
  Template Name: Advanced Search Page
 */
session_start();

global $functions_ph, $post;

// if( !isset($_GET['co']) ) {
	// $_GET['co'] = 'US';
// }
// if( !isset($_GET['ke']) ) {
	// $_GET['ke'] = 'advanced-search-btn=Search';
// }
// if( !isset($_GET['us']) ) {
	// $_GET['us'] = '1';
// }

if (isset($_GET['us'])) {
    setcookie("advanced_search_use_saved_itlocation", $functions_ph->get_current_url(), time() + 3600 * 24 * 30, "/");
} else {
    if ($_SESSION['advanced_search_use_saved_itlocation'] != 'advanced_search_page') {
        if ($_COOKIE['advanced_search_use_saved_itlocation']) {
            $redirect_url = $_COOKIE['advanced_search_use_saved_itlocation'];
            if ($redirect_url)
                wp_redirect($redirect_url);
        }
    }
    unset($_COOKIE["advanced_search_use_saved_itlocation"]);
    setcookie("advanced_search_use_saved_itlocation", '', time() - 3600, '/', '', 0);
}
get_header();
?>

<div class="row-fluid position-relative">
    <div id="map-canvas" class="width-100-perc height-600"></div>
    <div class="position-absolute map-search-large-loading top-200 left-520">
        <img src="<?php echo get_bloginfo('template_url') ?>/images/loading-middle.gif" class="width-50 height-50">
    </div>
    <div class="position-absolute top-0 right-0">
        <div class="visible-desktop">
            <?php
            $ads = '';
            if (get_option('itlocation_ads_txt_map_index')) {
                $ads = stripslashes(get_option('itlocation_ads_txt_map_index'));
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
</div>
<div id="main">
    <div class="container contCustom">
        <article>
            <div class="row-fluid">
                <div class="row-fluid">
                    <div class="span12">
                        <div class="span3" style="border-right: 1px solid #d2d2d2; padding-right: 1%;">
                            <form post="get" action="" id="advance-search-form" name="advance-search-form">
<!--                                <input type="hidden" name="page_url" id="page_url" value="<?php echo get_permalink($post->ID); ?>" />-->
                                <h4><?php _e('VAR Search', 'twentyten'); ?></h4>
                                <div class="clearfix underline"></div>
                                <label for="lo" class="pull-left"><?php _e('Location', 'twentyten'); ?></label><label class="checkbox pull-right"><input type="checkbox" name="us" id="us" value="1" <?php
            if ($_SESSION['advanced_search_use_saved_itlocation'] == 'advanced_search_page') {
                echo ($_GET['us']) ? 'checked="checked"' : '';
            } else {
                echo 'checked="checked"';
            }
            ?>/><?php _e('Use Saved', 'twentyten'); ?></label>
                                <div class="clear"></div>
                                <input type="text" class="margin-only-bottom-10" name="lo" id="lo" placeholder="<?php _e('Please enter City, State', 'twentyten'); ?>" value="<?php echo $_GET['lo'] ?>" style="width: 93%;">
                                <label for="co"><?php _e('Country', 'twentyten'); ?></label>
                                <?php
                                echo do_shortcode('[countries-ctrl-itlocation id="co" add_class="margin-only-bottom-10 width-100-perc" style="" selected_option="' . $_GET['co'] . '"/]');
                                ?>
                                <div class="clearfix underline"></div>
                                <label for="ke"><?php _e('Keywords', 'twentyten'); ?></label>
                                <input type="text" class="margin-only-bottom-10" name="ke" id="ke" placeholder="Keywords" value="<?php echo $_GET['ke'] ?>" style="width: 93%;">
                                <label for="se"><?php _e('Services', 'twentyten'); ?></label>
                                <?php
                                echo do_shortcode('[services-ctrl-itlocation kind="services" id="se" class="margin-only-bottom-10 width-100-perc" style="" default_val=' . $_GET['se'] . ' placeholder="Select Services"/]');
                                ?>
                                <label for="in"><?php _e('Industries', 'twentyten'); ?></label>
                                <?php
                                echo do_shortcode('[services-ctrl-itlocation kind="industries" id="in" class="margin-only-bottom-10 width-100-perc" style="" default_val=' . $_GET['in'] . ' placeholder="Select Industries"/]');
                                ?>
                                <label for="ce"><?php _e('Certifications', 'twentyten'); ?></label>
                                <?php
                                echo do_shortcode('[services-ctrl-itlocation kind="certifications" id="ce" class="margin-only-bottom-10 width-100-perc" style="" default_val=' . $_GET['ce'] . ' placeholder="Select Certifications"/]')
                                ?>
                                <label for="pa"><?php _e('Partners', 'twentyten'); ?></label>
                                <?php
                                echo do_shortcode('[services-ctrl-itlocation kind="partners" id="pa" class="margin-only-bottom-10 width-100-perc" style="" default_val=' . $_GET['pa'] . ' placeholder="Select Partners"/]');
                                ?>
                                <input type="submit" name="advanced-search-btn" class="btn btn-primary" id="advanced-search-btn" value="<?php _e('Search', 'twentyten') ?>" />
                            </form>
                        </div>
                        <div class="span9 margin-only-top-20 companies">
                            <?php
                            $tmp_a = $functions_ph->advanced_search();
                            
                            
                            foreach ($tmp_a as $tmp) {
                                ?>
                                <div class="media member_bg_<?php echo $tmp['user_role']; ?>">
                                    <a class="pull-left a-media" href="<?php echo $tmp['permalink']; ?>">
                                        <img class="media-object width-100 height-100" src="<?php echo $tmp['logo_url']; ?>">
                                    </a>
                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            <a href="<?php echo $tmp['permalink']; ?>">
                                                <?php _e(stripslashes($tmp['name'])); ?>
                                            </a>
                                            <?php
                                            $edit_fg = $functions_ph->get_default_member_limit('rating', $tmp['user_role']);

                                            if ($edit_fg) {
                                                echo do_shortcode('[comments-itlocation cid="' . $tmp['comp_id'] . '" uid="' . $tmp['user_id'] . '" grp_class="pull-right" /]');
                                            }
                                            ?>

                                        </h4>
                                    </div>
                                    <p class="media-content"><?php _e(stripslashes($tmp['description'])); ?></p>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="pagination text-align-center">
                                <?php
                                    
                                    $big = 999999999; // need an unlikely integer
                                    
                                    echo paginate_links(array(
                                        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                                        'format' => '?paged=%#%',
                                        'type' => 'list',
                                        'prev_text' => __('&larr;'),
                                        'next_text' => __('&rarr;'),
                                        'current' => max(1, get_query_var('paged')),
                                        'total' => $functions_ph->totalpages
                                    ));
                                    
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        <script>
		function initialize() {
			var serializedData= jQuery("#advance-search-form").serialize();                
			jQuery("#advance-search-form").submit(function(){
				var options = { 
					url: "/searchform_handler.php",
					type: 'post',
					data: {
						country: jQuery('#co').val(),
						address: jQuery('#lo').val(),
						mc_fg: 'map'
					},  // pre-submit callback 
					success: showResponse  // post-submit callback 
				}; 
				jQuery(this).ajaxSubmit(options); 
				// !!! Important !!! 
				// always return false to prevent standard browser submit and page navigation 
				// return false;
			});
			
			function showResponse(responseText, statusText, xhr, $form) { 
				console.log(responseText);
				$userdata =  jQuery.parseJSON(responseText); //response.locations;
				if($userdata.length == 0){
					alert('No Search Results Found. Please Try Again.');
				} else {
					make_marker_search();
					jQuery.fn.raty.defaults.path = '<?php echo bloginfo('template_url'); ?>/js/img';
					jQuery('.companies').empty();
					for(var i = 0; i < $userdata.length; i++){
						var html = "<div class='media member_bg_"+$userdata[i].user_role+"'>" + "<a class='pull-left a-media' href='"+$userdata[i].permalink+"'>" + "<img class='media-object width-100 height-100' src='"+$userdata[i].logo_url+"'>" + "</a>" + "<div class='media-body'>" + "<h4 class='media-heading'>" + "<a href='"+$userdata[i].permalink+"'>" + $userdata[i].name + "</a>"+ "<div id='star"+i+"' style='float:right;position:relative; '></div>"+ "</h4></div><p class='media-content'>" + $userdata[i].description + "</p></div>";
						jQuery('.companies').append(html);                
						jQuery('#star'+i).raty({readOnly: true, score: $userdata[i].rating }); 
					}
				}   
			} 

			var myOptions = {
				zoom: 5,
				center: new google.maps.LatLng(37.09024, -95.712891),
				mapTypeControl: true,
				mapTypeControlOptions: {
					style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
				},
				navigationControl: true,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		
			$map = new google.maps.Map(document.getElementById("map-canvas"),myOptions);
			jQuery('.map-search-large-loading').hide();
	   
			$userdata = <?php echo json_encode($functions_ph->advanced_search('map')); ?>;//response.locations;
			make_marker();
		
			address_pos = jQuery.cookie("address_pos");
		
			if(address_pos) {
				var res = address_pos.split(',');
				var pos = new google.maps.LatLng(res[0], res[1]);
				$map.setCenter(pos);
				$map.setZoom(12); 
			} else {
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(function(position) {
						var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
						var latlong = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
						geocoder = new google.maps.Geocoder();
						geocoder.geocode({'latLng': latlong}, function(results, status) {
							if (status == google.maps.GeocoderStatus.OK) {
								if (results[1]) {
									var today = new Date();
									today.setTime( today.getTime() );
									var expires = 1;
									expires = expires * 1000 * 60 * 60 * 24; 
									jQuery.cookie("address_pos", position.coords.latitude + "," + position.coords.longitude, {expires: new Date( today.getTime()+expires)});
									jQuery.cookie("address_itlocation", results[1].formatted_address, {expires: new Date( today.getTime()+expires)});

									$str = '<span class="iconic-map-pin font-size-16" style=""></span> <span class="font-size-12 font-weight-bold font-color-393939">' + jQuery.cookie('address_itlocation') + '</span>';

									if (jQuery.cookie('address_itlocation')) {
										jQuery("#location-str").html($str);
									}
									new google.maps.InfoWindow({
										map: $map,
										position: pos
									}); 
									
									$map.setCenter(pos);
									$map.setZoom(12);
								} else {
									alert('No results found');
								}
							} else {
								alert('Geocoder failed due to: ' + status);
							}
						});
					}, function(error) {
						var errors = { 
							1: 'Permission denied',
							2: 'Position unavailable',
							3: 'Request timeout'
						};
						alert("Error: " + errors[error.code]);						  
						handleNoGeolocation(true);
					});
				} else {
					// Browser doesn't support Geolocation
					handleNoGeolocation(false);
				}
			}
        }
        google.maps.event.addDomListener(window, 'load', initialize);
	</script>

<?php
get_footer();
$_SESSION['advanced_search_use_saved_itlocation'] = 'advanced_search_page';
?>