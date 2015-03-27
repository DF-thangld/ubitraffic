var point_of_interest_types = [	"restaurant", "cafe", "movie_theater", "art_gallery", 
								"gym", "hair_care", "travel_agency", "pharmacy", 
								"hospital", "grocery_or_supermarket", "shoe_store", "home_goods_store"];

var points_of_interest = [];
points_of_interest["restaurant"] = [];
points_of_interest["cafe"] = [];
points_of_interest["movie_theater"] = [];
points_of_interest["art_gallery"] = [];
points_of_interest["gym"] = [];
points_of_interest["hair_care"] = [];
points_of_interest["travel_agency"] = [];
points_of_interest["pharmacy"] = [];
points_of_interest["hospital"] = [];
points_of_interest["grocery_or_supermarket"] = [];
points_of_interest["shoe_store"] = [];
points_of_interest["home_goods_store"] = [];

var inforWindowList = [];
var markerList = [];

function findMarkerByName(markers_array, marker_name)
{
	for (var i = 0; i < markers_array.length; i++) 
	{
		if (markers_array[i].name === marker_name) 
		{
			return markers_array[i];
		}
	}
	return null;
}

//create marker for google places
function createPointOfInterestMarker(place, point_of_interest_type)
{
	
	var placeContentString = "";
	var request = { reference: place.reference };
	
	//get details for place
	point_of_interest_service.getDetails(request, function(details, status) 
	{
		
		if(status == google.maps.places.PlacesServiceStatus.OK) // got information for the place
		{
			//window.alert(2);
			var openingHours = "";
			if(typeof details.opening_hours !== 'undefined')
			{
				//create a list containing opening and closing hours for the week
				if (typeof details.opening_hours.open_now!=='undefined')
				{
					if (details.opening_hours.open_now === true) 
						openingHours = "<p>Status: Opening</p>";
					else if (details.opening_hours.open_now === false) 
						openingHours = "<p>Status: Closing</p>";
				}
				if (typeof details.opening_hours.weekday_text !== 'undefined')
					openingHours = '<p>Opening hours:<br/><ul id="openingHours">' +
						'<li class="listitem">' + details.opening_hours.weekday_text[0] + '</li>' +
						'<li class="listitem">' + details.opening_hours.weekday_text[1] + '</li>' +
						'<li class="listitem">' + details.opening_hours.weekday_text[2] + '</li>' +
						'<li class="listitem">' + details.opening_hours.weekday_text[3] + '</li>' +
						'<li class="listitem">' + details.opening_hours.weekday_text[4] + '</li>' +
						'<li class="listitem">' + details.opening_hours.weekday_text[5] + '</li>' +
						'<li class="listitem">' + details.opening_hours.weekday_text[6] + '</li>' +
						'</ul></p>';
			}
			
			var ratingText = "";
			if (typeof details.rating !== 'undefined')
			{
				ratingText = "<p>Average rating: " + details.rating;
				if (typeof details.user_ratings_total !== 'undefined') 
					ratingText += " (" + details.rating + " voted)";
				ratingText += "</p>";
			}
			
			var website = "";
			if (typeof details.website !== 'undefined')
				ratingText = "<p>Website: " + details.website+"</p>";
			
			placeContentString = '<div id="content">'+
			  '<h2 id="markerHeading" class="markerHeading">Details: '+details.name+'</h2>'+
			  '<div id="bodyContent">'+		  
			  '<p>Address: '+details.formatted_address+'</p>'+
			  '<p>Phone: '+details.formatted_phone_number+'</p>'+
			  website +
			  openingHours +
			  ratingText +
			  '</div></div>';
			//console.log("Detail: " + details.name + " - " + details.formatted_address);
			
			//create marker image
			var image = {
				url: "images/point_of_interest/" + point_of_interest_type + "_icon.png",
				scaledSize: new google.maps.Size(25, 25)
			};
			  
			//create actual marker
			var marker = new google.maps.Marker({
				map: map,
				position: place.geometry.location,
				icon: image,
				title: place.name
			});
			markerList.push(marker);
			
			var infowindow = new google.maps.InfoWindow({
				content: placeContentString
			});
			inforWindowList.push(infowindow);
			
			google.maps.event.addListener(marker, 'click', function()
			{
				for (var i = 0; i < inforWindowList.length; i++) {
					inforWindowList[i].close();
				}
				infowindow.open(map,marker);
			});
			points_of_interest[point_of_interest_type].push(marker);
			
			
		}
		else // cannot get information for the place
		{
			var openingHours = "";
			if(typeof place.opening_hours !== 'undefined')
			{
				//create a list containing opening and closing hours for the week
				if (typeof place.opening_hours.open_now!=='undefined')
				{
					if (place.opening_hours.open_now === true) 
						openingHours = "<p>Status: Opening</p>";
					else if (place.opening_hours.open_now === false) 
						openingHours = "<p>Status: Closing</p>";
				}
			}
			
			placeContentString = '<div id="content">'+
			  '<h2 id="markerHeading" class="markerHeading">Place: '+place.name+'</h2>'+
			  '<div id="bodyContent">'+		  
			  '<p>Address: '+place.vicinity+'</p>'+
			  openingHours +
			  '</div></div>';
			//console.log("Place: " + place.name + " - " + place.vicinity);
			//create marker image
			var image = {
				url: "images/point_of_interest/" + point_of_interest_type + "_icon.png",
				scaledSize: new google.maps.Size(25, 25)
			};
			  
			//create actual marker
			var marker = new google.maps.Marker({
				map: map,
				position: place.geometry.location,
				icon: image,
				title: place.name
			});
			markerList.push(marker);
			
			var infowindow = new google.maps.InfoWindow({
				content: placeContentString
			});
			inforWindowList.push(infowindow);
			
			google.maps.event.addListener(marker, 'click', function() {
				for (var i = 0; i < inforWindowList.length; i++) {
					inforWindowList[i].close();
				}
				infowindow.open(map,marker);
			});
			points_of_interest[point_of_interest_type].push(marker);
		}
	});
	
}

function changePointOfInterestType(point_of_interest_type, map)
{
	if (points_of_interest[point_of_interest_type].length === 0)
	{
	
		var request = {
			//the location from which the search is done (this could be changed to ubihotspot's location later)
			location: screen_point,
				
			//radius for the places to show up
			radius: 10000,
				
			//set the type of places to search
			types: [point_of_interest_type]
		};
		
		point_of_interest_service.nearbySearch(request, function(results, status)
		{
			if (status == google.maps.places.PlacesServiceStatus.OK)
			{
			
				for (var i = 0; i < results.length; i++) 
				{
					createPointOfInterestMarker(results[i], point_of_interest_type);
					
				}
			}
		});
	}
	else
	{
			
	}
	
	for (j=0; j<markerList.length; j++)
		markerList[j].setMap(null);
	
	for (j=0; j<points_of_interest[point_of_interest_type].length; j++)
		points_of_interest[point_of_interest_type][j].setMap(map);
	
	for (i = 0, len = point_of_interest_types.length; i < len; i++)
	{
		$("#"+point_of_interest_types[i]+"_information_button").attr("class", "button");
	}
	$("#"+point_of_interest_type+"_information_button").attr("class", "button chosen_mode");
}

function menu(map)
{
	// menu container definition
	var $container = $(document.createElement('DIV'));
	$container.attr("style", "position:absolute;");
	var $innerContainer = $(document.createElement('DIV'));
	$innerContainer.attr("style", "");
	
	//show menu button
	var $show_menu = $(document.createElement('DIV'));
	$show_menu.attr("id", "show_menu");
	$show_menu.attr("style", "position:absolute;margin-left:-50px;bottom:30px;float:left;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:73px;height:68px;");
	$show_menu.html("<img style='' src='images/open_button.png' />");
	google.maps.event.addDomListener($show_menu.get(0), 'click', function() {
		$('#show_menu').css('display', 'none');
		$('#main_menu').css('display', 'inline');
		main_menu_opening=true;
	});
	$innerContainer.append($show_menu);
	
	//main menu
	var $main_menu = $(document.createElement("DIV"));
	$main_menu.attr("id", "main_menu");
	$main_menu.attr("style", "display:none;position:absolute;margin-left:-50px;bottom:30px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:420px;height:68px;");
	
	//main menu - close button
	var $close_button = $(document.createElement("DIV"));
	$close_button.attr("id", "close_button");
	$close_button.attr("class", "button");
	$close_button.attr("style", "margin-left:3px;");
	$close_button.html("<img src='images/close_button.png' style='width:70px;height:67px;' />");
	google.maps.event.addDomListener($close_button.get(0), 'click', function() {
		reset();
	});
	$main_menu.append($close_button);
	
	//main menu - navigate button
	var $navigation_button = $(document.createElement("DIV"));
	$navigation_button.attr("id", "navigation_button");
	$navigation_button.attr("class", "button");
	$navigation_button.attr("style", "margin-left:3px;");
	$navigation_button.html("<center><img src='images/navigation_button.png' /><div>Navigation</div></center>");
	google.maps.event.addDomListener($navigation_button.get(0), 'click', function() {
		
		change_sub_menu('navigation_menu');
		
	});
	$main_menu.append($navigation_button);
	
	//main menu - bus timetable button
	var $bus_timetable_button = $(document.createElement("DIV"));
	$bus_timetable_button.attr("id", "bus_timetable_button");
	$bus_timetable_button.attr("class", "button");
	$bus_timetable_button.attr("style", "margin-left:3px;");
	$bus_timetable_button.html("<center><img src='images/bus_timetable_button.png' /><div>Timetable</div></center>");
	google.maps.event.addDomListener($bus_timetable_button.get(0), 'click', function() {
		// TODO
	});
	$main_menu.append($bus_timetable_button);
	
	//main menu - place button
	var $place_button = $(document.createElement("DIV"));
	$place_button.attr("id", "place_button");
	$place_button.attr("class", "button");
	$place_button.attr("style", "margin-left:3px;");
	$place_button.html("<center><img src='images/point_of_interest.png' style='' /><div>Points of interest</div></center>");
	google.maps.event.addDomListener($place_button.get(0), 'click', function() {
		change_sub_menu('point_of_interest');
	});
	$main_menu.append($place_button);
	
	//main menu - traffic congestion button
	var $traffic_congestion_button = $(document.createElement("DIV"));
	$traffic_congestion_button.attr("id", "traffic_congestion_button");
	$traffic_congestion_button.attr("class", "button");
	$traffic_congestion_button.attr("style", "margin-left:3px;");
	$traffic_congestion_button.html("<center><img src='images/traffic_congestion_button.png' /><div>Traffic Block</div></center>");
	google.maps.event.addDomListener($traffic_congestion_button.get(0), 'click', function() {
		change_sub_menu('traffic_congestion');
	});
	$main_menu.append($traffic_congestion_button);
	
	// insert main menu to map
	$innerContainer.append($main_menu);
	
	// point of interest menu
	var $point_of_interest_menu = $(document.createElement("DIV"));
	$point_of_interest_menu.attr("id", "point_of_interest");
	$point_of_interest_menu.attr("style", "display:none;position:absolute;margin-left:-50px;bottom:110px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:420px;height:220px;");
	// point of interest menu - restaurant button
	var $restaurant_information_button = $(document.createElement("DIV"));
	$restaurant_information_button.attr("id", "restaurant_information_button");
	$restaurant_information_button.attr("class", "button");
	$restaurant_information_button.attr("style", "margin-left:10px;width:90px;margin-top:7px;");
	$restaurant_information_button.html("<center><img src='images/point_of_interest/restaurant_button.png' style='height:40px;' width='40px' /><div>Restaurant</div></center>");
	google.maps.event.addDomListener($restaurant_information_button.get(0), 'click', function()
	{


		
		changePointOfInterestType("restaurant", map);
		
		//changePointOfInterestType("restaurant", map);
	});
	$point_of_interest_menu.append($restaurant_information_button);
	// point of interest menu - cafe button
	var $cafe_information_button = $(document.createElement("DIV"));
	$cafe_information_button.attr("id", "cafe_information_button");
	$cafe_information_button.attr("class", "button");
	$cafe_information_button.attr("style", "margin-left:10px;width:90px;margin-top:7px;");
	$cafe_information_button.html("<center><img src='images/point_of_interest/cafe_button.png' style='height:40px;' width='40px' /><div>Cafe</div></center>");
	google.maps.event.addDomListener($cafe_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("cafe", map);
	});
	$point_of_interest_menu.append($cafe_information_button);
	// point of interest menu - movie_theater button
	var $movie_theater_information_button = $(document.createElement("DIV"));
	$movie_theater_information_button.attr("id", "movie_theater_information_button");
	$movie_theater_information_button.attr("class", "button");
	$movie_theater_information_button.attr("style", "margin-left:10px;width:90px;margin-top:7px;");
	$movie_theater_information_button.html("<center><img src='images/point_of_interest/movie_theater_button.png' style='height:40px;' width='40px' /><div>Theater</div></center>");
	google.maps.event.addDomListener($movie_theater_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("movie_theater", map);
	});
	$point_of_interest_menu.append($movie_theater_information_button);
	// point of interest menu - art_gallery button
	var $art_gallery_information_button = $(document.createElement("DIV"));
	$art_gallery_information_button.attr("id", "art_gallery_information_button");
	$art_gallery_information_button.attr("class", "button");
	$art_gallery_information_button.attr("style", "margin-left:10px;width:90px;margin-top:7px;");
	$art_gallery_information_button.html("<center><img src='images/point_of_interest/art_gallery_button.png' style='height:40px;' width='40px' /><div>Art Gallery</div></center>");
	google.maps.event.addDomListener($art_gallery_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("art_gallery", map);
	});
	$point_of_interest_menu.append($art_gallery_information_button);
	// point of interest menu - gym button
	var $gym_information_button = $(document.createElement("DIV"));
	$gym_information_button.attr("id", "gym_information_button");
	$gym_information_button.attr("class", "button");
	$gym_information_button.attr("style", "margin-left:10px;width:90px;margin-top:7px;");
	$gym_information_button.html("<center><img src='images/point_of_interest/gym_button.png' style='height:40px;' width='40px' /><div>Gym</div></center>");
	google.maps.event.addDomListener($gym_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("gym", map);
	});
	$point_of_interest_menu.append($gym_information_button);
	// point of interest menu - hair_care button
	var $hair_care_information_button = $(document.createElement("DIV"));
	$hair_care_information_button.attr("id", "hair_care_information_button");
	$hair_care_information_button.attr("class", "button");
	$hair_care_information_button.attr("style", "margin-left:10px;width:90px;margin-top:10px;");
	$hair_care_information_button.html("<center><img src='images/point_of_interest/hair_care_button.png' style='height:40px;' width='40px' /><div>Hair Care</div></center>");
	google.maps.event.addDomListener($hair_care_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("hair_care", map);
	});
	$point_of_interest_menu.append($hair_care_information_button);
	// point of interest menu - pharmacy button
	var $pharmacy_information_button = $(document.createElement("DIV"));
	$pharmacy_information_button.attr("id", "pharmacy_information_button");
	$pharmacy_information_button.attr("class", "button");
	$pharmacy_information_button.attr("style", "margin-left:10px;width:90px;margin-top:10px;");
	$pharmacy_information_button.html("<center><img src='images/point_of_interest/pharmacy_button.png' style='height:40px;' width='40px' /><div>Pharmacy</div></center>");
	google.maps.event.addDomListener($pharmacy_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("pharmacy", map);
	});
	$point_of_interest_menu.append($pharmacy_information_button);
	// point of interest menu - travel_agency button
	var $travel_agency_information_button = $(document.createElement("DIV"));
	$travel_agency_information_button.attr("id", "travel_agency_information_button");
	$travel_agency_information_button.attr("class", "button");
	$travel_agency_information_button.attr("style", "margin-left:10px;width:90px;margin-top:10px;");
	$travel_agency_information_button.html("<center><img src='images/point_of_interest/travel_agency_button.png' style='height:40px;' width='40px' /><div>Travel Agency</div></center>");
	google.maps.event.addDomListener($travel_agency_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("travel_agency", map);
	});
	$point_of_interest_menu.append($travel_agency_information_button);
	// point of interest menu - home_goods_store button
	var $home_goods_store_information_button = $(document.createElement("DIV"));
	$home_goods_store_information_button.attr("id", "home_goods_store_information_button");
	$home_goods_store_information_button.attr("class", "button");
	$home_goods_store_information_button.attr("style", "margin-left:10px;width:90px;margin-top:10px;");
	$home_goods_store_information_button.html("<center><img src='images/point_of_interest/home_goods_store_button.png' style='height:40px;' width='40px' /><div>Store</div></center>");
	google.maps.event.addDomListener($home_goods_store_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("home_goods_store", map);
	});
	$point_of_interest_menu.append($home_goods_store_information_button);
	// point of interest menu - home_goods button
	var $grocery_or_supermarket_information_button = $(document.createElement("DIV"));
	$grocery_or_supermarket_information_button.attr("id", "grocery_or_supermarket_information_button");
	$grocery_or_supermarket_information_button.attr("class", "button");
	$grocery_or_supermarket_information_button.attr("style", "margin-left:10px;width:90px;margin-top:7px;");
	$grocery_or_supermarket_information_button.html("<center><img src='images/point_of_interest/grocery_or_supermarket_button.png' style='height:40px;' width='40px' /><div>Supermarket</div></center>");
	google.maps.event.addDomListener($grocery_or_supermarket_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("grocery_or_supermarket", map);
	});
	$point_of_interest_menu.append($grocery_or_supermarket_information_button);
	// point of interest menu - shoe_store button
	var $shoe_store_information_button = $(document.createElement("DIV"));
	$shoe_store_information_button.attr("id", "shoe_store_information_button");
	$shoe_store_information_button.attr("class", "button");
	$shoe_store_information_button.attr("style", "margin-left:10px;width:90px;margin-top:7px;");
	$shoe_store_information_button.html("<center><img src='images/point_of_interest/shoe_store_button.png' style='height:40px;' width='40px' /><div>Shoes Store</div></center>");
	google.maps.event.addDomListener($shoe_store_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("shoe_store", map);
	});
	$point_of_interest_menu.append($shoe_store_information_button);
	// point of interest menu - hospital button
	var $hospital_information_button = $(document.createElement("DIV"));
	$hospital_information_button.attr("id", "hospital_information_button");
	$hospital_information_button.attr("class", "button");
	$hospital_information_button.attr("style", "margin-left:10px;width:90px;margin-top:7px;height:70px;");
	$hospital_information_button.html("<center><img src='images/point_of_interest/hospital_button.png' style='height:40px;' width='40px' /><div>Hospital</div></center>");
	google.maps.event.addDomListener($hospital_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("hospital", map);
	});
	$point_of_interest_menu.append($hospital_information_button);

	
	$innerContainer.append($point_of_interest_menu);
	
	
	// traffic congestion menu
	var $traffic_congestion_menu = $(document.createElement("DIV"));
	$traffic_congestion_menu.attr("id", "traffic_congestion");
	$traffic_congestion_menu.attr("style", "display:none;position:absolute;margin-left:-50px;bottom:110px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:420px;height:68px;");
	var $weather_information_button = $(document.createElement("DIV"));
	$weather_information_button.attr("id", "weather_information_button");
	$weather_information_button.attr("class", "button");
	$weather_information_button.attr("style", "margin-left:60px;width:100px;margin-top:5px;");
	$weather_information_button.html("<center><img src='images/weather_sign.png' style='height:40px;' width='40px' /><div>Weather Information</div></center>");
	google.maps.event.addDomListener($weather_information_button.get(0), 'click', function() {
		
		// change class to chosen_mode
		$("#weather_information_button").attr('class', 'button chosen_mode');
		$("#camera_information_button").attr('class', 'button');
		$("#parking_information_button").attr('class', 'button');
		// remove other markers
		for (j=0; j<markerList.length; j++)
			markerList[j].setMap(null);
		
		
		// get weather info if uninitiazation
		if (weather_markers.length === 0)
		{
			$.ajax({
				type: "GET",
				url: "oulunliikenne_service.php?service=weather",
				cache: false,
				dataType: "xml",
				success: function(xml) {
					
					$(xml).find('item').each(function(){
						var name = $(this).find("title").text();
						//$("#info_panel").html($("#info_panel").html() + "<br>" + name);
						var id = "weather_" + name;
						var description = $(this).find("description").text();
						
						var weather_place = null;
						weather_place = findMarkerByName(weather_places, name);
						
						if (weather_place!==null)
						{
							var weather_marker = new google.maps.Marker({
								id: id,
								position: new google.maps.LatLng(weather_place.latitude,weather_place.longitude),
								map: map,
								title: name,
								icon: 'images/weather_map_icon.png'
							});
							markerList.push(weather_marker);
							
							var contentString = '<div style="width:170px;height:100px;">'+
							  '<b>' + name + '</b>'+
							  '<p>' + description + '</p>'+
							  '</div>';

							var infowindow = new google.maps.InfoWindow({
								content: contentString
							});
							inforWindowList.push(infowindow);
							google.maps.event.addListener(weather_marker, 'click', function() {
								for (var i = 0; i < inforWindowList.length; i++) {
									inforWindowList[i].close();
								}
								infowindow.open(map,weather_marker);
							});
							weather_markers.push(weather_marker);
						}
						
					});
				}
			});
		}
		// add weather info markers
		for (var i = 0; i < weather_markers.length; i++) {
			weather_markers[i].setMap(map);
		}
		
	});
	$traffic_congestion_menu.append($weather_information_button);
	
	var $camera_information_button = $(document.createElement("DIV"));
	$camera_information_button.attr("id", "camera_information_button");
	$camera_information_button.attr("class", "button");
	$camera_information_button.attr("style", "margin-left:10px;width:100px;margin-top:5px;");
	$camera_information_button.html("<center><img src='images/camera_sign.png' style='height:40px;' width='40px' /><div>Live Camera</div></center>");
	google.maps.event.addDomListener($camera_information_button.get(0), 'click', function() {
		
		// TODO: 
		// change class to chosen_mode
		$("#weather_information_button").attr('class', 'button');
		$("#camera_information_button").attr('class', 'button chosen_mode');
		$("#parking_information_button").attr('class', 'button');
		// remove other markers
		for (j=0; j<markerList.length; j++)
			markerList[j].setMap(null);
		
		// get camera info if uninitiazation
		if (camera_markers.length === 0)
		{
			$.ajax({
				type: "GET",
				url: "oulunliikenne_service.php?service=camera",
				cache: false,
				dataType: "xml",
				success: function(xml) {
					
					$(xml).find('item').each(function(){
						var name = $(this).find("title").text();

						var id = "camera_" + name;
						var description = $(this).find("description").text();
						var img_src= $(this).find("link").text();
						
						var camera_place = null;
						camera_place = findMarkerByName(camera_places, name);
						
						if (camera_place!==null)
						{
							var camera_marker = new google.maps.Marker({
								id: id,
								position: new google.maps.LatLng(camera_place.latitude,camera_place.longitude),
								map: map,
								title: name,
								icon: 'images/camera_map_icon.png'
							});
							markerList.push(camera_marker);
							
							
							var contentString = '<div style="width:170px;height:100px;">'+
							  '<b>' + name + '</b>'+
							  '<p><img src="' + img_src + '" style="width:70px;height:50px;" /></p>'+
							  '</div>';
							
							  var infowindow = new google.maps.InfoWindow({
								  content: contentString
							  });
							inforWindowList.push(infowindow);
							google.maps.event.addListener(camera_marker, 'click', function() {
								for (var i = 0; i < inforWindowList.length; i++) {
									inforWindowList[i].close();
								}
								infowindow.open(map,camera_marker);
							});
							camera_markers.push(camera_marker);
						}
						
					});
				}
			});
		}
		// add camera info markers
		for (var i = 0; i < camera_markers.length; i++) {
			camera_markers[i].setMap(map);
		}
		
	});
	$traffic_congestion_menu.append($camera_information_button);
	
	var $parking_information_button = $(document.createElement("DIV"));
	$parking_information_button.attr("id", "parking_information_button");
	$parking_information_button.attr("class", "button");
	$parking_information_button.attr("style", "margin-left:10px;width:100px;margin-top:5px;");
	$parking_information_button.html("<center><img src='images/parking_sign.png' style='height:40px;' width='40px' /><div>Parking Slots</div></center>");
	google.maps.event.addDomListener($parking_information_button.get(0), 'click', function() {
		
		// TODO: 
		// change class to chosen_mode
		$("#weather_information_button").attr('class', 'button');
		$("#camera_information_button").attr('class', 'button');
		$("#parking_information_button").attr('class', 'button chosen_mode');
		// remove other markers
		for (j=0; j<markerList.length; j++)
			markerList[j].setMap(null);
		
		// get parking info if uninitiazation
		if (parking_markers.length === 0)
		{
			$.ajax({
				type: "GET",
				url: "oulunliikenne_service.php?service=parking",
				cache: false,
				dataType: "xml",
				success: function(xml) 
				{
					
					$(xml).find('item').each(function(){
						var name = $(this).find("title").text();
						var id = "parking_" + name;
						var description = $(this).find("description").text();
						
						var geo_point = $(this).find('georss:point').context.children[6].innerHTML.split(" ");
						
						
						var parking_marker = new google.maps.Marker({
							id: id,
							position: new google.maps.LatLng(geo_point[1],geo_point[0]),
							map: map,
							title: name,
							icon: 'images/parking_map_icon.png'
						});
						markerList.push(parking_marker);
						var contentString = '<div style="width:170px;height:120px;">'+
						  '<b>' + name + '</b>'+
						  '<p>' + description + '</p>'+
						  '<p><a href="javascript:find_route(\'' + screen_address + ',oulu,finland\', new google.maps.LatLng(' + geo_point[1] + ',' + geo_point[0] + '),google.maps.TravelMode.WALKING );">Walk there</a></p>'+
						  '</div>';

						  var infowindow = new google.maps.InfoWindow({
							  content: contentString
						  });
						inforWindowList.push(infowindow);
						google.maps.event.addListener(parking_marker, 'click', function() {
							for (var i = 0; i < inforWindowList.length; i++) {
								inforWindowList[i].close();
							}
							infowindow.open(map,parking_marker);
						});
						parking_markers.push(parking_marker);
						
					});
				}
			});
		}
		// add parking info markers
		for (var i = 0; i < parking_markers.length; i++) {
			parking_markers[i].setMap(map);
		}
		
	});
	$traffic_congestion_menu.append($parking_information_button);
	
	$innerContainer.append($traffic_congestion_menu);
	
	// navigate menu
	var $navigation_menu = $(document.createElement("DIV"));
	$navigation_menu.attr("id", "navigation_menu");
	$navigation_menu.attr("style", "display:none;position:absolute;margin-left:-50px;bottom:110px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:420px;height:136px;");
	// navigate menu - navigate form
	$navigation_left_panel = $(document.createElement("DIV"));
	$navigation_left_panel.attr("style", "float:left");
	
	$navigateForm = $(document.createElement("DIV"));
	$navigateForm.attr("style", "margin:17px;margin-left:10px;margin-right:0;font-size:14px;float:left;");
	$navigateForm.html("From:&nbsp;&nbsp;<input id='start_place' class='text_box' value='' />&nbsp;&nbsp;To:&nbsp;&nbsp;<input id='destination' class='text_box' value='' />");
	$navigation_left_panel.append($navigateForm);
	
	
	$navigation_travel_mode = $(document.createElement("DIV"));
	$navigation_travel_mode.attr("style", "margin-left:8px;margin-right:0;font-size:14px;");
	$navigation_travel_mode_span = $(document.createElement("span"));
	$navigation_travel_mode_span.html("Travel mode: ");
	$navigation_travel_mode.append($navigation_travel_mode_span);
	// navigate menu - walking icon
	$walking_icon = $(document.createElement("img"));
	$walking_icon.attr("id", "walking_icon");
	$walking_icon.attr("src", "images/pedestrial.png");
	$walking_icon.attr("class", "travel_mode chosen_mode");
	google.maps.event.addDomListener($walking_icon.get(0), 'click', function() {
		change_travel_mode('walking_icon', google.maps.TravelMode.WALKING);
	});
	$navigation_travel_mode.append($walking_icon);
	// navigate menu - bicycle icon
	$bicycle_icon = $(document.createElement("img"));
	$bicycle_icon.attr("id", "bicycle_icon");
	$bicycle_icon.attr("src", "images/bike.png");
	$bicycle_icon.attr("class", "travel_mode");
	google.maps.event.addDomListener($bicycle_icon.get(0), 'click', function() {
		change_travel_mode('bicycle_icon', google.maps.TravelMode.BICYCLING);
	});
	$navigation_travel_mode.append($bicycle_icon);
	// navigate menu - transit icon
	$bus_icon = $(document.createElement("img"));
	$bus_icon.attr("id", "bus_icon");
	$bus_icon.attr("src", "images/bus.png");
	$bus_icon.attr("class", "travel_mode");
	google.maps.event.addDomListener($bus_icon.get(0), 'click', function() {
		change_travel_mode('bus_icon', google.maps.TravelMode.TRANSIT);
	});
	$navigation_travel_mode.append($bus_icon);
	$navigation_left_panel.append($navigation_travel_mode);
	
	// navigation menu - right panel
	$navigation_right_panel = $(document.createElement("div"));
	$navigation_right_panel.attr("style", "float:left");
	// navigate menu - right panel - navigate button
	$navigate_button = $(document.createElement("div"));
	$navigate_button.attr("style", "");
	$navigate_button.html("<button style='width:100px;height:90px;font-size:17px;margin-top:17px;margin-left:5px;'>Navigate</button>");
	google.maps.event.addDomListener($navigate_button.get(0), 'click', function() {
		navigate_route();
	});
	$navigation_right_panel.append($navigate_button);
	
	$navigation_menu.append($navigation_left_panel);
	$navigation_menu.append($navigation_right_panel);
	
	
	$innerContainer.append($navigation_menu);
	
	// Insert menu to map
	$container.append($innerContainer);
	map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push($container.get(0));
}