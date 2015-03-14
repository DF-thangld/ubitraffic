function menu(map)
{
	// menu container definition
	var $container = $(document.createElement('DIV'));
	$container.attr("style", "width:90%;height:90%;position:absolute;");
	var $innerContainer = $(document.createElement('DIV'));
	$innerContainer.attr("style", "");
	
	//show menu button
	var $show_menu = $(document.createElement('DIV'));
	$show_menu.attr("id", "show_menu");
	$show_menu.attr("style", "position:absolute;bottom:5%;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:15%;height:16%;");
	$show_menu.html("<img style='margin:3%' src='images/open_button.png' width='100%;' />");
	google.maps.event.addDomListener($show_menu.get(0), 'click', function() {
		$('#show_menu').css('display', 'none');
		$('#main_menu').css('display', 'inline');
		main_menu_opening=true;
	});
	$innerContainer.append($show_menu);
	
	//main menu
	var $main_menu = $(document.createElement("DIV"));
	$main_menu.attr("id", "main_menu");
	$main_menu.attr("style", "display:none;position:absolute;bottom:50px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:800px;height:68px;");
	
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
	$traffic_congestion_button.html("<center><img src='images/traffic_congestion_button.png' /><div>Traffic Congestion</div></center>");
	google.maps.event.addDomListener($traffic_congestion_button.get(0), 'click', function() {
		// TODO
	});
	$main_menu.append($traffic_congestion_button);
	
	// insert main menu to map
	$innerContainer.append($main_menu);
	
	// navigate menu
	var $navigation_menu = $(document.createElement("DIV"));
	$navigation_menu.attr("id", "navigation_menu");
	$navigation_menu.attr("style", "display:none;position:absolute;bottom:150px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:800px;height:68px;");
	// navigate menu - navigate form
	$navigateForm = $(document.createElement("DIV"));
	$navigateForm.attr("style", "margin:17px;margin-left:30px;margin-right:0;font-size:17px;float:left;");
	$navigateForm.html("From:&nbsp;&nbsp;&nbsp;<input id='start_place' class='text_box' value='' />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To:&nbsp;&nbsp;&nbsp;<input id='destination' class='text_box' value='' />");
	$navigation_menu.append($navigateForm);
	// navigate menu - walking icon
	$walking_icon = $(document.createElement("img"));
	$walking_icon.attr("id", "walking_icon");
	$walking_icon.attr("src", "images/pedestrial.png");
	$walking_icon.attr("class", "travel_mode chosen_mode");
	google.maps.event.addDomListener($walking_icon.get(0), 'click', function() {
		change_travel_mode('walking_icon', google.maps.TravelMode.WALKING);
	});
	$navigation_menu.append($walking_icon);
	// navigate menu - bicycle icon
	$bicycle_icon = $(document.createElement("img"));
	$bicycle_icon.attr("id", "bicycle_icon");
	$bicycle_icon.attr("src", "images/bike.png");
	$bicycle_icon.attr("class", "travel_mode");
	google.maps.event.addDomListener($bicycle_icon.get(0), 'click', function() {
		change_travel_mode('bicycle_icon', google.maps.TravelMode.BICYCLING);
	});
	$navigation_menu.append($bicycle_icon);
	// navigate menu - transit icon
	$bus_icon = $(document.createElement("img"));
	$bus_icon.attr("id", "bus_icon");
	$bus_icon.attr("src", "images/bus.png");
	$bus_icon.attr("class", "travel_mode");
	google.maps.event.addDomListener($bus_icon.get(0), 'click', function() {
		change_travel_mode('bus_icon', google.maps.TravelMode.TRANSIT);
	});
	$navigation_menu.append($bus_icon);
	// navigate menu - navigate button
	$navigate_button = $(document.createElement("button"));
	$navigate_button.attr("style", "width:100px;height:37px;font-size:17px;margin-top:17px;margin-left:5px;");
	$navigate_button.html("Navigate");
	google.maps.event.addDomListener($navigate_button.get(0), 'click', function() {
		navigate_route();
	});
	$navigation_menu.append($navigate_button);
	$innerContainer.append($navigation_menu);
	
	// Insert menu to map
	$container.append($innerContainer);
	map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push($container.get(0));
}