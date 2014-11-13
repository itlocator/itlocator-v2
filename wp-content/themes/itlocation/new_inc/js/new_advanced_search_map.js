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
				$pagenum = jQuery(".page-numbers.current")[0].innerHTML * 1 - 1;
			} else if( jQuery(this).hasClass('next') ){
				$pagenum = jQuery(".page-numbers.current")[0].innerHTML * 1 + 1;
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
		} else {
			$url = location.href;
			$start = $url.indexOf("?");
			
			$pagenum = jQuery(this).text();
			if( jQuery(this).hasClass('prev') ){
				$pagenum = jQuery(".page-numbers.current")[0].innerHTML * 1 - 1;
			} else if( jQuery(this).hasClass('next') ){
				$pagenum = jQuery(".page-numbers.current")[0].innerHTML * 1 + 1;
			}
			
			if( jQuery( "#lo" ).val() != '' ){
				var geocoder = new google.maps.Geocoder();
			
				geocoder.geocode({
					'address': jQuery( "#lo" ).val()
				}, function (results, status) {
					if( !results[0].geometry ){
						return;
					}
					
					var suffix_param = '';
					
					if (status == google.maps.GeocoderStatus.OK) {
				 	var latitude = results[0].geometry.location.lat();
					var longitude = results[0].geometry.location.lng();
					  } 
					
					if( results[0].geometry.viewport ){
						var pointNorthEast = results[0].geometry.viewport.getNorthEast(),
							pointSouthWest = results[0].geometry.viewport.getSouthWest();
						
						suffix_param = "&x1=" + pointNorthEast.k + "&y1=" + pointNorthEast.B + "&x2=" + pointSouthWest.k + "&y2=" + pointSouthWest.B+ "&lat=" + latitude +"&long=" + longitude;
					} else {							
						// console.log(results[0].geometry.location);
					}
					
					//showing data
					$url_paramlist = 'page=' +$pagenum + '&action=new-advanced-search-map-itlocation-html&' + $url.substring($start + 1, $url.length)+suffix_param;
					
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
				});
			} else {
			
			var urlstring = $url.substring($start + 1, $url.length);
			var urlpart = urlstring.split("&");
			var countrypart=urlpart[3].split("=");
			
			var geocoder = new google.maps.Geocoder();
				geocoder.geocode({
					'address': countrypart[1]
				}, function (results, status) {
					if( !results[0].geometry ){
						return;
					}
								
					var suffix_param = '';
					
					if (status == google.maps.GeocoderStatus.OK) {
				 	var latitude = results[0].geometry.location.lat();
					var longitude = results[0].geometry.location.lng();
					  } 
					
					if( results[0].geometry.viewport ){
						var pointNorthEast = results[0].geometry.viewport.getNorthEast(),
							pointSouthWest = results[0].geometry.viewport.getSouthWest();
						
						suffix_param = "&x1=" + pointNorthEast.k + "&y1=" + pointNorthEast.B + "&x2=" + pointSouthWest.k + "&y2=" + pointSouthWest.B+"&lat=" + latitude +"&long=" + longitude;
						}
				//showing data
				
				$url_paramlist = 'page=' +$pagenum + '&action=new-advanced-search-map-itlocation-html&' + $url.substring($start + 1, $url.length)+suffix_param;
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
			});
			}
		}
		return false;
	});
}

jQuery(document).ready(function(){
	if( jQuery("#lo").val() == '' ){
		jQuery("#co").removeAttr("disabled");
	} else {
		jQuery("#co").attr("disabled", "disabled");
	}
	
	jQuery("#co").select2({
		formatResult: countries_format,
		formatSelection: countries_format,
		placeholder: "Select Country",
		allowClear: true,
		escapeMarkup: function(m) {
			return m;
		}
	});
	
	function allLocationSearchFalse(){		
		if( jQuery("#roundmylocation")[0].checked ) {
			return true;
		}
		
		if( jQuery("#specificlocation")[0].checked ) {
			return true;
		}
		
		if( jQuery("#bycountry")[0].checked ) {
			return true;
		}
		
		return false;
	}
	
	jQuery("#roundmylocation").click(function(){
		if( this.checked ) {
			jQuery("#lo").attr("disabled", "disabled");
			jQuery("#co").attr("disabled", "disabled");
			
			jQuery("#specificlocation")[0].checked = false;
			jQuery("#bycountry")[0].checked = false;
		}else if( !allLocationSearchFalse() && !this.checked ){
			this.checked = true;
		}
		
		jQuery("#co").select2({
			formatResult: countries_format,
			formatSelection: countries_format,
			placeholder: "Select Country",
			allowClear: true,
			escapeMarkup: function(m) {
				return m;
			}
		});
	});
	
	jQuery("#specificlocation").click(function(){
		if( this.checked ){
			jQuery("#co").attr("disabled", "disabled");
			jQuery("#lo").removeAttr("disabled");
			
			jQuery("#roundmylocation")[0].checked = false;
			jQuery("#bycountry")[0].checked = false;
		}else if( !allLocationSearchFalse() && !this.checked ){
			this.checked = true;
		}
		
		jQuery("#co").select2({
			formatResult: countries_format,
			formatSelection: countries_format,
			placeholder: "Select Country",
			allowClear: true,
			escapeMarkup: function(m) {
				return m;
			}
		});
	});
	
	jQuery("#bycountry").click(function(){
		if( this.checked ){
			jQuery("#lo").attr("disabled", "disabled");
			jQuery("#co").removeAttr("disabled");
			
			jQuery("#roundmylocation")[0].checked = false;
			jQuery("#specificlocation")[0].checked = false;
		}else if( !allLocationSearchFalse() && !this.checked ){
			this.checked = true;
		}
		
		jQuery("#co").select2({
			formatResult: countries_format,
			formatSelection: countries_format,
			placeholder: "Select Country",
			allowClear: true,
			escapeMarkup: function(m) {
				return m;
			}
		});
	});
	
	// jQuery("#advance-search-form").submit(function(){
		// return false;
	// });
});

function getAllAroundList( lat, lng ){
	$url = location.href;
	$start = $url.indexOf("?");
if($url=="http://www.itlocator.com/my-profile/")
{
	return false;
}

			
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
			
			// center = jQuery.cookie("address_pos");
    		// var res = center.split(',');
								
			// var center = new google.maps.LatLng( res[0], res[1] );
			// $map.setZoom( 10 );
			// $map.setCenter( center );
			
			if( $markerBounds != null ) {
				$map.setCenter( $markerBounds.getCenter() );
				if( response.length > 1 ){
					$map.fitBounds( $markerBounds );
				} else {
					$map.setZoom(9);
				}
			}
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
	
	var autocomplete = new google.maps.places.Autocomplete( document.getElementById('lo') );

if(document.getElementById('add_loc2')!=null)
var autocomplete1 = new google.maps.places.Autocomplete( document.getElementById('add_loc2') );
	  autocomplete2 = new google.maps.places.Autocomplete(document.getElementById('address2'), { types: [ 'geocode' ] });
google.maps.event.addListener(autocomplete2, 'place_changed', function() {
  fillInAddress();
});
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
/*	 if(jQuery.cookie("address_itlocation")==null) { 
			var searchvalue ='none';
           if (jQuery('#roundmylocation').is(":checked"))
           {
			searchvalue='US';
           }
		   else if (jQuery('#specificlocation').is(":checked"))
           {
			searchvalue='specificlocation';
           }
		    else if (jQuery('#bycountry').is(":checked"))
           {
			searchvalue='bycountry';
           }
		  
		jQuery.ajax({

			type : 'post',

			dataType : 'json',

			url : admin_ajax.url,

			data: {

				'action' : 'new-search-map-itlocation',

				'security' : jQuery('#new-search-map-security').val(),

				'countries' : searchvalue,

				'services' : '',

				'keywords' : ''

			},

			success: function(response) {

				jQuery('.map-search-large-loading').hide();

			jQuery("#company_search_list").html("<center><b>No Results found</b></center>");

				$userdata = response;
				make_marker();

				if( $markerBounds != null ) {

					$map.setCenter( $markerBounds.getCenter() );

					$map.fitBounds( $markerBounds );
				}

			}

		});

	}*/
	if( getParameterValue('roundmylocation') == 'true' || $detect_no_parameter ) {		
		jQuery("#co").select2({
			formatResult: countries_format,
			formatSelection: countries_format,
			placeholder: "Select Country",
			allowClear: true,
			escapeMarkup: function(m) {
				return m;
			}
		});
			
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
									jQuery("#advance_my_location").html($str);
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
			center = jQuery.cookie("address_pos");
			var res = center.split(',');

			getAllAroundList(res[0], res[1]);
		}
	} 
	
		else if( getParameterValue('searchbyaddress') == 'true' || $detect_no_parameter ) {
			
		jQuery("#co").attr("disabled", "disabled");
    	jQuery("#lo").removeAttr("disabled");
		jQuery("#specificlocation")[0].checked = true;
		jQuery("#roundmylocation")[0].checked = false;
		jQuery("#bycountry")[0].checked = false;
			
		var myaddress =	getParameterValue('byaddress');
		  myaddress = myaddress.replace("%20", " ");
		if( myaddress !="" ){
		myaddress =	myaddress.split('%20').join(' ');
			jQuery("#advance_my_location").html("");
			jQuery("#lo").val(myaddress);
			}
		var geocoder = new google.maps.Geocoder();
			
			geocoder.geocode({
				'address': myaddress
			}, function (results, status) {
				 console.log( results );
				 //return;
				if( !results[0].geometry ){
					return;
				}
				
				var suffix_param = '';
				if (status == google.maps.GeocoderStatus.OK) {
				 	var latitude = results[0].geometry.location.lat();
					var longitude = results[0].geometry.location.lng();
				 } 
				if( results[0].geometry.viewport ){
					var pointNorthEast = results[0].geometry.viewport.getNorthEast(),
						pointSouthWest = results[0].geometry.viewport.getSouthWest();
				        console.log( pointNorthEast );
                   
                   //Before there is using "A instead of B"
					suffix_param = "&x1=" + pointNorthEast.k + "&y1=" + pointNorthEast.B + "&x2=" + pointSouthWest.k + "&y2=" + pointSouthWest.B+ "&lat=" + latitude +"&long=" + longitude;
				} else {							
					// console.log(results[0].geometry.location);
				}
				
				$url = location.href;
				$start = $url.indexOf("?");
				
				//showing data
				$url_paramlist = 'page=1&action=new-advanced-search-map-itlocation-html&' + $url.substring($start + 1, $url.length) + suffix_param;
				
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
				$url_paramlist = 'action=new-advanced-search-map-itlocation&' + $url.substring($start + 1, $url.length) + suffix_param;
				
				jQuery.ajax({
					type : 'post',
					dataType : 'json',
					url : admin_ajax.url,
					data: $url_paramlist,
					success: function(response) {
						// console.log(response); return;
						
						jQuery('.map-search-large-loading').hide();
						
						if( isEmptyObject( response ) ){
							$map.fitBounds( results[0].geometry.viewport );
						} else {
							$userdata = response;
							
							make_marker();
							
							if( results[0].geometry.viewport ){								
								$map.setCenter( $markerBounds.getCenter() );
						
								if( response.length > 1 ){
									$map.fitBounds( $markerBounds );
								} else {
									$map.setZoom(9);
								}
							} else {
								circle = new google.maps.Circle({
									strokeColor: '#FF0000',
									strokeOpacity: 0.8,
									strokeWeight: 2,
									fillColor: '#FF0000',
									fillOpacity: 0.35,
									map: $map,
									center: place.geometry.location,
									radius: 5000
								});
						
								$map.setCenter(results[0].geometry.location);
							}
						}
					}
				});
			});	
			}
	
	else {		
		if( jQuery( "#lo" ).val() != '' ){
			var geocoder = new google.maps.Geocoder();
			
			geocoder.geocode({
				'address': jQuery( "#lo" ).val()
			}, function (results, status) {
				 console.log( results );
				 //return;
				if( !results[0].geometry ){
					return;
				}
				
				var suffix_param = '';
				
                      
			if (status == google.maps.GeocoderStatus.OK) {
			    var latitude = results[0].geometry.location.lat();
			    var longitude = results[0].geometry.location.lng();
			  } 
				if( results[0].geometry.viewport ){
					var pointNorthEast = results[0].geometry.viewport.getNorthEast(),
						pointSouthWest = results[0].geometry.viewport.getSouthWest();
				        console.log( pointNorthEast );
                   
                   //Before there is using "A instead of B"
					suffix_param = "&x1=" + pointNorthEast.k + "&y1=" + pointNorthEast.B + "&x2=" + pointSouthWest.k + "&y2=" + pointSouthWest.B +"&lat=" + latitude +"&long=" + longitude;
				} else {							
					// console.log(results[0].geometry.location);
				}
				
				$url = location.href;
				$start = $url.indexOf("?");
				
				//showing data
				$url_paramlist = 'page=1&action=new-advanced-search-map-itlocation-html&' + $url.substring($start + 1, $url.length) + suffix_param;
				
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
				$url_paramlist = 'action=new-advanced-search-map-itlocation&' + $url.substring($start + 1, $url.length) + suffix_param;
				
				jQuery.ajax({
					type : 'post',
					dataType : 'json',
					url : admin_ajax.url,
					data: $url_paramlist,
					success: function(response) {
						// console.log(response); return;
						
						jQuery('.map-search-large-loading').hide();
						
						if( isEmptyObject( response ) ){
							$map.fitBounds( results[0].geometry.viewport );
						} else {
							$userdata = response;
							
							make_marker();
							
							if( results[0].geometry.viewport ){								
								$map.setCenter( $markerBounds.getCenter() );
						
								if( response.length > 1 ){
									$map.fitBounds( $markerBounds );
								} else {
									$map.setZoom(9);
								}
							} else {
								circle = new google.maps.Circle({
									strokeColor: '#FF0000',
									strokeOpacity: 0.8,
									strokeWeight: 2,
									fillColor: '#FF0000',
									fillOpacity: 0.35,
									map: $map,
									center: place.geometry.location,
									radius: 5000
								});
						
								$map.setCenter(results[0].geometry.location);
							}
						}
					}
				});
			});
		} 
		
	
		else {
		var geocoder = new google.maps.Geocoder();
	var SearchString = location.href.substring(1);
    var VariableArray = SearchString.split('&');
			var countrycode=VariableArray[2];
		countrycode =	countrycode.split('=');
	
		var mycountry="";
		if(countrycode[1]="IN")
		{
			mycountry="India";
		}
		else if(countrycode[1]="US")
		{
			mycountry="United States";
		}
		else if(countrycode[1]="AR")
		{
			mycountry="Argentina";
		}
		else if(countrycode[1]="AU")
		{
			mycountry="Australia";
		}
		else if(countrycode[1]="BD")
		{
			mycountry="Bangladesh";
		}
		else if(countrycode[1]="BR")
		{
			mycountry="Brazil";
		}
		else if(countrycode[1]="CA")
		{
			mycountry="Canada";
		}
		else if(countrycode[1]="CN")
		{
			mycountry="China";
		}
		else if(countrycode[1]="CO")
		{
			mycountry="Colombia";
		}
	        else if(countrycode[1]="CZ")
		{
			mycountry="Czech Republic";
		}
		else if(countrycode[1]="EG")
		{
			mycountry="Egypt";
		}
		else if(countrycode[1]="FI")
		{
			mycountry="Finland";
		}
		else if(countrycode[1]="FR")
		{
			mycountry="France";
		}
		else if(countrycode[1]="DE")
		{
			mycountry="Germany";
		}  
		else if(countrycode[1]="ID")
		{
			mycountry="Indonesia";
		} 
		  
		else if(countrycode[1]="IL")
		{
			mycountry="Israel";
		}
		  
		else if(countrycode[1]="ID")
		{
			mycountry="Indonesia";
		}
		  
		else if(countrycode[1]="IT")
		{
			mycountry="Italy";
		}
		  
		else if(countrycode[1]="MX")
		{
			mycountry="Mexico";
		}
		  
		else if(countrycode[1]="NL")
		{
			mycountry="Netherlands";
		}
		  
		else if(countrycode[1]="RO")
		{
			mycountry="Romania";
		}
		else if(countrycode[1]="RU")
		{
			mycountry="Russia (Russian Fed.)";
		}
		else if(countrycode[1]="SA")
		{
			mycountry="Saudi Arabia";
		}
		else if(countrycode[1]="ZA")
		{
			mycountry="South Africa";
		}
		else if(countrycode[1]="ES")
		{
			mycountry="Spain";
		}
		else if(countrycode[1]="SE")
		{
			mycountry="Sweden";
		}
		else if(countrycode[1]="TR")
		{
			mycountry="Turkey";
		}
		else if(countrycode[1]="UK")
		{
			mycountry="United Kingdom";
		}
	       
			geocoder.geocode({
				'address': mycountry
			}, function (results, status) {
				 console.log( results );
				 //return;
				if( !results[0].geometry ){
					return;
				}
				
				if (status == google.maps.GeocoderStatus.OK) {
		 	var latitude = results[0].geometry.location.lat();
			var longitude = results[0].geometry.location.lng();
			  }
			$url = location.href;
			$start = $url.indexOf("?");
			var locationData = "&lat=" + latitude +"&long=" + longitude;	
			//showing data
			$url_paramlist = 'page=1&action=new-advanced-search-map-itlocation-html&' + $url.substring($start + 1, $url.length) +locationData;
			
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
			$url_paramlist = 'action=new-advanced-search-map-itlocation&' + $url.substring($start + 1, $url.length) + locationData;
			
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
					
					if( $markerBounds != null ) {
						$map.setCenter( $markerBounds.getCenter() );
						if( response.length > 1 ){
							$map.fitBounds( $markerBounds );
						} else {
							$map.setZoom( 9 );
						}
					}
				}
			});
                     });
		}
	}
	var pathname = window.location.pathname; 
//var res = pathname.split("/"); 
if(pathname=="/advanced-search/")
{
		 if(jQuery.cookie("address_itlocation")==null) { 
			var searchvalue ='none';
           if (jQuery('#roundmylocation').is(":checked"))
           {
			searchvalue='US';
           }
		   else if (jQuery('#specificlocation').is(":checked"))
           {
			searchvalue='specificlocation';
           }
		    else if (jQuery('#bycountry').is(":checked"))
           {
			searchvalue='bycountry';
           }
		  
		jQuery.ajax({

			type : 'post',

			dataType : 'json',

			url : admin_ajax.url,

			data: {

				'action' : 'new-search-map-itlocation',

				'security' : jQuery('#new-search-map-security').val(),

				'countries' : searchvalue,

				'services' : '',

				'keywords' : ''

			},

			success: function(response) {

				jQuery('.map-search-large-loading').hide();

			jQuery("#company_search_list").html("<center><b>No Results found</b></center>");

				$userdata = response;
				make_marker();

				if( $markerBounds != null ) {

					$map.setCenter( $markerBounds.getCenter() );

					$map.fitBounds( $markerBounds );
				}

			}

		});

	}
	}
}
function getOption(option)
{
	option= option|| null;
       var newUrl= location.href += "&type=" +option;
       var newUrl = document.URL;
       newUrl= newUrl += "&type=" +option;
       newUrl = newUrl.substring(newUrl.lastIndexOf('?') + 1);
       newUrl = newUrl.split('&');
       var url1 = newUrl[0].split('='); 
       var url2 = newUrl[3].split('='); 
       var requesttype=url1[0];
       var optionValue =url2[1];
//alert( requesttype);
       return optionValue ;
       
}
function fillInAddress() {
  var place = autocomplete.getPlace();
  }
google.maps.event.addDomListener(window, 'load', initialize);


/*
google.maps.event.addListener(autocomplete, 'place_changed', function(){
	alert(1);
	var place = autocomplete.getPlace();
	if( !place.geometry ){
		return;
	}

	if( place.geometry.viewport ){
		$map.fitBounds( place.geometry.viewport );
		rectangle = new google.maps.Rectangle({
			bounds: results[0].geometry.viewport,
			editable: true,
			draggable: true,
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: '#FF0000',
			fillOpacity: 0.35,
		});

		rectangle.setMap( $map );
	} else {
		circle = new google.maps.Circle({
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: '#FF0000',
			fillOpacity: 0.35,
			map: $map,
			center: place.geometry.location,
			radius: 5000
		});
					
		$map.setCenter(place.geometry.location);
	}
});
*/