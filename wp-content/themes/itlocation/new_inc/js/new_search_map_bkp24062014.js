function homepage_go_advanced_search(){
	var service_param = jQuery("#se").serialize();
	
	var advanced_search_url = '';
	
	if(  jQuery("#countries").val() == 'mylocation' ){
		advanced_search_url = "http://www.itlocator.com/advanced-search/?lo=&co=&ke=" + jQuery("#keywords").val() + "&" + service_param + "&advanced-search-btn=Search&roundmylocation=true";
	} else {
		advanced_search_url = "http://www.itlocator.com/advanced-search/?bycountry=true&lo=&co=" + jQuery("#countries").val() + "&ke=" + jQuery("#keywords").val() + "&" + service_param + "&advanced-search-btn=Search"
	}
	// alert( advanced_search_url );
	location.href = advanced_search_url;
	
	return false;
}

function initialize() {
	var myOptions = {
		zoom: 5,
		center: new google.maps.LatLng(37.09024, -95.712891),
		mapTypeControl: true,
		mapTypeControlOptions: { style: google.maps.MapTypeControlStyle.DROPDOWN_MENU },
		navigationControl: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}

	$map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);

	jQuery('.map-search-large-loading').hide();
	jQuery('#search-map-btn').removeAttr('disabled');   

	// Try HTML5 geolocation
	address_itlocation = jQuery.cookie("address_itlocation");
	search_map_init('search-map-form');
	
	if( address_itlocation == null ){
		// console.log( navigator.geolocation );
		if( navigator.geolocation ){    
			navigator.geolocation.getCurrentPosition(function( position ){
				// console.log( position );
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
								jQuery("#advance_my_location").html($str);
							}
							
							jQuery('#subscriber-itlocation-modal').modal('show');
							
							search_map_init('search-map-form');
							
							jQuery("#countries")[0].selectedIndex = 1;
							jQuery("#countries").select2({
								formatResult: countries_format,
								formatSelection: countries_format,
								placeholder: "Select Country",
								allowClear: true,
								escapeMarkup: function(m) {
									return m;
								}
							});
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
						alert("PERMISSION_DENIED");
						break;
					case error.POSITION_UNAVAILABLE:
						alert("POSITION_UNAVAILABLE");
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
		jQuery("#advance_my_location").html($str);
		first_date_visit = jQuery.cookie("first_visit_date");
		var today = new Date();
		var time_diff = today.getTime() - parseFloat(first_date_visit);
		time_diff = parseInt(time_diff / (1000 * 60 * 60 *24));
		if(time_diff > 3 || time_diff == 3){
			jQuery.removeCookie("not_now_subscriber_itlocation");
			jQuery.cookie("first_visit_date", today.getTime(), {expires: 30 });
			jQuery('#subscriber-itlocation-modal').modal('show');   
		}
	}  
}
google.maps.event.addDomListener(window, 'load', initialize);