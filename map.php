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
		
		#download {
			position: absolute;
			top: 5px;
			left: 5px;
			width: 80px;
			height: 40px;
			background-color: #fff;
		}
	</style>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places">
    </script>
    <script type="text/javascript">
	var directionsDisplay;
	var directionsService = new google.maps.DirectionsService();
	var map;
	
	var service;

	
	//set coordinates
	var oulu = new google.maps.LatLng(65.0123600, 25.4681600);
	var orig = new google.maps.LatLng(65.059248, 25.466337);
	var dest = new google.maps.LatLng(65.010786, 25.469942);
	
	var m_zoom = 13;
	
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
			zoom: m_zoom
		};
		map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
		directionsDisplay.setMap(map);
	
			
		
		/*
		
		---------------------------------------------------
		Google Places search starts
		---------------------------------------------------
		Different types for places: (maybe give 3-5 options and the user can choose from those)
			-restaurant
			-cafe
			-gym
			-movie_theater
			-hospital
			-art_gallery
			-travel_agency
			-grocery_or_supermarket
			-shoe_store
			-pharmacy
			-home_goods_store
			-hair_care
			-electronics_store
		*/
		
		 var request = {
			//the location from which the search is done (this could be changed to ubihotspot's location later)
			location: oulu,
			
			//radius for the places to show up
			radius: 500,
			
			//set the type of places to search
			types: ['restaurant']
		  };
		  infowindow = new google.maps.InfoWindow();
		  service = new google.maps.places.PlacesService(map);
		  service.nearbySearch(request, callback);	
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
	  
	  //create marker image
	  var image = {
        url: place.icon,
        scaledSize: new google.maps.Size(25, 25)
		};
	  
	  //create actual marker
	  var marker = new google.maps.Marker({
		map: map,
		position: place.geometry.location,
		icon: image,
		title: place.name
	  });

	
	   var request = { reference: place.reference };

		//get details for place
		service.getDetails(request, function(details, status) {
		
		//check if there is details for a place
		if(status == 'OK')
		{
		  google.maps.event.addListener(marker, 'click', function() {
		  
			//get the opening data for the place
			if(details.opening_hours != null)
			{
				//create a list containing opening and closing hours for the week
				var openingHours = '<ul id="openingHours">' +
					'<li class="listitem">Monday: '+(details.opening_hours.periods[1].open.time/100).toFixed(2)+' - '+(details.opening_hours.periods[1].close.time/100).toFixed(2)+'</li>' +
					'<li class="listitem">Tuesday: '+(details.opening_hours.periods[2].open.time/100).toFixed(2)+' - '+(details.opening_hours.periods[2].close.time/100).toFixed(2)+'</li>' +
					'<li class="listitem">Wednesday: '+(details.opening_hours.periods[3].open.time/100).toFixed(2)+' - '+(details.opening_hours.periods[3].close.time/100).toFixed(2)+'</li>' +
					'<li class="listitem">Thursday: '+(details.opening_hours.periods[4].open.time/100).toFixed(2)+' - '+(details.opening_hours.periods[4].close.time/100).toFixed(2)+'</li>' +
					'<li class="listitem">Friday: '+(details.opening_hours.periods[5].open.time/100).toFixed(2)+' - '+(details.opening_hours.periods[5].close.time/100).toFixed(2)+'</li>' +
					'<li class="listitem">Saturday: '+(details.opening_hours.periods[6].open.time/100).toFixed(2)+' - '+(details.opening_hours.periods[6].close.time/100).toFixed(2)+'</li>' +
					'<li class="listitem">Sunday: '+(details.opening_hours.periods[0].open.time/100).toFixed(2)+' - '+(details.opening_hours.periods[0].close.time/100).toFixed(2)+'</li>' +
					'</ul>';
			}
			else
			{
			 var openingHours = "Unavailable";
			}				
			
			//create the text info for place
			var contentString = '<div id="content">'+
			  '<div id="siteNotice">'+
			  '</div>'+
			  '<h2 id="markerHeading" class="markerHeading">'+details.name+'</h2>'+
			  '<div id="bodyContent">'+		  
			  '<p>Address: '+details.formatted_address+
			  '<br>Phone: '+details.formatted_phone_number+
			  '<br>Website: '+details.website+
			  '</p>' +
			  '<p>Opening hours: <br>'+openingHours+'</p>' +
			  '<p>Average rating: '+details.rating+'</p>' +
			  '</div>'+
			  '</div>';
			  
			//set the information to the marker
			infowindow.setContent(contentString);
			infowindow.open(map, this);
			});
		}
		
		//else just show place's name
		else
		{
			google.maps.event.addListener(marker, 'click', function() {
			infowindow.setContent(place.name);
			infowindow.open(map, this);
			});
		}
	  });	  
	}

	/*
	---------------------------------------------------
	Google Places search ends
	---------------------------------------------------	
	*/
	
	
	
	
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
		  showSteps(response);
		}
	  });
	}	
	
	//show the steps for route
	function showSteps(directionResult)
	{
		var myRoute = directionResult.routes[0].legs[0];
		var totDuration =  Math.round(myRoute.duration.value/60);
		var totDistance =  (myRoute.distance.value/1000).toFixed(2);
		var steps = document.getElementById("panel");
		if(!document.getElementById("steps"))
		{
			var paragraph = document.createElement("p");
			paragraph.id = "steps";
			steps.appendChild(paragraph);
			paragraph.innerHTML="total duration: "+totDuration+"min - total distance: "+totDistance+"km";
		}
		else
		{
			document.getElementById("steps").innerHTML = "total duration: "+totDuration+"min - total distance: "+totDistance+"km";
		}
		
		for (var i = 0; i < myRoute.steps.length; i++) {
			var distance = myRoute.steps[i].distance;
			var instruct = myRoute.steps[i].instructions;
			
			document.getElementById("steps").innerHTML += "<br>"+(i+1)+": " + instruct + " - "+distance.value+"m";
			
		}
		
		
			
		
	}
	
	//just a test function to get map and hopefully save it as pdf
	//TODO::: Path (as well as all path information), Markers (and the data that is currently chosen)
	function createPDF()
	{
		var image = "https://maps.googleapis.com/maps/api/staticmap?center="+oulu+"&zoom="+m_zoom+"&size=400x600";
		
		var div = document.createElement("div");
		div.style.position = "absolute";
		div.style.left = "5px";
		div.style.top = "80px";
		div.style.width = "100px";
		div.style.height = "100px";
		div.style.background = "white";
		div.style.color = "black";
		div.innerHTML = "Hello<br>";
		var aa = document.createElement("a");
		aa.href = image;
		aa.innerHTML = "Image of map";
		div.appendChild(aa);
		

		document.getElementById('map-canvas').appendChild(div);
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
	<button id="download" onclick="createPDF()">Create PDF</button>
  </body>
</html>