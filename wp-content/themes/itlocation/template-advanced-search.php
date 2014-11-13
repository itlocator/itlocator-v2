<?php

/*
  Template Name: Advanced Search Page
 */
session_start();

$roundmylocation = false;
if( isset( $_REQUEST['roundmylocation'] ) ){
	$roundmylocation = $_REQUEST['roundmylocation'];
}

$specificlocation = false;
if( isset( $_REQUEST['specificlocation'] ) ){
	$specificlocation = $_REQUEST['specificlocation'];
}

$bycountry = false;
if( isset( $_REQUEST['bycountry'] ) ){
	$bycountry = $_REQUEST['bycountry'];
}

if( !$roundmylocation && !$specificlocation && !$bycountry ){
	$roundmylocation = true; 
		
}


$location = "";
if( isset( $_REQUEST['lo'] ) ){
	$location = $_REQUEST['lo'];
}

$country = "";
if( isset( $_REQUEST['co'] ) ){
	$country = $_REQUEST['co'];
}

$keyword = "";
if( isset( $_REQUEST['ke'] ) ){
	$keyword = $_REQUEST['ke'];
}

$services = "";
if( isset( $_REQUEST['se'] ) ){
	$services = $_REQUEST['se'];
}

$industries = "";
if( isset( $_REQUEST['in'] ) ){
	$industries = $_REQUEST['in'];
}

$certifications = "";
if( isset( $_REQUEST['ce'] ) ){
	$certifications = $_REQUEST['ce'];
}

$partners = "";
if( isset( $_REQUEST['pa'] ) ){
	$partners = $_REQUEST['pa'];
}

$paged = max(1, get_query_var('paged'));

get_header();
?>
<div class="position-relative">
    <div  style="height:400px; overflow:hidden;">
        <div id="map-canvas" class="width-100-perc height-600"></div>
    </div>
    <div class="position-absolute map-search-large-loading top-200 left-50p">
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
			<?php } ?>
        </div>
    </div>
</div>

<!-- Extra closing divs for only this page -->
</div>
</div>
</div>
</div><!-- Extra closing divs for only this page ends -->

<div class="page-sub-page">
    <div class="listing-wrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                	<div class="displayB">                
                    	<h2 class="page-title-diff"><?php _e('Advanced Provider Search', 'twentyten'); ?></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="search-sidebar">
                    <form method="get" action="" id="advance-search-form" name="advance-search-form">
                        <input type="hidden" name="page_id" value="<?php echo $_REQUEST['page_id']; ?>"/>
                        <?php wp_nonce_field('new-search-map-itlocation', 'new-search-map-security'); ?>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="roundmylocation" id="roundmylocation" value="true" <?php echo $roundmylocation ? 'checked="checked"' : ''; ?>/> By Saved Location
                                </label>
                                <br />
                                <span id="advance_my_location">
                                    <?php  if( $_COOKIE['address_itlocation'] != '' ){ ?>
                                      <div class="alert alert-info" role="alert"> <i class="fa fa-map-marker"></i> <?php echo $_COOKIE['address_itlocation']; ?></div> 
                                    <?php } ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                   <input type="checkbox" name="specificlocation" id="specificlocation" value="true" <?php echo $specificlocation ? 'checked="checked"' : ''; ?>/> By Specific Location
                                </label>
                                <input type="text" class="form-control" name="lo" id="lo" placeholder="<?php _e('Please enter City, State', 'twentyten'); ?>" value="<?php echo $_GET['lo'] ?>" <?php echo $specificlocation ? '' : 'disabled="disabled"'; ?>/>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                   <input type="checkbox" name="bycountry" id="bycountry" value="true" <?php echo $bycountry ? 'checked="checked"' : ''; ?>/> By Country
                                </label>
                                <?php
                            echo do_shortcode('[countries-ctrl-itlocation id="co" add_class="" style="" selected_option="' . $_GET['co'] . '" disabled="' . ($bycountry ? '' : 'disabled') . '"/]');
                        ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Keywords</label>
                            <input type="text" class="form-control" name="ke" id="ke" placeholder="Keywords" value="<?php echo $_GET['ke'] ?>" >
                        </div>
                        
                        <div class="form-group">
                            <label>Services</label>
                             <?php
                            echo do_shortcode('[services-ctrl-itlocation kind="services" id="se" class="form-control" style="height:auto;" default_val=' . implode(",", $services) . ' placeholder="Select Services"/]');
                        ?>
                        </div>
                        
                        <div class="form-group">
                            <label>Industries</label>
                             <?php
                            echo do_shortcode('[services-ctrl-itlocation kind="industries" id="in" class="form-control" style="height:auto;" default_val=' . implode(",", $industries) . ' placeholder="Select Industries"/]'); ?>
                        </div>
                        
                        <div class="form-group">
                            <label>Certifications</label>
                             <?php echo do_shortcode('[services-ctrl-itlocation kind="certifications" id="ce" class="form-control" style="height:auto;" default_val=' . implode(",", $certifications) . ' placeholder="Select Certifications"/]') ?>
                        </div>
                        
                        <div class="form-group">
                            <label>Partners</label>
                             <?php echo do_shortcode('[services-ctrl-itlocation kind="partners" id="pa" class="form-control" style="height:auto;" default_val=' . implode(",", $partners) . ' placeholder="Select Partners"/]'); ?>
                        </div>
                        
                        <div class="form-group">
                             <input type="submit" class="btn btn-primary btn-block btn-lg" id="advanced-search-btn" value="<?php _e('Search', 'twentyten') ?>" />
                        </div>
                    </form>
                </div>
                </div>
                <input type="hidden" />
                
                <div class="list-cont col-lg-9 col-md-9 col-sm-9">
                    <div style="padding-top:15px; min-height:700px;">
                        <!-- /* data listing starts */ -->
                        <div id="company_search_list">
                            <div style="text-align:center;">
                                <p><!--<img src="<?php echo get_bloginfo('template_url') ?>/images/loading-middle.gif" class="width-50 height-50">--></p>
                                <p><b>Please Wait. Refreshing Search Results ...</b></p>
                            </div>
                        </div>
                        <div id="content_loading_process" class="hide">
                            <div style="text-align:center;">
                                <p><img src="<?php echo get_bloginfo('template_url') ?>/images/loading-middle.gif" class="width-50 height-50"></p>
                                <p><b>Please Wait. Refreshing Search Results ...</b></p>
                            </div>
                        </div><!-- /* data listing ends */ -->
                        
                        <!-- /* Total result count */ -->
                      <!--  <div class="result-count">
                            <div class="row">
                                <div class="col-sm-6">
                                	Search Results <span class="badge">2</span>
                                </div>
                                <div class="col-sm-6">
                                    <ul class="sort navbar-right">
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sort by <span class="caret"></span></a>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="#">Alphabetically </a></li>
                                                <li><a href="#">Distance </a></li>
                                                <li><a href="#">Membership</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>--><!-- /*  Total result count ends */-->
                        
                        <!-- Listing style -->
                       <!-- <div class="media client-list">
                            <div class="pull-left">
                            	<div class="img-holder"><a href=""><img src="http://placehold.it/100x100" /></a></div>
                            </div>
                            <div class="media-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="media-heading"><i class="fa fa-building-o"></i> <a href="">Bacon ipsum dolor sit amet spare ribs</a></h4>
                                        <p>Bacon ipsum dolor sit amet spare ribs sirloin hamburger, kevin fatback ground round brisket capicola. Biltong tri-tip prosciutto.</p>
                                        <a href="" class="btn btn-default btn-sm"> More</a>
                                    </div>
                                    <div class="col-sm-3">
                                    	<dl>
                                            <dt><i class="fa fa-phone"></i> Phone:</dt>
                                            <dd>(123) 456 789</dd>
                                            <dt><i class="fa fa-mobile-phone"></i> Mobile:</dt>
                                            <dd>888 123 456 789</dd>
                                            <dt><i class="fa fa-envelope"></i> Email:</dt>
                                            <dd><a href="mailto:#">agency@example.com</a></dd>
                                        </dl>
                                    </div>
                                    <div class="col-sm-3">
                                    	<div class="badge"><i class="fa fa-check-circle-o"></i> Free listing</div>
                                        <address>
                                            <strong><i class="fa fa-briefcase"></i> C-U Computing</strong><br>
                                            4877 Spruce Drive<br>
                                            West Newton, PA 15089<br />
                                            <strong><i class="fa  fa-car"></i> 1.5m | 2.1km away</strong>
                                        </address>
                                         
                                    </div>
                                </div>
                            </div>
                        </div>--><!-- Listing style ends -->
                        
                        <!-- Listing style -->
                      <!--  <div class="media client-list">
                            <div class="pull-left">
                            	<div class="img-holder"><a href=""><img src="http://placehold.it/100x100" /></a></div>
                            </div>
                            <div class="media-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="media-heading"><i class="fa fa-building-o"></i> <a href="">Bacon ipsum dolor sit amet spare ribs</a></h4>
                                        <p>Bacon ipsum dolor sit amet spare ribs sirloin hamburger, kevin fatback ground round brisket capicola. Biltong tri-tip prosciutto.</p>
                                        <a href="" class="btn btn-default btn-sm"> More</a>
                                    </div>
                                    <div class="col-sm-3">
                                    	<dl>
                                            <dt><i class="fa fa-phone"></i> Phone:</dt>
                                            <dd>(123) 456 789</dd>
                                            <dt><i class="fa fa-mobile-phone"></i> Mobile:</dt>
                                            <dd>888 123 456 789</dd>
                                            <dt><i class="fa fa-envelope"></i> Email:</dt>
                                            <dd><a href="mailto:#">agency@example.com</a></dd>
                                        </dl>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="badge b1"><i class="fa fa-check-circle-o"></i> Member</div>
                                        <address>
                                            <strong><i class="fa fa-briefcase"></i> C-U Computing</strong><br>
                                            4877 Spruce Drive<br>
                                            West Newton, PA 15089<br />
                                            <strong><i class="fa  fa-car"></i> 1.5m | 2.1km away</strong>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>--><!-- Listing style ends -->
                        
                        <!-- Listing style -->
                       <!-- <div class="media client-list">
                            <div class="pull-left">
                            	<div class="img-holder"><a href=""><img src="http://placehold.it/100x100" /></a></div>
                            </div>
                            <div class="media-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="media-heading"><i class="fa fa-building-o"></i> <a href="">Bacon ipsum dolor sit amet spare ribs</a></h4>
                                        <p>Bacon ipsum dolor sit amet spare ribs sirloin hamburger, kevin fatback ground round brisket capicola. Biltong tri-tip prosciutto.</p>
                                        <a href="" class="btn btn-default btn-sm"> More</a>
                                    </div>
                                    <div class="col-sm-3">
                                    	<dl>
                                            <dt><i class="fa fa-phone"></i> Phone:</dt>
                                            <dd>(123) 456 789</dd>
                                            <dt><i class="fa fa-mobile-phone"></i> Mobile:</dt>
                                            <dd>888 123 456 789</dd>
                                            <dt><i class="fa fa-envelope"></i> Email:</dt>
                                            <dd><a href="mailto:#">agency@example.com</a></dd>
                                        </dl>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="badge b2"><i class="fa fa-check-circle-o"></i> Premium</div>
                                        <address>
                                            <strong><i class="fa fa-briefcase"></i> C-U Computing</strong><br>
                                            4877 Spruce Drive<br>
                                            West Newton, PA 15089<br />
                                            <strong><i class="fa  fa-car"></i> 1.5m | 2.1km away</strong>
                                        </address>
                                    </div>
                                </div>
                            </div>-->
                        </div><!-- Listing style ends -->
                        
                        <!-- <div class="row">
                            <div class="col-sm-12">
                            	<div class="media client-info-on-map">
                                    <div class="pull-left"><img src="http://placehold.it/80x80" /></div>
                                    <div class="media-body">
                                        <h4 class="media-heading">asas</h4>
                                        <p>asas</p>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        
                        <!-- /* Pagination starts */ -->
                        <!--<div class="displayB aRight">
                            <ul class="pagination page-nav">
                                <li><a href="#"><i class="fa fa-angle-left"></i></a></li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li><a href="#"><i class="fa fa-angle-right"></i></a></li>
                            </ul>
                        </div> --> <!-- /* Pagination ends */ -->
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>