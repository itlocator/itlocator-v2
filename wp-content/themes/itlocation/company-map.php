<?php



global $company_id, $address_ctrl_fg;







$model = new companyModelItlocation();



$addresses = $model->get_a_comp_address_by_comp_id( $company_id );



$current_company = $model->get_by_id( $company_id );







$info = array();



// echo '<pre>';print_r( $addresses );echo '</pre>';



foreach( $addresses as $address ){



	$info[] = array(



		'x' 		=> $address->lat,



		'y' 		=> $address->lng,



		#'user_role' => $address->user_role,



	);

}





$icon_url = get_bloginfo('template_url') . '/images/marker-free.png';



if( $current_company->user_role == '1' ){



    $icon_url = get_bloginfo('template_url') . '/images/marker-basic.png';

} else if ($current_company->user_role == '2'){



    $icon_url = get_bloginfo('template_url') . '/images/marker-premium.png';

}

?>





<div class="row-fluid">



    <div class="span12">



        <script language="javascript">



            function initialize() {				



                var myOptions = {

                

                    zoom:  <?php if( count( $info ) ){echo "18";}else{echo "5";}?>,

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



			<?php



				}



			?>



				if( $markerBounds != null ) {



					$map.setCenter( $markerBounds.getCenter() );

                    //$map.initialZoom = true;

				    $map.fitBounds( $markerBounds );



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



            <div class="clearfix"></div><br/>



            <div class="row-fluid">



                <div id="ctrl-groups">



                    <h3><?php _e('Additional Locations', 'twentyten') ?></h3>



                    <?php



						if( $num == -1 ){



							for( $i = 0; $i < count($addresses); $i++ ){



                    ?>



								<div class="ctrl-group ctrl-group-location">



									<label class="pull-left"><?php _e('Location', 'twentyten') ?></label>



									<input type="text"  name="locations[]" class="locations span8" value="<?php echo $addresses[$i]->address; ?>"> <i class="iconic-o-x delete_comp_address font-size-20 cursor-pointer" role="<?php echo $current_company->user_role; ?>"></i>



									<div class="clearfix margin-only-bottom-10"></div>



									<input type="button" value="<?php _e('Set Address on Map', 'twentyten') ?>" class="setting_lat_lng btn btn-mini btn-info pull-right" role="<?php echo $current_company->user_role; ?>" />



									<div class="clearfix margin-only-bottom-10"></div>



									<label class="pull-left"><?php _e('Latitude', 'twentyten') ?></label>



									<input type="text" name="locations_lat[]" class="locations_lat span8" value="<?php echo $addresses[$i]->lat; ?>" />



									<div class="clearfix margin-only-bottom-10"></div>



									<label class="pull-left"><?php _e('Longitude', 'twentyten') ?></label>



									<input type="text" name="locations_lng[]" class="locations_lng span8" value="<?php echo $addresses[$i]->lng; ?>" />



									<div class="clearfix margin-only-bottom-10"></div>



									<div class="underline"></div>



								</div>



					<?php



							}



						} else {



							$idx = 0;



							for ($i = 0; $i < $num; $i++) {



					?>



								<div class="ctrl-group ctrl-group-location">



									<label class="pull-left"><?php _e('Location', 'twentyten') ?></label>



									<input type="text" name="locations[]" class="locations span8" value="<?php echo $addresses[$idx]->address; ?>">



									<div class="clearfix margin-only-bottom-10"></div>



									<input type="button" value="<?php _e('Set Address on Map', 'twentyten') ?>" class="setting_lat_lng btn btn-mini btn-info pull-right" role="<?php echo $current_company->user_role; ?>"/>



									<div class="clearfix margin-only-bottom-10"></div>



									<label class="pull-left"><?php _e('Latitude', 'twentyten') ?></label>



									<input type="text" name="locations_lat[]" class="locations_lat span8" value="<?php echo $addresses[$idx]->lat; ?>" />



									<div class="clearfix margin-only-bottom-10"></div>



									<label class="pull-left"><?php _e('Longitude', 'twentyten') ?></label>



									<input type="text" name="locations_lng[]" class="locations_lng span8" value="<?php echo $addresses[$idx]->lng; ?>" />



									<div class="clearfix margin-only-bottom-10"></div>



									<div class="underline"></div>



								</div>



					<?php



								++$idx;



							}



						}



                    ?>



                </div>



            </div>



		<?php 

			if( $num == -1 ){ 

		?>



			<div class="ctrl-group">



				<input type="button" value="Add Other Address" class="btn btn-info" id="add-other-address" />



			</div>



			<div id="ctrl-group-company-address" class="display-none">



				<div class="ctrl-group ctrl-group-location">



					<label class="pull-left"><?php _e('Location', 'twentyten') ?></label>



					<input type="text" name="locations[]" class="locations span8" value=""> <i class="iconic-o-x delete_comp_address font-size-20 cursor-pointer" role="<?php echo $current_company->user_role; ?>"></i>



					<div class="clearfix margin-only-bottom-10"></div>



					<input type="button" value="<?php _e('Set Address on Map', 'twentyten') ?>" class="setting_lat_lng btn btn-mini btn-info pull-right" role="<?php echo $current_company->user_role; ?>" />



					<div class="clearfix margin-only-bottom-10"></div>



					<label class="pull-left"><?php _e('Latitude', 'twentyten') ?></label>



					<input type="text" name="locations_lat[]" class="locations_lat" value="">



					<div class="clearfix margin-only-bottom-10"></div>



					<label class="pull-left"><?php _e('Longitude', 'twentyten') ?></label>



					<input type="text" name="locations_lng[]" class="locations_lng" value="">



					<div class="clearfix margin-only-bottom-10"></div>



					<div class="underline"></div>



				</div>



			</div>



<?php



			}



        }



    }



}



?>