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
		// TODO
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
		for (var i = 0; i < camera_markers.length; i++) {
			camera_markers[i].setMap(null);
		}
		for (var i = 0; i < parking_markers.length; i++) {
			parking_markers[i].setMap(null);
		}
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
							
							var contentString = '<div style="width:170px;height:100px;">'+
							  '<b>' + name + '</b>'+
							  '<p>' + description + '</p>'+
							  '</div>';

							  var infowindow = new google.maps.InfoWindow({
								  content: contentString
							  });
							google.maps.event.addListener(weather_marker, 'click', function() {
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
		for (var i = 0; i < parking_markers.length; i++) {
			parking_markers[i].setMap(null);
		}
		for (var i = 0; i < weather_markers.length; i++) {
			weather_markers[i].setMap(null);
		}
		
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
							
							var contentString = '<div style="width:170px;height:100px;">'+
							  '<b>' + name + '</b>'+
							  '<p><img src="' + img_src + '" style="width:70px;height:50px;" /></p>'+
							  '</div>';

							  var infowindow = new google.maps.InfoWindow({
								  content: contentString
							  });
							google.maps.event.addListener(camera_marker, 'click', function() {
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
		for (var i = 0; i < camera_markers.length; i++) {
			camera_markers[i].setMap(null);
		}
		for (var i = 0; i < weather_markers.length; i++) {
			weather_markers[i].setMap(null);
		}
		
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
						//console.log ($(this).find('georss:point').context.children);
						
						var geo_point = $(this).find('georss:point').context.children[6].innerHTML.split(" ");
						
						//var parking_place = null;
						//window.alert(geo_point);
						console.log (geo_point);
						var parking_marker = new google.maps.Marker({
							id: id,
							position: new google.maps.LatLng(geo_point[1],geo_point[0]),
							map: map,
							title: name,
							icon: 'images/parking_map_icon.png'
						});
						var contentString = '<div style="width:170px;height:120px;">'+
						  '<b>' + name + '</b>'+
						  '<p>' + description + '</p>'+
						  '<p><a href="javascript:find_route(\'' + screen_address + ',oulu,finland\', new google.maps.LatLng(' + geo_point[1] + ',' + geo_point[0] + '),google.maps.TravelMode.WALKING );">Walk there</a></p>'+
						  '</div>';

						  var infowindow = new google.maps.InfoWindow({
							  content: contentString
						  });
						google.maps.event.addListener(parking_marker, 'click', function() {
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