<?php
global $company_id, $address_ctrl_fg;
$model = new companyModelItlocation();
$addresses = $model->get_a_comp_address_by_comp_id( $company_id );
$current_company = $model->get_by_id($company_id );
$info = array();

//echo '<pre>';print_r( $addresses );echo '</pre>';

foreach( $addresses as $address ){
	$info[] = array(

		'x' 		=> $address->lat,
		'y' 		=> $address->lng,
		'user_role' => $address->user_role,

	);
}

//$icon_url = get_bloginfo('template_url') . '/images/marker-free.png';

if( $current_company->user_role == '0' ){
	
   $icon_url = get_bloginfo('template_url') . '/images/marker-free.png';
} else if ($current_company->user_role == '1'){

  $icon_url = get_bloginfo('template_url') . '/images/marker-basic.png';
} 
 else if ($current_company->user_role == '2'){

    $icon_url = get_bloginfo('template_url') . '/images/marker-premium.png';
}

$count = array();  // create an empty array

$zoom_size=0;
if(count($info[1]) !=0)
 {
$zoom_size=4;
 } 
 else { $zoom_size=14; } ?>

<style>
.form-control1{margin:0 auto; padding:0; position:relative; width:600px; height: 195px;}
.form-control1 p{margin:7px 0; padding:6px; border:1px solid #ccc; width:243px; height:34px; clear:both; float:left; border-radius:4px;}
.form-control1 p label{width:250px; margin:0; padding:0; float:left;}
.form-control1 p input[type="text"]{width:159px; margin:-20px 1px 0 0; padding:0; float:right; border:none; background:none; height:20px;}
.form-control1 input[type="button"]{width:150px; margin:5px 0; padding:0; float:left; clear:both; height:27px; cursor:pointer; }
.line{float:left; clear:both; border-bottom:2px solid #ccc; margin:0 0 10px 0; height:10px; width:265px;}
</style>
<div class="row-fluid">
    <div class="span12">
        <script language="javascript">
            function initialize() {		
                var myOptions = {  
             
                    //zoom:  <?php if( count( $info ) ){echo "4";} else{echo "14";}?>,
		zoom:<?php echo $zoom_size; ?>,

		center: new google.maps.LatLng(37.09024, -95.712891),

                    mapTypeControl: true,
                    mapTypeControlOptions: {
                        style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                    },

                    navigationControl: true,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                $map = new google.maps.Map( document.getElementById( "map-canvas" ), myOptions );

			<?php
				if( count( $info ) ){
			?>

                $userdata = <?php echo json_encode($info); ?>;
                make_marker_no_desc();

			<?php } ?>

				if( $markerBounds != null ) {
					$map.setCenter( $markerBounds.getCenter() );
                    $map.initialZoom = true;
				    //$map.fitBounds( $markerBounds );
				}

			}
		
			google.maps.event.addDomListener(window, 'load', initialize);
        </script>
       <div id="map-canvas" class="width-100-perc" style="height: 200px;"></div>
    </div>
</div>

<?php
if( $address_ctrl_fg ){
    if( is_user_logged_in() ){
        global $functions_ph;	
        $address_model = new addressModelItlocation();
        $addresses = $address_model->get_by_comp_id($current_company->id, 1, false);
        $num = $functions_ph->get_default_member_limit('locations', $current_company->user_role);
        if( $num != 0 ){

?>
			<div id="ctrl-groups">
 					<h4 class="page-title-diff"><?php _e('Additional Locations', 'twentyten') ?></h4>

                    <?php
						if( $num == -1 ){
							for( $i = 0; $i < count($addresses); $i++ ){ ?>

								<div class="form-group relative">
									<label><?php _e('Location', 'twentyten') ?></label>
									<input type="text" id="add_loc1" name="locations[]" class="locations form-control" value="<?php echo $addresses[$i]->address; ?>"> <i class="fa fa-times-circle delete_comp_address btn cursor-pointer" role="<?php echo $current_company->user_role; ?>" style="color: #428bca; position:absolute; right:0; top:0;"></i><br />
									<input type="button" value="<?php _e('Set Address on Map', 'twentyten') ?>" class="setting_lat_lng btn btn-sm btn-primary" role="<?php echo $current_company->user_role; ?>" />
                         </div>
									
                                    <div class="form-group">
                                        <label><?php _e('Latitude', 'twentyten') ?></label>
                                        <input type="text" name="locations_lat[]" class="locations_lat form-control" value="<?php echo $addresses[$i]->lat; ?>" />
                                	</div>
									
                                    <div class="form-group">
                                        <label><?php _e('Longitude', 'twentyten') ?></label>
                                        <input type="text" name="locations_lng[]" class="locations_lng form-control" value="<?php echo $addresses[$i]->lng; ?>" />
                                    </div>
								

					<?php } } else {
							$idx = 0;
							for ($i = 0; $i < $num; $i++) { ?>
					 <div id="ctrl-group-company-address">
								<div class="form-group">
									<label><?php _e('Location', 'twentyten') ?></label>
									<input type="text" id="add_loc2" name="locations[]" class="locations form-control" value="<?php echo $addresses[$idx]->address; ?>"><br />
                                     <input type="button" value="<?php _e('Set Address on Map', 'twentyten') ?>" class="setting_lat_lng btn btn-sm btn-primary" role="<?php echo $current_company->user_role; ?>"/><br>
                          <!--    </div>
                                 
                                 <div class="form-group">-->
									<label><?php _e('Latitude', 'twentyten') ?></label>
									<input type="text"  name="locations_lat[]" class="locations_lat form-control" value="<?php echo $addresses[$idx]->lat; ?>" />
                            <!-- 	</div>
                                 
                                 <div class="form-group">-->
									<label><?php _e('Longitude', 'twentyten') ?></label>
									<input type="text" name="locations_lng[]" class="locations_lng form-control" value="<?php echo $addresses[$idx]->lng; ?>" />
                                 </div>
                                  </div>
					<?php ++$idx; } } ?>
                    
		</div>
		<?php if( $num == -1 ){ ?>

			<div class="ctrl-group">
				<input type="button" value="Add Other Address" class="btn btn-sm btn-primary" id="add-other-address" />
			</div>

			<div id="ctrl-group-company-address" class="display-none">
				<!--<div class="form-control">
					<label><?php _e('Location', 'twentyten') ?></label>
					<input type="text"  name="locations[]" class="locations form-control" value=""> <i class="fa fa-times-circle delete_comp_address btn cursor-pointer" role="<?php echo $current_company->user_role; ?>"></i><br />
                    <input type="button" value="<?php _e('Set Address on Map', 'twentyten') ?>" class="setting_lat_lng btn btn-sm btn-primary" role="<?php echo $current_company->user_role; ?>" />
               </div>
    
                <div class="form-control">
                    <label><?php _e('Latitude', 'twentyten') ?></label>
                    <input type="text" name="locations_lat[]" class="locations_lat form-control" value="">
                </div>
    
                <div class="form-control">
                    <label><?php _e('Longitude', 'twentyten') ?></label>
                    <input type="text" name="locations_lng[]" class="locations_lng form-control" value="">
                </div>-->
	<div  class="form-control1">
	
		<p><label><?php _e('Location', 'twentyten') ?></label><input type="text" id="add_loc3" value="" class="locations form-control" name="locations[]" placeholder="Enter a location"></p>
		<i class="fa fa-times-circle delete_comp_address btn cursor-pointer" role="<?php echo $current_company->user_role; ?>" style="color: #428bca;"></i>
		
		<input type="button" value="<?php _e('Set Address on Map', 'twentyten') ?>" class="setting_lat_lng btn btn-sm btn-primary" role="<?php echo $current_company->user_role; ?>" />
		
		<p><label><?php _e('Latitude', 'twentyten') ?></label><input type="text" class="locations_lat form-control" name="locations_lat[]" value=""></p>
		<p><label><?php _e('Longitude', 'twentyten') ?></label><input type="text" value="" class="locations_lng form-control" name="locations_lng[]" value=""></p>
		

		</div>
			</div>

<?php } } } } ?>