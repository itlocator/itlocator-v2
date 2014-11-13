<!DOCTYPE html>
<html>
<head>
    <title>Geolocation</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
		#map-canvas{
			width:500px;
			height:500px;
		}
    </style>
    <script src="http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true"></script>
    <script>
		var map;
		function initialize() {
			var mapOptions = {
				zoom: 6
			};
			map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			var pos = new google.maps.LatLng('37.09024', '-95.712891');
			var infowindow = new google.maps.InfoWindow({
				map: map,
				position: pos,
				content: 'Location found using HTML5.'
			});
			map.setCenter(pos);
			
			var current_address = "";
		}
		google.maps.event.addDomListener(window, 'load', initialize);
	</script>
</head>
<body>
<div id="map-canvas"></div>
</body>
</html>