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
      src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places">
    </script>
    <script type="text/javascript">
	var directionsDisplay;
	var directionsService = new google.maps.DirectionsService();
	var map;
	
	//set coordinates
	var oulu = new google.maps.LatLng(65.0123600, 25.4681600);
	var orig = new google.maps.LatLng(65.059248, 25.466337);
	var dest = new google.maps.LatLng(65.010786, 25.469942);
	
	//set origin/destination as draggable
	var rendererOptions = {
	  draggable: true
	};

	
	function initialize() {
		//initialize google map
		directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
		var mapOptions = {
			//these mapoptions are the same than in oulunliikenne.fi service
			center: oulu,
			zoom: 13
		};
		map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
		directionsDisplay.setMap(map);
	
		//google places search, there are different types like stores, restaurants etc.
		//creates markers for each places found inside radius
		/*there is an example of a google places search box in 
		https://developers.google.com/maps/documentation/javascript/examples/places-searchbox
		which could be useful
		*/
		 var request = {
			location: oulu,
			radius: 500,
			types: ['store']
		  };
		  infowindow = new google.maps.InfoWindow();
		  var service = new google.maps.places.PlacesService(map);
		  service.nearbySearch(request, callback);

		
	//Commented section is to create a marker
	/*	var contentString = '<div id="content">'+
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
		});		*/
	}
	
	//callback function used for google places
	function callback(results, status) {
	  if (status == google.maps.places.PlacesServiceStatus.OK) {
		for (var i = 0; i < results.length; i++) {
		  createMarker(results[i]);
		}
	  }
	}

	//create marker for google places
	function createMarker(place) {
	  var placeLoc = place.geometry.location;
	  var marker = new google.maps.Marker({
		map: map,
		position: place.geometry.location
	  });

	  google.maps.event.addListener(marker, 'click', function() {
		infowindow.setContent(place.name);
		infowindow.open(map, this);
	  });
	}

	
	
	//calculates route from origin to destination
	function calcRoute() {
	  var selectedMode = document.getElementById("mode").value;
	  var request = {
		  //set origin
		  origin: orig,
		  //set destination
		  destination: dest,
		  // Note that Javascript allows us to access the constant
		  // using square brackets and a string value as its
		  // "property."
		  
		  //travelmode (bike/walk/car/transit)
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

	<div id="panel">
	<strong>Mode of Travel: </strong>
	<select id="mode" onchange="calcRoute();">
	  <option value="DRIVING">Driving</option>
	  <option value="WALKING">Walking</option>
	  <option value="BICYCLING">Bicycling</option>
	  <option value="TRANSIT">Transit</option>
	</select>
	</div>
	<div id="map-canvas"></div>
  </body>
</html>