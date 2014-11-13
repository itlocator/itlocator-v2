<?php
$exist_company = 'not_claim_process';

if(!session_start()) {
	throw new LinkedInException('This script requires session support, which appears to be disabled according to session_start().');
}

global $wp_query, $functions_ph ,$color_change;
$curauth = $wp_query->get_queried_object();

$user = new WP_User($curauth->ID);

if ($user->roles[0] == 'administrator') {
    wp_redirect(get_site_url());
}

$company_model = new companyModelItlocation();
$company_info = $company_model->get_by_user_id($curauth->ID);

$color_change='btn-success';
if( isset( $_POST['process'] ) && $_POST['process'] == 'claim' ){
	
	require_once 'inc/linkedin/company.php';

	/*if( $exist_company == 'not_in_linkedin' ) {
		$company_model->setCompanyClaim( $curauth->ID, 0 );
	} else*/
}
if( $exist_company == 'not_in_linkedin' ) {
	$color_change='btn-danger';
} 


if( $exist_company == 'yes_in_linkedin' ) {
	$company_model->setCompanyClaim( $curauth->ID, true );
	$company_info->claim = true;
}

get_header();
// echo $exist_company;
// print_r( $_POST );
?>
<!--  Extra div closing */ -->
</div>
</div>
</div>
</div><!--  Extra div closing */ -->

<div class="inner-page profile-page" style="padding-top:0;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 noPadding"> 
             	<?php echo do_shortcode('[company-map-itlocation company_id="' . $company_info->id . '" /]'); ?>
            </div>
        </div>
    </div>
    
	<?php
		$role_info = $functions_ph->get_member_role_info_by_id($curauth->ID);
		if( $role_info['label'] == "Free Listing") {
		?>
		<style>
		.profile-page .base{border-top: 5px solid #939598;}
		</style>
		<?php
		} else if( $role_info['label'] == "Member Listing") {
		?>
		<style>
		.profile-page .base{border-top: 5px solid #e77e1c;}
		</style>
		<?php 
		} else {
		?>
		<style>
		.profile-page .base{border-top: 5px solid #e1202c;}
		</style>
    <?php } ?>
    
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-9 base" style="top:-20px;">
                <div class="displayB profile-cont">
                    <h1 class="company-name"><?php _e(stripslashes($company_info->companyname)) ?></h1>                      
                </div>
                <!-- / Profile-info starts -->
                <div class="profile-info">
                    <div class="row">
                        <div class="col-md-3 col-sm-3">
                                            
                            <figure class="profile-image">
								 <div class="profile-image-sub">
								 <?php  $upload_dir = wp_upload_dir();
                                    if ($company_info->logo_file_nm) { ?>
                                    <img src="<?php echo $upload_dir["baseurl"] . "/comp_logo/" . $company_info->logo_file_nm; ?>" class="img-responsive">
                                 <?php }
								 else { ?>									 
									<img src="<?php bloginfo('stylesheet_directory'); ?>/img/no-logo.jpg" class="img-responsive">									 
								 <?php }?>
                                 </div>
                            </figure>
                            
                           <div class="displayB aCenter" style="margin-top:30px; margin-bottom:30px;">
                            <?php if( $company_info->claim ) {
							//echo '<span class="claim-do">Claimed</span>';
						} else {
							  $login_user_id = get_current_user_id();
						
							// echo '<br/>' . $company_info->user_id . '-'. $login_user_id . '<br/>';
							if( ($company_info->autoinsert == 1 && $company_info->source == "SITE") || $company_info->user_id == $login_user_id ){
								echo '<form method="post" action="">
								<input type="hidden" name="process" value="claim"/>
								
								 <input type="submit" class="btn claim-btn '.$color_change.'" value="Claim Listing"/> 
								</form>';
							} else {
								//echo '<span class="claim-do-not">Not Claimed Yet</span>';
							}
						}
					?>
                    
<!-- Button trigger modal <!-- -->
<!--<button class="btn btn-danger dMx" data-toggle="modal" data-target="#claim_failure">
  Claim Failure
</button>-->
                    
                    </div>
                        </div><!-- /.col-md-3 -->
                        <div class="col-lg-5 col-md-5 col-sm-5">
                            <h3 class="page-title dM">About Company</h3>
                            <p>
                                <?php _e(stripslashes($company_info->description)); ?>
                            </p>
                        </div><!-- /.col-md-4 -->
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <h3 class="page-title dM">Contact/ Address Info</h3>
                            <div class="badge badge-3 dM"><i class="fa fa-check-circle-o"></i> <?php echo $role_info['label']; ?></div>
                            <address>
                            <strong><?php global $all_country_nms; echo $company_info->address1; ?></strong><br>
                            <?php
                                if ($company_info->address2)
                                    $company_info->address2 . '<br>'; ?>
                                <?php echo $company_info->city . ' ' . $company_info->state . ' ' . $company_info->zip_code . '<br>' . $all_country_nms[$company_info->country]; ?><br><strong><?php _e('Time Zone', 'twentyten') ?></strong> <?php echo $company_info->time_zone; ?>
                            
                        </address>
			<address>
			<?php if ($company_info->companyurl){
            
                $companyurl= $company_info->companyurl;
                
             /*   if (strpos($companyurl,'http://') === false) {
                if(strpos($companyurl,'https://') === false)
                    {
                        $companyurl='http://'.$companyurl;
                    }
                }*/
            ?>
                <strong>Company URL:</strong> <a target="_blank" href="<?php echo $companyurl; ?>"><?php echo $companyurl; ?></a><br> <?php } ?>
                </address>
     		<address>
			<?php if (get_user_meta($curauth->ID, 'user_email_show_fg', true)) { ?>
             <strong>E:</strong> <a href="mailto:<?php echo $curauth->user_email; ?>"><?php echo $curauth->user_email; ?></a><br>
					
				<?php } if (!get_user_meta($curauth->ID, 'user_phone_show_fg', true)) { ?>
				
                <abbr title="Phone"><strong>P :</strong></abbr> <?php echo "".substr($company_info->phoneprim, 0, 3)."-".substr($company_info->phoneprim, 3, 3)."-".substr($company_info->phoneprim,6); ?>
				 <?php } ?>
            </address>
                        
                        </div><!-- /.col-md-5 -->
                    </div><!-- /.row -->
				</div><!-- / Profile-info -->
                
                <!-- / Profile-desc -->
                <div class="profile-desc">
                     <div class="panel-group" id="accordion">
                                                                          
                     <!-- <div class="panel panel-default">
                      <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#contributions">
                                    	<i class="fa fa-list"></i> <?php _e('Contributions', 'twentyten') ?>
                                    </a>
                                </h4>
                            </div>
                           <!-- <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                    	<i class="fa fa-list"></i> Personal details
                                    </a>
                                </h4> 
                            </div> -->
                          <!--  <div id="collapseOne" class="panel-collapse collapse in"> 
                                <div class="panel-body">
                                <!-- /.row-->
                             <!--   <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <?php if ($company_info->companyurl) { ?>
                                        <?php if($company_info->companyurl==''){ ?> <div class="span6"><span>URL:</span> <a href="<?php echo $functions_ph->add_url_http($company_info->companyurl); ?>" target="_blank"><?php _e($company_info->companyurl); ?></a></div><?php } ?>
                                        <?php } ?>
                                        <?php if ($company_info->phonescond) { ?>
                                            <div class="span6"><span>Phone:</span> <?php _e($company_info->phonescond); ?></div>
                                        <?php } ?>
                                    </div><!-- /.col-md-3 -->
                                <!--</div><!-- /.row-->
                               <!-- </div>
                            </div>
                        </div>-->
                      <div class="panel panel-default">
                          <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#contributions">
                                    	<i class="fa fa-list"></i> <?php _e('Contributions', 'twentyten') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="contributions" class="panel-collapse collapse in"> 
                                <div class="panel-body">
                                	 <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                             <?php  $edit_fg = $functions_ph->get_default_member_limit('contribution', $company_info->user_role);
                                if ($edit_fg) {  ?>
                                    <div class="">
                                        <div class="">
                                            <?php
                                            $the_query = new WP_Query(array(
                                                        'author' => $curauth->ID,
                                                        'post_type' => 'member-contributions',
                                                        'posts_per_page' => -1
                                                    ));
                                            $idx = 0;
                                            while ($the_query->have_posts()) : $the_query->the_post();
                                                if (($idx % 4) == 0) {
                                                    if ($idx != 0)
                                                        echo '</ul>';
                                                    echo '<ul class="thumbnails">';
                                                }
                                                ?>
                                                <li id="post-<?php the_ID(); ?>" <?php post_class('span3'); ?>>
                                                    <div class="thumbnail">
                                                        <?php if (get_post_status(get_the_ID()) == 'publish') { ?>
                                                            <a href="<?php the_permalink() ?>">
                                                                <?php
                                                            }
                                                            if (has_post_thumbnail()) {
                                                                the_post_thumbnail('thumb-300*200');
                                                            } else {
                                                                ?>
                                                                <img data-src="holder.js/300x200" alt="300x200" width="300" height="200" src="http://www.placehold.it/300x200/AFAFAF/fff&amp;text=No+Image">
                                                                <?php
                                                            }
                                                            if (get_post_status(get_the_ID()) == 'publish') {
                                                                ?>
                                                            </a>
                                                            <?php } ?>
                        
                                                        <div class="caption">
                                                            <h4 class="entry-title">
                                                                <?php if (get_post_status(get_the_ID()) == 'publish') { ?>
                                                                    <a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'twentyten'), the_title_attribute('echo=0')); ?>" rel="bookmark">
                                                                    <?php } ?>
                                                                    <?php the_title(); ?>
                                                                    <?php if (get_post_status(get_the_ID()) == 'publish') { ?>
                                                                    </a>
                                                                <?php } ?>
                                                            </h4>
                        
                                                            <div class="entry-meta font-color-b2b2b2 margin-only-bottom-10">
                                                                <?php twentyten_posted_on(); ?>
                                                            </div><!-- .entry-meta -->
                        
                                                            <div class="entry-summary description">
                                                                <?php the_excerpt(); ?>
                                                            </div><!-- .entry-summary -->
                                                        </div>
                                                    </div>
                        
                                                </li><!-- #post-## -->
                                                <?php
                                                ++$idx;
                                                if ($idx == count($the_query->posts)) {
                                                    echo '</ul>';
                                                }
                                                ?>
                                                <?php
                                            endwhile; // End the loop. Whew.  
                                            wp_reset_postdata(); ?>
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
                                                    'total' => $the_query->max_num_pages
                                                ));
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                               <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                    	<i class="fa fa-list"></i> <?php _e('Services Offered', 'twentyten') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse in">
                                <div class="panel-body">
                                	<?php
									$file_num = $functions_ph->get_default_member_limit('services', $company_info->user_role);
									if ($file_num != '0') { ?>
										<div class="row">
											<div class="col-md-12 col-sm-12">
												<?php echo do_shortcode('[services-data-itlocation kind="services" company_id="' . $company_info->id . '" tag="span" class="oTag" /]'); ?>
											</div>
										</div><!-- /.row-->
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- /* */ -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#certifications">
                                    	<i class="fa fa-list"></i> <?php _e('Certifications Offered', 'twentyten') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="certifications" class="panel-collapse collapse in">
                                <div class="panel-body">
                                	<?php 
                                $file_num = $functions_ph->get_default_member_limit('certifications', $company_info->user_role);
                                if ($file_num != '0') { ?>
										<div class="row">
											<div class="col-md-12 col-sm-12">
												<?php echo do_shortcode('[services-data-itlocation kind="certifications" company_id="' . $company_info->id . '" tag="span" class="oTag" /]'); ?>
											</div>
										</div><!-- /.row-->
                                    <?php  } ?>
                                </div>
                            </div>
                        </div><!-- /* */ -->
                        
                        <!-- /* */ -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#partners">
                                    	<i class="fa fa-list"></i> <?php _e('Partners Offered', 'twentyten') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="partners" class="panel-collapse collapse in">
                                <div class="panel-body">
                                	<?php
                                $file_num = $functions_ph->get_default_member_limit('partners', $company_info->user_role);
                                if ($file_num != '0') {
                                    ?>
										<div class="row">
											<div class="col-md-12 col-sm-12">
												<?php echo do_shortcode('[services-data-itlocation kind="partners" company_id="' . $company_info->id . '" tag="span" class="oTag" /]'); ?>
											</div>
										</div><!-- /.row-->
                                    <?php  } ?>
                                </div>
                            </div>
                        </div><!-- /* */ -->
                        
                        <!-- /* */ -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#industries">
                                    	<i class="fa fa-list"></i> <?php _e('Industries Offered', 'twentyten') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="industries" class="panel-collapse collapse in">
                                <div class="panel-body">
                                	<?php 
                                $file_num = $functions_ph->get_default_member_limit('industries', $company_info->user_role);
                                if ($file_num != '0') { ?>
										<div class="row">
											<div class="col-md-12 col-sm-12">
												<?php echo do_shortcode('[services-data-itlocation kind="industries" company_id="' . $company_info->id . '" tag="span" class="oTag" /]'); ?>
											</div>
										</div><!-- /.row-->
                                    <?php  } ?>
                                </div>
                            </div>
                        </div><!-- /* */ -->
                        
                        <!-- /* */ -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collateral">
                                    	<i class="fa fa-list"></i> <?php _e('Collateral', 'twentyten') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collateral" class="panel-collapse collapse in">
                                <div class="panel-body">
                                	 <?php $file_num = $functions_ph->get_default_member_limit('collateral', $company_info->user_role);
                                if ($file_num != '0') { ?>
										<div class="row">
											<div class="col-md-12 col-sm-12">
												<?php echo do_shortcode('[file-mgn-itlocation file_type="collateral" comp_id="' . $company_info->id . '" /]'); ?>
											</div>
										</div><!-- /.row-->
                                    <?php  } ?>
                                </div>
                            </div>
                        </div><!-- /* */ -->
                        
                        <!-- /* */ -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#casestudy">
                                    	<i class="fa fa-list"></i> <?php _e('Case Studies', 'twentyten') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="casestudy" class="panel-collapse collapse in">
                                <div class="panel-body">
                                	 <?php  $file_num = $functions_ph->get_default_member_limit('collateral', $company_info->user_role);
                                    if ($file_num != '0') { ?>
										<div class="row">
											<div class="col-md-12 col-sm-12">
												<?php echo do_shortcode('[file-mgn-itlocation file_type="case_studies" comp_id="' . $company_info->id . '" /]'); ?>
											</div>
										</div><!-- /.row-->
                                    <?php  } ?>
                                </div>
                            </div>
                        </div><!-- /* */ -->
                        
                        <!-- /* */ -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#pointofcontact">
                                    	<i class="fa fa-list"></i> <?php _e('Point of Contact', 'twentyten') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="pointofcontact" class="panel-collapse collapse in">
                                <div class="panel-body">
                                	 <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <p><?php _e('Name', 'twentyten') ?> : <?php _e($company_info->firstname . ' ' . $company_info->lastname) ?> </p>
                                            <p><?php _e('Title', 'twentyten') ?> : <?php echo get_user_meta($curauth->ID, 'user_title', true) ?> </p>
                                            <?php if (!get_user_meta($curauth->ID, 'user_phone_show_fg', true)) { ?>
                                            <p><?php _e('Phone', 'twentyten') ?> : <?php echo "".substr($company_info->phoneprim, 0, 3)."-".substr($company_info->phoneprim, 3, 3)."-".substr($company_info->phoneprim,6); ?> </p>
                                            <?php } ?>
                                            
                                            <div class="">                                        
											<?php if (get_user_meta($curauth->ID, 'user_email_show_fg', true)) { ?>
                                            <p><?php _e('Email', 'twentyten') ?> : <?php echo $curauth->user_email; ?> </p>
                                            <?php } ?>
                                        </div>
                                        <?php if (get_user_meta($curauth->ID, 'user_photo_nm', true)) { ?>
                                            <div class="">
                                                <img src="<?php echo $upload_dir["baseurl"] . "/user_photos/" . get_user_meta($curauth->ID, 'user_photo_nm', true); ?>" class="img-polaroid max-width-200" />
                                            </div>
                                        <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /* */ -->
                        
                        <!-- /* */ -->
                         <!-- <div class="panel panel-default">
                          <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#contributions">
                                    	<i class="fa fa-list"></i> <?php _e('Contributions', 'twentyten') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="contributions" class="panel-collapse collapse in"> 
                                <div class="panel-body">
                                	 <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                             <?php  $edit_fg = $functions_ph->get_default_member_limit('contribution', $company_info->user_role);
                                if ($edit_fg) {  ?>
                                    <div class="">
                                        <div class="">
                                            <?php
                                            $the_query = new WP_Query(array(
                                                        'author' => $curauth->ID,
                                                        'post_type' => 'member-contributions',
                                                        'posts_per_page' => -1
                                                    ));
                                            $idx = 0;
                                            while ($the_query->have_posts()) : $the_query->the_post();
                                                if (($idx % 4) == 0) {
                                                    if ($idx != 0)
                                                        echo '</ul>';
                                                    echo '<ul class="thumbnails">';
                                                }
                                                ?>
                                                <li id="post-<?php the_ID(); ?>" <?php post_class('span3'); ?>>
                                                    <div class="thumbnail">
                                                        <?php if (get_post_status(get_the_ID()) == 'publish') { ?>
                                                            <a href="<?php the_permalink() ?>">
                                                                <?php
                                                            }
                                                            if (has_post_thumbnail()) {
                                                                the_post_thumbnail('thumb-300*200');
                                                            } else {
                                                                ?>
                                                                <img data-src="holder.js/300x200" alt="300x200" width="300" height="200" src="http://www.placehold.it/300x200/AFAFAF/fff&amp;text=No+Image">
                                                                <?php
                                                            }
                                                            if (get_post_status(get_the_ID()) == 'publish') {
                                                                ?>
                                                            </a>
                                                            <?php } ?>
                        
                                                        <div class="caption">
                                                            <h4 class="entry-title">
                                                                <?php if (get_post_status(get_the_ID()) == 'publish') { ?>
                                                                    <a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'twentyten'), the_title_attribute('echo=0')); ?>" rel="bookmark">
                                                                    <?php } ?>
                                                                    <?php the_title(); ?>
                                                                    <?php if (get_post_status(get_the_ID()) == 'publish') { ?>
                                                                    </a>
                                                                <?php } ?>
                                                            </h4>
                        
                                                            <div class="entry-meta font-color-b2b2b2 margin-only-bottom-10">
                                                                <?php twentyten_posted_on(); ?>
                                                            </div><!-- .entry-meta -->
                        
                                                          <!--   <div class="entry-summary description">
                                                                <?php the_excerpt(); ?>
                                                            </div><!-- .entry-summary -->
                                                         <!--</div>
                                                    </div>
                        
                                                </li><!-- #post-## -->
                                                <!-- <?php
                                                ++$idx;
                                                if ($idx == count($the_query->posts)) {
                                                    echo '</ul>';
                                                }
                                                ?>
                                                <?php
                                            endwhile; // End the loop. Whew.  
                                            wp_reset_postdata(); ?>
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
                                                    'total' => $the_query->max_num_pages
                                                ));
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                               <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>>--><!-- /* */ -->
                        
                    </div><!-- /* panel group */ -->
                
                </div> <!-- / Profile-desc ends-->
            	
            </div>
            <!-- /* sidebar starts */ -->            
            <div class="col-lg-3 col-md-3 col-sm-3 base-secondary">            
            	<section class="sidebar">
                    <div class="">
                    <div class="dMx">
                    	<?php echo do_shortcode('[send-email-itlocation id="view_profile" to_email="' . $curauth->user_email . '"/]'); ?>
                    </div>
                    <div class="dMx"><?php _e('Status', 'twentyten') ?> : <?php _e('Active', 'twentyten') ?> </div>
                    <div class="dMx">Membership type 
									<?php  $role_info = $functions_ph->get_member_role_info_by_id($curauth->ID); ?>
                                    <strong><?php //echo $role_info['label']; ?></strong>
                                    <div class="<?php echo $role_info['icon']; ?>"></div>
                    </div>
                    <div class="dMx"><?php
                        if ($company_info->linkedin) { 
 $linkedin= $company_info->linkedin;
/*if (strpos($linkedin,'http://') === false) {
if(strpos($linkedin,'https://') === false)
{
    $linkedin='http://'.$linkedin;
}
}*/
?>
                            <a style="font-size:24px;" href="<?php echo $linkedin; ?>" target="_blank"><!--<img src="<?php echo get_template_directory_uri(); ?>/images/icon-linkedin.png" alt="screenshot"/>--><i class="fa fa-linkedin-square"></i></a>
                            <?php } ?>
                        <?php
                        if ($company_info->googleplus) { 
 $googleplus= $company_info->googleplus;
/*if (strpos($googleplus,'http://') === false) {
if(strpos($googleplus,'https://') === false)
{
    $googleplus='http://'.$googleplus;
}
}*/
?>
                            <a style="font-size:24px;" href="<?php echo $googleplus; ?>" target="_blank"><!-- <img src="<?php echo get_template_directory_uri(); ?>/images/icon-googleplus.png" alt="screenshot"/>--><i class="fa fa-google-plus-square"></i></a>
                            <?php }  ?>
                        <?php
                        if ($company_info->facebook) {
 $facebook= $company_info->facebook;
/*if (strpos($facebook,'http://') === false) {
if(strpos($facebook,'https://') === false)
{
    $facebook='http://'.$facebook;
}
}*/
 ?>
                            <a style="font-size:24px;" href="<?php echo $facebook; ?>" target="_blank"><!-- <img src="<?php echo get_template_directory_uri(); ?>/images/icon-facebook.png" alt="screenshot"/>--><i class="fa fa-facebook-square"></i></a>
                            <?php } ?>
                        <?php
                        if ($company_info->twitter) { 
 $twitter= $company_info->twitter;

/*if (strpos($twitter,'http://') === false) {
if(strpos($twitter,'https://') === false)
{
    $twitter='http://'.$twitter;
}
}*/
 ?>
                            <a style="font-size:24px;" href="<?php echo $twitter; ?>" target="_blank"><!-- <img src="<?php echo get_template_directory_uri(); ?>/images/icon-twitter.png" alt="screenshot"/>--><i class="fa fa-twitter-square"></i></a>
                            <?php } ?>
                    </div>
                    <div class="dMx"><i class="fa fa-calendar"></i> <?php _e('Register on', 'twentyten') ?> : <?php echo ($company_info->renew_date) ? date(get_option('date_format'), strtotime($company_info->register_date)) : '' ?></div>
                   
                    
                </div>
                <!-- /* */ -->
                <div class="hidden">
                    <?php
                        $address_model = new addressModelItlocation();
                        $addresses = $address_model->get_by_comp_id($company_info->id);
                            if (count($addresses)) { ?>
                            <div class="clearfix"></div>
                                <table class="table">
                                    <tbody>
                                        <?php foreach ($addresses as $address) { ?>
                                            <tr>
                                                <td><?php //_e($address->address) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                    <?php }  ?>
                </div>
                <div class="dMx">
                   <?php $edit_fg = $functions_ph->get_default_member_limit('rating', $company_info->user_role);
						 if ($edit_fg) {
                                echo do_shortcode('[comments-itlocation cid="' . $company_info->id . '" uid="' . $curauth->ID . '" star_class="pull-left" left_cmt_fg="1" view_cmt_fg="1" title="Rating"/]'); } ?>
                <!-- /* */ -->
                </div>
                
                <!-- /* Advertisement start */-->
                <div class="aCenter">
                    <?php  if (stripslashes(get_option('itlocation_ads_author'))) { ?>
                        <div class="visible-desktop">
                            <?php echo stripslashes(get_option('itlocation_ads_author')); ?>
                        </div>
                    <?php } ?>
                    </div><!-- /* Advertisement ends */-->
                    
                </section><!-- /#sidebar -->            
            </div> <!-- /* sidebar ends */ -->
        </div>        
    </div><!-- /.container -->
</div>
      
<?php
	if( isset( $_POST['process'] ) && $_REQUEST['process'] == 'claim' ) {
		
		// $exist_company = 1;
		if($exist_company == 'not_in_linkedin' ) {		
						
			$email_from = get_option('admin_email');
			//This email getting from company.php file of linkdn
		   $email_to = $linkedin_profile_email;
			// $email_to = 'jc.hu920@gmail.com';
			$email_title = "We Could Not Approve Your Claim of " . stripslashes($company_info->companyname);
			$email_content ='';			
			$email_content .= '<p>Unfortunately we were unable to approve your claim request for '.stripslashes($company_info->companyname).'. Our internal claim review process was unable to definitely associate you with the company listed on IT Locator which is a requirement for approving any claim requests we receive.</p>';
			$email_content .='<p>We would still like to encourage you to join IT Locator. Because you took the time to claim your listing we want to present you with a special one-time discount if you upgrade your membership level as a way of saying thanks in making the effort.</p>';
			$email_content .='<p>Once you have your new listing up, please email us and if after comparison its clear we made a mistake in not initially authorizing you, we will gladly remove the current profile for '.stripslashes($company_info->companyname).'<p>';
			
			$email_content .='Thanks<br><br>The IT Locator Team';
			
			$functions_ph->send_mail($email_to, $email_from, $email_title, $email_content);	?>
<style>
#claim_failure input[type=text], .uneditable-input {
    width: 280px !important;
	height:25px !important;
}
textarea {
    width: 280px !important;
}
#claim_failure .modal-body{
	min-height:445px !important;	
}

</style>
	<a id="claim_failure_btn" href="#claim_failure" role="button" class="btn btn-large btn-primary" data-toggle="modal" style="display:none;">EMAIL VAR</a>
	<!-- <div id="claim_failure1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="send-email-modal-label" aria-hidden="true">
        <div class="modal-header failure-modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 id="send-email-modal-label"><?php _e('We Couldn’t Verify You', 'twentyten') ?></h3>
        </div>
        <div class="modal-body">
            <div class="row-fluid">
				<div class="span12" id="jquery-live-validation-send_email_claim">
					<p>System could not fully authenticate your claim.  We require further information to process your profile claim request.  Please provide the following information and we will manually verify your claim to <?php echo stripslashes($company_info->companyname); ?> listing.</p>
					<form class="form-horizontal" name="send_email_view_profile-form-claim" id="send_email_view_profile-form-claim" action="" method="post" style="margin:0;">
                        <?php //wp_nonce_field('send-email-itlocation', 'send-claim-email-itlocation-security'); ?>
                        
						<div class="control-group" style="margin-bottom: 10px;">
							<label class="control-label" for="claim_name"><?php _e('Name', 'twentyten') ?> <span class="imp_star_mark">*</span></label>
                            <div class="controls">
								<input type="text" name="claim_name" id="claim_name" value="" />
							</div>
						</div>
						<div class="control-group" style="margin-bottom: 10px;">
							<label class="control-label" for="claim_title"><?php _e('Title', 'twentyten') ?> <span class="imp_star_mark">*</span></label>
                            <div class="controls">
								<input type="text" name="claim_title" id="claim_title" value="" />
							</div>
						</div>
                      <div class="control-group" style="margin-bottom: 10px;">
							<label class="control-label" for="claim_email"><?php _e('Email', 'twentyten') ?> <span class="imp_star_mark">*</span></label>
                            <div class="controls">
								<input type="text" name="claim_email" id="claim_email" value="" />
							</div>
						</div>
                        <div class="control-group" style="margin-bottom: 10px;">
                            <label class="control-label" for="claim_phone"><?php _e('Phone', 'twentyten') ?><span class="imp_star_mark">*</span></label>
                            <div class="controls">
                                <input type="text" name="claim_phone" id="claim_phone" value="" />
                            </div>
                        </div>
                        <div class="control-group"  style="margin-bottom: 10px;">
                            <label class="control-label" for="claim_comments"><?php _e('Claim Justification', 'twentyten') ?></label>
                            <div class="controls live-validation-textarea">
                                <textarea name="claim_comments" id="claim_comments" rows="4" cols="20"></textarea>
                            </div>
                        </div>
                        <div class="control-group"  style="margin-bottom: 10px;">
                            <div class="controls">
                                <input type="submit" class="btn btn-primary" id="send-claim-email-btn" value="Request Manual Verification">
                            </div>
                        </div>
                        <div class="control-group"  style="margin-bottom: 10px;display:none" id="errorcont_manualclaim_cont">
                          <div class="controls" id="errorcont_manualclaim">
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> -->
    
    <!-- Modal -->
    
	<script language="javascript">
		jQuery(document).ready(function(){
			jQuery('#claim_failure_btn').trigger('click');
		   	jQuery("#send_email_view_profile-form-claim").live('submit', function () {
                jQuery('#send-claim-email-btn', jQuery(this)).attr('disabled', 'disabled');
                $this = this;
                jQuery.ajax({
                    type : "post",
                    dataType : "html",
                    url : '<?php  echo get_template_directory_uri(); ?>/ajax/manual-claim-email.php',
                    data: {
                        'security':jQuery('#send-claim-email-itlocation-security', jQuery(this)).val(),
                        //'claim_to_email':jQuery('#claim_to_email', jQuery(this)).val(), 
						'claim_to_email':jQuery('#claim_email', jQuery(this)).val(),
                        'claim_name':jQuery('#claim_name', jQuery(this)).val(),
                        'claim_company':'<?php echo stripslashes($company_info->companyname); ?>',
                        'claim_title':jQuery('#claim_title', jQuery(this)).val(),
                        'claim_phone':jQuery('#claim_phone', jQuery(this)).val(),
                        'claim_comments':jQuery('#claim_comments', jQuery(this)).val(),
						'user_id':'<?php echo $company_info->user_id; ?>'						
					},
                    success: function(response) {
						// alert( response );
						if(response == '' ) {
							alert('Manual Claim Request Sent.You will be contacted within 24 hours by an IT Locator Claim Administrator');
							jQuery("#errorcont_manualclaim").html(''); 
							  jQuery("#errorcont_manualclaim_cont").hide();
							jQuery("#claim_failure .close").trigger('click');
						}else{
						   jQuery('#send-claim-email-btn').removeAttr('disabled');
						   jQuery("#errorcont_manualclaim_cont").show();
						   jQuery("#errorcont_manualclaim").html(response);  	
						}
						
						
                        // jQuery('#send-email-btn', jQuery($this)).removeAttr('disabled');
                        // if(!response.error) {
                            // jQuery('#email_title', jQuery($this)).val('');
                            // jQuery('#email_content', jQuery($this)).val('');
                            // jQuery('#send_email_<?php echo $id; ?>').modal('hide');
                        // }
                    }
                });
                return false;
            });
		});
	</script>
	
	<?php } else if( $exist_company == 'yes_in_linkedin' ) {
		
			$company_user_data = get_userdata( $company_info->user_id );			
			$login_name = $company_user_data->user_login;			
			$new_password = wp_generate_password(12, false);			
			wp_set_password( $new_password, $company_info->user_id );			
			global $functions_ph;						
			$email_from = get_option('admin_email');			
			$email_to = $company_user_data->user_email;;
			// $email_to = 'jc.hu920@gmail.com';
			
			$email_title = "Congratulations Your Claim of " . stripslashes($company_info->companyname) . " was Approved!";
			$email_content = get_option('itlocation_email_settings_claim_success_email_content');			
			$email_content = str_replace("[#name#]", $login_name, $email_content);			
			$email_content = str_replace("[#password#]", $new_password, $email_content);			
			$email_content = str_replace("[#company_name#]", stripslashes($company_info->companyname), $email_content);
			
			$functions_ph->send_mail($email_to, $email_from, $email_title, $email_content); ?>
	<a id="claim_success_btn" href="#claim_success" role="button" class="btn btn-large btn-primary" data-toggle="modal" style="display:none;">EMAIL VAR</a>
	<div id="claim_success" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="send-email-modal-label" aria-hidden="true">
        <div class="modal-header success-modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="send-email-modal-label"><?php _e('Congratulations!', 'twentyten') ?></h3>
        </div>
        <div class="modal-body">
            <div class="row-fluid">
				<p>Your LinkedIn Profile indicates that you are associated with <?php _e(stripslashes($company_info->companyname)) ?>.</p>
				<p>Please Check your email box for getting Login Info.</p>
            </div>
        </div>
    </div>
	<script language="javascript">
		jQuery(document).ready(function(){
			jQuery('#claim_success_btn').trigger('click');
		});
	</script>
	<?php } elseif($exist_company=="samelistsecond") { ?>
        
     <a id="claim_success_btn" href="#claim_success" role="button" class="btn btn-large btn-primary" data-toggle="modal" style="display:none;"></a>
	<div id="claim_success" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="send-email-modal-label" aria-hidden="true">
        <div class="modal-header success-modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="send-email-modal-label"><?php _e('Message', 'twentyten') ?></h3>
        </div>
        <div class="modal-body">
            <div class="row-fluid">
				<p>We have already received your claim request.  Please <a href="http://www.itlocator.com/contact-us/">contact us</a> if you have a question or an issue.</p>
            </div>
        </div>
    </div>
	<script language="javascript">
		jQuery(document).ready(function(){
			jQuery('#claim_success_btn').trigger('click');
		});
	</script>
     <?php }} ?>

<?php get_footer(); ?>



	<div class="modal fade" id="claim_failure" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog pop-up">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel"> <?php _e('We Couldn’t Verify You', 'twentyten') ?></h4>
                </div>
                <div class="modal-body">
                
                    <div class="displayB" id="jquery-live-validation-send_email_claim">
                        <p>System could not fully authenticate your claim.  We require further information to process your profile claim request.  Please provide the following information and we will manually verify your claim to <strong><?php echo stripslashes($company_info->companyname); ?></strong> listing.</p>
                        <form name="send_email_view_profile-form-claim" id="send_email_view_profile-form-claim" action="" method="post" style="margin:0;">
                            <?php wp_nonce_field('send-email-itlocation', 'send-claim-email-itlocation-security'); ?>                        
                            <div class="form-group">
                                <label class="control-label" for="claim_name"><?php _e('Name', 'twentyten') ?> <span class="imp_star_mark">*</span></label>
                                <div class="">
                                    <input type="text" name="claim_name" id="claim_name" class="form-control" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="claim_title"><?php _e('Title', 'twentyten') ?> <span class="imp_star_mark">*</span></label>
                                <div class="">
                                    <input type="text" name="claim_title" id="claim_title" value="" class="form-control" />
                                </div>
                            </div>
                          <div class="form-group">
                                <label class="control-label" for="claim_email"><?php _e('Email', 'twentyten') ?> <span class="imp_star_mark">*</span></label>
                                <div class="">
                                    <input type="text" name="claim_email" id="claim_email" value="" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="claim_phone"><?php _e('Phone', 'twentyten') ?><span class="imp_star_mark">*</span></label>
                                <div class="">
                                    <input type="text" name="claim_phone" id="claim_phone" value="" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="claim_comments"><?php _e('Claim Justification', 'twentyten') ?></label>
                                <div class=" live-validation-textarea">
                                    <textarea name="claim_comments" id="claim_comments" rows="4" cols="20" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="">
                                    <input type="submit" class="btn btn-success" id="send-claim-email-btn" value="Request Manual Verification">
                                </div>
                            </div>
                            <div class="control-group"  style="margin-bottom: 10px;display:none" id="errorcont_manualclaim_cont">
                              <div class="controls" id="errorcont_manualclaim">
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                
                </div>
                
            </div>
        </div>
    </div>