function getParameterValue(name){
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]"+name+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var results = regex.exec( window.location.href );
	if( results == null ) return "";
	else return results[1];
}

function pageNavigationClick(){
	jQuery(".page-numbers").click(function(){
		$detect_no_parameter = location.href.split('?');
	
		if( $detect_no_parameter.length == 1 ){
			$detect_no_parameter = true;
		} else if( $detect_no_parameter[1] == '' ){
			$detect_no_parameter = true;
		} else {
			$detect_no_parameter = false;
		}
	
		if( getParameterValue('roundmylocation') == 'true' || $detect_no_parameter ) {
			$url = location.href;
			$start = $url.indexOf("?");
			
			$pagenum = jQuery(this).text();
			if( jQuery(this).hasClass('prev') ){
				$pagenum = jQuery(".page-numbers.current").text() * 1 - 1;
			} else if( jQuery(this).hasClass('next') ){
				$pagenum = jQuery(".page-numbers.current").text() * 1 + 1;
			}
			
			center = jQuery.cookie("address_pos");
			var res = center.split(',');
								
			//showing data
			$url_paramlist = 'page=' + $pagenum + '&lat=' + res[0] + '&lng=' + res[1] + '&action=new-advanced-search-map-round-my-location-itlocation-html&' + $url.substring($start + 1, $url.length);
			
			jQuery("#company_search_list").html(jQuery("#content_loading_process").html());
			
			jQuery.ajax({
				type : 'post',
				dataType : 'html',
				url : admin_ajax.url,
				data : $url_paramlist,
				success: function(response) {
					jQuery("#company_search_list").html(response);
					pageNavigationClick();
				}
			});
			
			//drawing map
			$url_paramlist = 'lat=' + res[0] + '&lng=' + res[1] + '&action=new-advanced-search-map-round-my-location-itlocation&' + $url.substring($start + 1, $url.length);
			
			jQuery.ajax({
				type : 'post',
				dataType : 'json',
				url : admin_ajax.url,
				data : $url_paramlist,
				success: function(response) {
					// console.log(response);
					jQuery('.map-search-large-loading').hide();
					// return;
					
					$userdata = response;
					
					make_marker();
					
					center = jQuery.cookie("address_pos");
					var res = center.split(',');
								
					var center = new google.maps.LatLng( res[0], res[1] );
					$map.setZoom( 9 );
					$map.setCenter( center );
				}
			});
		} else {
			$url = location.href;
			$start = $url.indexOf("?");
			
			$pagenum = jQuery(this).text();
			if( jQuery(this).hasClass('prev') ){
				$pagenum = jQuery(".page-numbers.current").text() * 1 - 1;
			} else if( jQuery(this).hasClass('next') ){
				$pagenum = jQuery(".page-numbers.current").text() * 1 + 1;
			}
			
			//showing data
			$url_paramlist = 'page=' +$pagenum + '&action=new-advanced-search-map-itlocation-html&' + $url.substring($start + 1, $url.length);
			
			jQuery("#company_search_list").html(jQuery("#content_loading_process").html());
			
			jQuery.ajax({
				type : 'post',
				dataType : 'html',
				url : admin_ajax.url,
				data: $url_paramlist,
				success: function( response ){
					jQuery("#company_search_list").html(response);
					pageNavigationClick();
				}
			});		
			
			//draw google map
			$url_paramlist = 'action=new-advanced-search-map-itlocation&' + $url.substring($start + 1, $url.length);
			
			jQuery.ajax({
				type : 'post',
				dataType : 'json',
				url : admin_ajax.url,
				data: $url_paramlist,
				success: function(response) {
					// console.log(response);
					jQuery('.map-search-large-loading').hide();
					// return;
					
					$userdata = response;
					
					make_marker();
					
					if( jQuery( "#co" ).val() ){
						var geocoder = new google.maps.Geocoder();
						geocoder.geocode({
							'address': jQuery("#co option:eq(" + jQuery("#co")[0].selectedIndex + ")").html()
						}, function (results, status) {
							lat = results[0].geometry.location.lat();
							lng = results[0].geometry.location.lng();
							var center = new google.maps.LatLng(lat, lng);
							$map.setZoom(5);
							$map.setCenter(center);
						});
					} else {
						doneCb();
						var center = new google.maps.LatLng($c_lat, $c_long);
						$map.setCenter(center);
					}
				}
			});
		}
		return false;
	});
}

jQuery(document).ready(function(){
	jQuery("#roundmylocation").click(function(){
		if( this.checked == true ) {
			jQuery("#lo").attr("disabled", "disabled");
			jQuery("#co").attr("disabled", "disabled");
		} else {
			jQuery("#lo").removeAttr("disabled");
			jQuery("#co").removeAttr("disabled");
		}
	});
});

function getAllAroundList( lat, lng ){
	$url = location.href;
	$start = $url.indexOf("?");
		
	//showing data
	$url_paramlist = 'page=1&lat=' + lat + '&lng=' + lng + '&action=new-advanced-search-map-round-my-location-itlocation-html&' + $url.substring($start + 1, $url.length);
	
	jQuery.ajax({
		type : 'post',
		dataType : 'html',
		url : admin_ajax.url,
		data : $url_paramlist,
		success: function(response) {
			jQuery("#company_search_list").html(response);
			pageNavigationClick();
		}
	});
	
	//drawing map
	$url_paramlist = 'lat=' + lat + '&lng=' + lng + '&action=new-advanced-search-map-round-my-location-itlocation&' + $url.substring($start + 1, $url.length);
	
	jQuery.ajax({
		type : 'post',
		dataType : 'json',
		url : admin_ajax.url,
		data : $url_paramlist,
		success: function(response) {
			// console.log(response);
			jQuery('.map-search-large-loading').hide();
			// return;
			
			$userdata = response;
			
			make_marker();
			
			center = jQuery.cookie("address_pos");
			var res = center.split(',');
								
			var center = new google.maps.LatLng( res[0], res[1] );
			$map.setZoom( 9 );
			$map.setCenter( center );
		}
	});
}

function initialize(){	
	var myOptions = {
		zoom: 5,
		center: new google.maps.LatLng(37.09024, -95.712891),
		mapTypeControl: true,
		mapTypeControlOptions: { style: google.maps.MapTypeControlStyle.DROPDOWN_MENU },
		navigationControl: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	
	$map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
	
    jQuery('.map-search-large-loading').show();
    remove_markers( "map-canvas" );
	
	$detect_no_parameter = location.href.split('?');
	
	if( $detect_no_parameter.length == 1 ){
		$detect_no_parameter = true;
	} else if( $detect_no_parameter[1] == '' ){
		$detect_no_parameter = true;
	} else {
		$detect_no_parameter = false;
	}
	
	if( getParameterValue('roundmylocation') == 'true' || $detect_no_parameter ) {
		jQuery('#roundmylocation').trigger('click');
		jQuery('#lo').attr('disabled', 'disabled');
		jQuery('#co').attr('disabled', 'disabled');
		
		address_itlocation = jQuery.cookie("address_itlocation");
		if( address_itlocation == null ){
			if( navigator.geolocation ){    
				navigator.geolocation.getCurrentPosition(function( position ){
					var pos = new google.maps.LatLng( position.coords.latitude, position.coords.longitude );
					var latlong = new google.maps.LatLng( position.coords.latitude, position.coords.longitude );
					geocoder = new google.maps.Geocoder();
					geocoder.geocode( {'latLng': latlong}, function( results, status ){
						if( status == google.maps.GeocoderStatus.OK ){
							if( results[1] ) {
								var today = new Date();
								today.setTime( today.getTime() );
								var expires = 30;
								expires = expires * 1000 * 60 * 60 * 24; 
								jQuery.cookie( "address_pos", position.coords.latitude + "," + position.coords.longitude, { expires: new Date( today.getTime() + expires ) } );
								jQuery.cookie( "address_itlocation", results[1].formatted_address, { expires: new Date( today.getTime() + expires ) } );
								jQuery.cookie( "first_visit_date", today.getTime(), { expires: 30 } );
								$str = '<span class="iconic-map-pin font-size-16" style=""></span> <span class="font-size-12 font-weight-bold font-color-393939">' + jQuery.cookie('address_itlocation') + '</span>';
								
								if( jQuery.cookie( 'address_itlocation' ) ){
									jQuery("#location-str").html($str);
								}
								
								center = jQuery.cookie("address_pos");
								var res = center.split(',');
								
								getAllAroundList(res[0], res[1]);
											
								jQuery('#subscriber-itlocation-modal').modal('show');
							} else {
								alert('No results found');
							}
						} else {
							alert('Geocoder failed due to: ' + status);
						}
					});
				}, function(error) {
					switch(error.code) {
						case error.PERMISSION_DENIED:
							break;
						case error.POSITION_UNAVAILABLE:
							break;
						case error.TIMEOUT:
							alert("The request to get user location timed out.");
							break;
						case error.UNKNOWN_ERROR:
							alert("An unknown error occurred.");
							break;
					}
					handleNoGeolocation(true); 
				});
			} else {
				// Browser doesn't support Geolocation
				handleNoGeolocation(false);
			}
		} else {
			$str = '<span class="iconic-map-pin font-size-16" style=""></span> <span class="font-size-12 font-weight-bold font-color-393939">' + jQuery.cookie('address_itlocation') + '</span>';
			jQuery("#location-str").html($str);
			first_date_visit = jQuery.cookie("first_visit_date");
			var today = new Date();
			var time_diff = today.getTime() - parseFloat(first_date_visit);
			time_diff = parseInt(time_diff / (1000 * 60 * 60 *24));
			if(time_diff > 3 || time_diff == 3){
				jQuery.removeCookie("not_now_subscriber_itlocation");
				jQuery.cookie("first_visit_date", today.getTime(), {expires: 30 });
				jQuery('#subscriber-itlocation-modal').modal('show');   
			}
			center = jQuery.cookie("address_pos");
			var res = center.split(',');
			
			getAllAroundList(res[0], res[1]);
		}
	} else {
		$url = location.href;
		$start = $url.indexOf("?");
		
		//showing data
		$url_paramlist = 'page=1&action=new-advanced-search-map-itlocation-html&' + $url.substring($start + 1, $url.length);
		
		jQuery.ajax({
			type : 'post',
			dataType : 'html',
			url : admin_ajax.url,
			data: $url_paramlist,
			success: function( response ){
				jQuery("#company_search_list").html(response);
				pageNavigationClick();
			}
		});		
		
		//draw google map
		$url_paramlist = 'action=new-advanced-search-map-itlocation&' + $url.substring($start + 1, $url.length);
		
		jQuery.ajax({
			type : 'post',
			dataType : 'json',
			url : admin_ajax.url,
			data: $url_paramlist,
			success: function(response) {
				// console.log(response);
				jQuery('.map-search-large-loading').hide();
				// return;
				
				$userdata = response;
				
				make_marker();
				
				if( jQuery( "#co" ).val() ){
					var geocoder = new google.maps.Geocoder();
					geocoder.geocode({
						'address': jQuery("#co option:eq(" + jQuery("#co")[0].selectedIndex + ")").html()
					}, function (results, status) {
						lat = results[0].geometry.location.lat();
						lng = results[0].geometry.location.lng();
						var center = new google.maps.LatLng(lat, lng);
						$map.setZoom(5);
						$map.setCenter(center);
					});
				} else {
					doneCb();
					var center = new google.maps.LatLng($c_lat, $c_long);
					$map.setCenter(center);
				}
			}
		});
	}
}

google.maps.event.addDomListener(window, 'load', initialize);