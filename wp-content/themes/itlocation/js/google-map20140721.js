function isEmptyObject(obj) {
    var name;
    for (name in obj) {
        return false;
    }
    return true;
}

function getParameterValue(name){
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]"+name+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var results = regex.exec( window.location.href );
	if( results == null ) return "";
	else return results[1];
}

var $c_lat = '';
var $c_long = '';
var $map = null;
var $userdata = null;
var $markers = [];
var $infowindow;
var $zoom = 8;
var $markerBounds = null;
var $myprofile_mark = null;

function doneCb() {
    var $lat = 0;
    var $long = 0;
    var west = 0;
    var east = 0;
    var counter = 0; 

    if($userdata != null) {
        var i;
        for (i = 0; i < $userdata.length; i++) {
            if($userdata[i] == null) continue;
            if(i == 0) {
                west = $userdata[i].x;
                east = $userdata[i].x;
            } else {
                if($userdata[i].x > west)
                    west = $userdata[i].x;
                if($userdata[i].x < east)
                    east = $userdata[i].x;
            }
            if($userdata[i].x)
                $lat += parseFloat($userdata[i].x);
            if($userdata[i].y)
                $long += parseFloat($userdata[i].y);
            counter++;
        }
        $zoom = 7 - Math.round(Math.log(Math.abs(west-east))/Math.log(2));
        if(west == east)
            $zoom = 13;
        if(Math.abs(west-east) < Math.log(2))
            $zoom = 13;
        
        $c_long = $long / counter;
        $c_lat = $lat / counter;
    }
}


function make_marker_search(){
    $idx = 0;
    isCentered = 0;
    
    for(var n = 0; n < $markers.length; n++){
		$markers[n].setMap(null);
    }
	
    if($userdata != null) {
        for (var i = 0; i < $userdata.length; i++) {
			if($userdata[i] == null) continue;
            if($userdata[i].x == '' || $userdata[i].x == '0') continue;
            var myLatLng = new google.maps.LatLng($userdata[i].x, $userdata[i].y);
            
            if(isCentered == 0){
				isCentered = 1; 
				$map.setCenter(myLatLng); 
				$map.setZoom(9);  
            }
            
            if($markers[$idx]){
                $markers[$idx].setMap(null);
            }
            
            var $icon_url = images.url + 'marker-free-map.png';
            var bgcolor='#404040';
            var bordercolor='#404040';
			
            if($userdata[i].user_role >= 2){
                bgcolor='#C1272D';
                bordercolor='#C1272D';
                $icon_url = images.url + 'marker-premium-map.png';
            } else if($userdata[i].user_role == 1) {
                $icon_url = images.url + 'marker-basic-map.png';
                bgcolor='#F7931E';
                bordercolor='#F7931E';
            }

            $markers[$idx] = new google.maps.Marker({
                position: myLatLng,
                map: $map,
                icon : $icon_url,
                title: $userdata[i].address
            });
                
            info_content = '<div>';
            if($userdata[i].user_role >= 1 && $userdata[i].logo_url) {
                info_content += '<img src="'+ $userdata[i].logo_url+'" width="90" class="pull-left" style="margin-right: 5px; margin-bottom: 10px;display: block;"/>';
                info_content += '<a href="'+$userdata[i].permalink+'" class="font-color-fff text-decoration-none" style="display: block;"><h4 class="font-size-14">'+$userdata[i].name+'</h4><div class="font-size-14" style="width: 310px; display: block;">';
            } else {
                info_content += '<a href="'+$userdata[i].permalink+'" class="font-color-fff text-decoration-none" style="display: block;"><h4 class="font-size-14">'+$userdata[i].name+'</h4><div class="font-size-14" style="width: 200px; display: block;">';
            }
            
            if($userdata[i].user_role >= 1 && $userdata[i].description) {
                info_content += '<div>' + $userdata[i].description + '</div>';
            }
            
			if($userdata[i].user_role > 1 && $userdata[i].rating != 'no') {
                rating = ($userdata[i].rating/5)*100;
                info_content += '<div class="star-rating" style="float: left"><span style="width:'+rating+'%"></span></div>';
            }
			
            info_content += '</div></a></div>';
            var infowindow = new google.maps.InfoWindow({
                content: info_content
            });
            
            attachWindow($markers[$idx], infowindow, bgcolor);

            ++$idx;
        }
    }
}


function make_marker(){
    $idx = 0;
    if( $userdata != null ){
		$markerBounds = new google.maps.LatLngBounds();
		
        for ( var i = 0; i < $userdata.length; i++ ){
            if( $userdata[i] == null || $userdata[i].x == null || $userdata[i].x == '' || $userdata[i].x == '0' || $userdata[i].y == null || $userdata[i].y == '' || $userdata[i].y == '0' ){
				continue;
			}
 
            var myLatLng = new google.maps.LatLng( $userdata[i].x, $userdata[i].y );
			
            $markerBounds.extend( myLatLng );
			
            if( $markers[$idx] ){
                $markers[$idx].setMap(null);
            }
            
            var $icon_url = images.url + 'marker-free-map.png';
            var bgcolor = '#404040';
            var bordercolor = '#404040';
			
			if( $userdata[i].myloc == 'true' ){
				bgcolor = '#6490f4';
				bordercolor = '#6490f4';
				$icon_url = images.url + 'mylocation.png';
				$userdata[i].address = 'My Location';
				$userdata[i].name = 'My Location';
            } else if( $userdata[i].user_role >= 2 ){
                bgcolor = '#C1272D';
                bordercolor = '#C1272D';
                $icon_url = images.url + 'marker-premium-map.png';
            } else if($userdata[i].user_role == 1) {
                $icon_url = images.url + 'marker-basic-map.png';
                bgcolor='#F7931E';
                bordercolor='#F7931E';
            }

            $markers[$idx] = new google.maps.Marker({
                position: myLatLng,
                map: $map,
                icon : $icon_url,
                title: $userdata[i].address
            });
                
            info_content = '<div>';
            if($userdata[i].user_role >= 1 && $userdata[i].logo_url) {
                info_content += '<img src="'+ $userdata[i].logo_url+'" width="90" class="pull-left" style="margin-right: 5px; margin-bottom: 10px;display: block;"/>';
                info_content += '<a href="'+$userdata[i].permalink+'" class="font-color-fff text-decoration-none" style="display: block;"><h4 class="font-size-14">'+$userdata[i].name+'</h4><div class="font-size-14" style="width: 310px; display: block;">';
            } else {
                info_content += '<a href="'+$userdata[i].permalink+'" class="font-color-fff text-decoration-none" style="display: block;"><h4 class="font-size-14">'+$userdata[i].name+'</h4><div class="font-size-14" style="width: 200px; display: block;">';
            }
            
            if( $userdata[i].user_role >= 1 && $userdata[i].description) {
                info_content += '<div>' + $userdata[i].description + '</div>';
            }
            if( $userdata[i].user_role > 1 && $userdata[i].rating != 'null' && $userdata[i].myloc != 'true' ) {
                rating = ($userdata[i].rating/5)*100;
                info_content += '<div class="star-rating" style="float: left"><span style="width:'+rating+'%"></span></div>';
            }
            info_content += '</div></a></div>';
            var infowindow = new google.maps.InfoWindow({
                content: info_content
            });
            
            attachWindow($markers[$idx], infowindow, bgcolor);

            ++$idx;
        }
    }
}
var prev_infowindow =false; 
function attachWindow(marker, infowindow, bgcolor){
    google.maps.event.addListener(marker, 'click', function(){
        if( prev_infowindow ) {
            prev_infowindow.close();
        }
        prev_infowindow = infowindow;
        infowindow.open($map, marker);
        jQuery("div:nth-child(4)",jQuery('.gm-style-iw').prev()).css('background-color', bgcolor);
        $obj_r = jQuery("div:nth-child(3)",jQuery('.gm-style-iw').prev());
        jQuery("div:nth-child(1)", jQuery("div:nth-child(1)", $obj_r)).css('background-color', bgcolor);
        jQuery("div:nth-child(1)", jQuery("div:nth-child(2)", $obj_r)).css('background-color', bgcolor);
    });
}

function doneCb_profile($x, $y) {
    var $lat = 0;
    var $long = 0;
    var west = 0;
    var east = 0;
    var counter = 0; 

    if($x != null) {
        var i;
        for (i = 0; i < $x.length; i++) {
            if($x[i] == null) continue;
            if(i == 0) {
                west = $x[i];
                east = $y[i];
            } else {
                if($x[i] > west)
                    west = $x[i];
                if($x[i] < east)
                    east = $x[i];
            }
            if($x[i])
                $lat += parseFloat($x[i]);
            if($y[i])
                $long += parseFloat($x[i]);
            counter++;
        }
        $zoom = 9 - Math.round(Math.log(Math.abs(west-east))/Math.log(2));
        if(west == east)
            $zoom = 9;
        if(Math.abs(west-east) < Math.log(2))
            $zoom = 9;
        
        $c_long = $long / counter;
        $c_lat = $lat / counter;
    }
}

function make_marker_no_desc(){
    $x = new Array();
    $y = new Array();
	
	$markerBounds = new google.maps.LatLngBounds();
	
    if( $userdata != null ){
        for( var i = 0; i < $userdata.length; i++ ){
            if( $userdata[i] == null ) continue;

            var $icon_url = images.url + 'marker-free.png';
            if( $userdata[i].user_role >= 2 ){
                $icon_url = images.url + 'marker-premium.png';
            } else if( $userdata[i].user_role == 1 ){
                $icon_url = images.url + 'marker-basic.png';
            }
			
			var myLatLng = new google.maps.LatLng( $userdata[i].x, $userdata[i].y );
			$markerBounds.extend( myLatLng );
	
            $markers.push( new google.maps.Marker({
                position: myLatLng,
                map: $map,
                icon : $icon_url,
                title: ''
            }) );
            
            $x.push( $userdata[i].x );
            $y.push( $userdata[i].y );
        }
    }
}

function attachInfobubble(marker,infoBubble) {
    google.maps.event.addListener(marker, 'mouseover', function(){
        infoBubble.open($map, marker);
        infoBubble.setArrowPosition(80);
    });
    google.maps.event.addListener(marker, 'mouseover', function(){
        //jQuery(document).find('.phoney').parent().css('overflow', 'hidden');
        });
    google.maps.event.addListener(marker, 'mouseout', function() {
        infoBubble.close($map, marker);
    });
}
    
function attachMessage(marker, msg) {
    var myEventListener = google.maps.event.addListener(marker, 'mouseover', function() {
        if ($infowindow) $infowindow.close();
        $infowindow = new google.maps.InfoWindow({
            content: String(msg) ,
            arrowPosition:10,          
            arrowSize:10

        });
        $infowindow.open($map, marker);
    });

    var myEventListener = google.maps.event.addListener(marker, 'mouseout', function() {
        $infowindow.close();
    });
}

function remove_markers() {
    for( var i = 0; i < $markers.length; i++ ){
        $markers[i].setMap(null);
    }
	
    $markers.length=0;
}

function handleNoGeolocation(errorFlag) {
    if (errorFlag) {
        var content = 'Error: The Geolocation service failed.';
    } else {
        var content = 'Error: Your browser doesn\'t support geolocation.';
    }
    
}

function reflesh_map_by_location( $icon_url, $root_val ){
    remove_markers();
	$markers = [];
	
    var geocoder = new google.maps.Geocoder();
	$markerBounds = new google.maps.LatLngBounds();
	
	$main_address = jQuery( '#address1' ).val() + ',' + jQuery( '#comp_city' ).val() + ',' + jQuery( '#comp_state' ).val() + ',' + jQuery( '#comp_country' ).val();
	
	if( $main_address != '' ){
		geocoder.geocode({
			'address': $main_address
		}, function( results, status ){
			if( status == google.maps.GeocoderStatus.OK ){
				var myLatLng = new google.maps.LatLng( results[0].geometry.location.lat(), results[0].geometry.location.lng() );
				$markerBounds.extend( myLatLng );

				$markers.push( new google.maps.Marker({
					position: myLatLng,
					map: $map,
					icon : $icon_url,
					title: ''
				}) );
			}
		});
	}
	
    jQuery( '.locations' ).each(function( i ){
		var $this = this;
        if( jQuery( this ).val() ){
            geocoder.geocode({
                'address': jQuery( this ).val()
            }, function( results, status ){
                if( status == google.maps.GeocoderStatus.OK ){
					var myLatLng = new google.maps.LatLng( results[0].geometry.location.lat(), results[0].geometry.location.lng() );
					$markerBounds.extend( myLatLng );

                    $markers.push( new google.maps.Marker({
                        position : myLatLng,
                        map : $map,
                        icon : $icon_url,
                        title: ''
                    }) );
					
					jQuery(".locations_lat" , jQuery($this).parent()).val( results[0].geometry.location.lat() );
					jQuery(".locations_lng" , jQuery($this).parent()).val( results[0].geometry.location.lng() );
                }
            });
        }
    });
	
	$map.setZoom( 0 );
}

jQuery(document).ready(function() {
    lat_a = [];
    lng_a = [];
    
	jQuery( ".setting_lat_lng" ).live('click', function(){
        if( jQuery( '.locations', jQuery(this).parent()).val() ){
			var $icon_url = images.url + 'marker-free.png';
            if( jQuery( this ).attr( 'role' ) >= 2 ){
                $icon_url = images.url + 'marker-premium.png';
            } else if( jQuery( this ).attr('role') == 1 ){
                $icon_url = images.url + 'marker-basic.png';
            }
			
            reflesh_map_by_location( $icon_url, '' );
        }
    });  
    
    jQuery("#main_address_setting_lat_lng").live('click', function() {
        if(jQuery('#comp_country').val()) {
            var $icon_url = images.url + 'marker-free.png';
            if(jQuery(this).attr('role') >= 2) {
                $icon_url = images.url + 'marker-premium.png';
            } else if(jQuery(this).attr('role') == 1) {
                $icon_url = images.url + 'marker-basic.png';
            }

            var geocoder = new google.maps.Geocoder();
            var address = jQuery('#address1').val() + ',' + jQuery('#comp_city').val() + ',' + jQuery('#comp_state').val() + ',' + jQuery('#comp_country').val();
            geocoder.geocode({
                'address': address
            }, function (results, status) {
                lat = results[0].geometry.location.lat();
                lng = results[0].geometry.location.lng();
                var center = new google.maps.LatLng(lat, lng);
				
				if( $myprofile_mark ){
					$myprofile_mark.setMap(null);
				}
				
                $myprofile_mark = new google.maps.Marker({
                    position: center,
                    map: $map,
                    icon : $icon_url,
                    title: ''
                });
				
                jQuery('#latitude').val(lat);
                jQuery('#longitude').val(lng);
                $map.setCenter(center);
            });
        }
    });  
    
    jQuery("#search-map-form").live('submit', function() {
        search_map('search-map-form');
        return false;
    });
});

/*
* map search in index
*/
function search_map( $id ){
    $obj = jQuery('#' + $id);
    jQuery('#search-map-btn', $obj).attr('disabled', 'disabled');
    jQuery('.map-search-large-loading').show();

	remove_markers( "map-canvas" );
	
	if( jQuery("#countries").val() == 'mylocation' ){
		center = jQuery.cookie("address_pos");
		var res = center.split(',');
									
		$url_paramlist = 'lat=' + res[0] + '&lng=' + res[1] + '&action=new-advanced-search-map-round-my-location-itlocation&ke=' + jQuery('#keywords').val() + jQuery('#se').serialize();

		jQuery.ajax({
			type : 'post',
			dataType : 'json',
			url : admin_ajax.url,
			data : $url_paramlist,
			success: function(response) {
				jQuery('.map-search-large-loading').hide();
				
				$userdata = response;
				
				make_marker();
				
				if( $markerBounds != null ) {
					$map.setCenter( $markerBounds.getCenter() );
					if( response.length > 1 ){
						$map.fitBounds( $markerBounds );
					} else {
						$map.setZoom(9);
					}
				}
				
				jQuery("#search-map-btn").removeAttr('disabled');
			}
		});
	} else {
		jQuery.ajax({
			type : 'post',
			dataType : 'json',
			url : admin_ajax.url,
			data: {
				'action' : 'new-search-map-itlocation',
				'security' : jQuery('#new-search-map-security').val(),
				'countries' : jQuery('#countries', $obj).val(),
				'services' : jQuery('#se', $obj).val(),
				'keywords' : jQuery('#keywords', $obj).val()
			},
			success: function(response) {
				jQuery('.map-search-large-loading').hide();
				jQuery('#search-map-btn', $obj).removeAttr('disabled');
				$userdata = response;
				
				if( isEmptyObject( response ) ){
					
					var myOptions = {
						zoom: 5,
						center: new google.maps.LatLng(37.09024, -95.712891),
						mapTypeControl: true,
						mapTypeControlOptions: { style: google.maps.MapTypeControlStyle.DROPDOWN_MENU },
						navigationControl: true,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					}
					
					$map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
	
				} else {
					make_marker();
					
					if( $markerBounds != null ) {
						$map.setCenter( $markerBounds.getCenter() );
						$map.fitBounds( $markerBounds );
					}
				}
			}
		});
	}
}

function search_map_init( $id ) {
    $obj = jQuery('#' + $id);
    jQuery('#search-map-btn', $obj).attr('disabled', 'disabled');
    jQuery('.map-search-large-loading').show();
	
    remove_markers( $id );
	
	address_itlocation = jQuery.cookie("address_pos");
	
	if( address_itlocation != null ){
		var res = address_itlocation.split(',');
								
		$url_paramlist = 'lat=' + res[0] + '&lng=' + res[1] + '&action=new-advanced-search-map-round-my-location-itlocation';
	
		jQuery.ajax({
			type : 'post',
			dataType : 'json',
			url : admin_ajax.url,
			data : $url_paramlist,
			success: function(response) {
				jQuery('.map-search-large-loading').hide();
				
				$userdata = response;
				
				make_marker();
				
				if( $markerBounds != null ) {
					$map.setCenter( $markerBounds.getCenter() );
					
					if( response.length > 1 ){
						$map.fitBounds( $markerBounds );
					} else {
						$map.setZoom(9);
					}
				}
				
				jQuery("#search-map-btn").removeAttr('disabled');
			}
		});
	} else {
		jQuery.ajax({
			type : 'post',
			dataType : 'json',
			url : admin_ajax.url,
			data: {
				'action' : 'new-search-map-itlocation',
				'security' : jQuery('#new-search-map-security').val(),
				'countries' : 'US',
				'services' : jQuery('#se', $obj).val(),
				'keywords' : jQuery('#keywords', $obj).val()
			},
			success: function(response) {
				jQuery('.map-search-large-loading').hide();
				jQuery('#search-map-btn', $obj).removeAttr('disabled');
				$userdata = response;
				
				make_marker();
				
				if( $markerBounds != null ) {
					$map.setCenter( $markerBounds.getCenter() );
					$map.fitBounds( $markerBounds );
				}
			}
		});
		
		jQuery("#countries")[0].selectedIndex = 2;
		jQuery("#countries").select2({
			formatResult: countries_format,
			formatSelection: countries_format,
			placeholder: "Select Country",
			allowClear: true,
			escapeMarkup: function(m) {
				return m;
			}
		});
	}
}