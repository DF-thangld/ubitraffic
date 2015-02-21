<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=7" />
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <meta name="description" content=" " />
    <title>Google Php TEST</title>

    <style type="text/css">
		html, body, #map-canvas { 
			height: 100%; 
			margin: 0px; 
			padding: 0px 
		}
		
		#panel {
			position: absolute;
			top: 5px;
			left: 50%;
			margin-left: -180px;
			z-index: 5;
			background-color: #fff;
			padding: 5px;
			border: 1px solid #999;
		}
	</style>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=">
    </script>
    <script type="text/javascript">
	var directionsDisplay;
	var directionsService = new google.maps.DirectionsService();
	var map;
	var oulu = new google.maps.LatLng(65.0123600, 25.4681600);
	var orig = new google.maps.LatLng(65.059248, 25.466337);
	var dest = new google.maps.LatLng(65.010786, 25.469942);
	function initialize() {
		directionsDisplay = new google.maps.DirectionsRenderer();
		var mapOptions = {
		  center: oulu,
		  zoom: 13
		};
		map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
		directionsDisplay.setMap(map);
	
		var contentString = '<div id="content">'+
		  '<div id="siteNotice">'+
		  '</div>'+
		  '<h1 id="firstHeading" class="firstHeading">Hospital</h1>'+
		  '<div id="bodyContent">'+
		  '<p>Hello, this is Oulu Hospital</p>'+
			'<p>Visit <a href="http://www.ppshp.fi/oulun_yliopistollinen_sairaala"></a></p>'+
		  '</div>'+
		  '</div>';

		var infowindow = new google.maps.InfoWindow({
			content: contentString
		});

			
			
		var myLatlng = new google.maps.LatLng(65.0075,25.518611);

		var marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			title:"Hello World!"
		});
		google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
		});		
	}
	function calcRoute() {
	  var selectedMode = document.getElementById("mode").value;
	  var request = {
		  origin: orig,
		  destination: dest,
		  // Note that Javascript allows us to access the constant
		  // using square brackets and a string value as its
		  // "property."
		  travelMode: google.maps.TravelMode[selectedMode]
	  };
	  directionsService.route(request, function(response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
		  directionsDisplay.setDirections(response);
		}
	  });
	}	
	
	google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </head>
  <body>
<div id="map-canvas"></div>
<div id="panel">
<strong>Mode of Travel: </strong>
<select id="mode" onchange="calcRoute();">
  <option value="DRIVING">Driving</option>
  <option value="WALKING">Walking</option>
  <option value="BICYCLING">Bicycling</option>
  <option value="TRANSIT">Transit</option>
</select>
</div>

  </body>
</html>