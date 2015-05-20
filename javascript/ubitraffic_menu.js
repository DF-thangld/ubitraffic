var point_of_interest_types = [	"restaurant", "cafe", "movie_theater", "art_gallery", 
								"gym", "hair_care", "travel_agency", "pharmacy", 
								"hospital", "grocery_or_supermarket", "shoe_store", "home_goods_store"];

								
/*Cafes and Restaurants - 3
Beauty ja Health 4
Real Estate Management - 2
Other - -1
Culture ja Leisure - 6
Marketing ja Media - 8
Shopping - 33
Authorities - 50
Specialist Services - 54
Finance ja Insurance - 61
*/
var points_of_interest = [];
points_of_interest["-1"] = [];
points_of_interest["2"] = [];
points_of_interest["3"] = [];
points_of_interest["4"] = [];
points_of_interest["6"] = [];
points_of_interest["8"] = [];
points_of_interest["33"] = [];
points_of_interest["50"] = [];
points_of_interest["54"] = [];
points_of_interest["61"] = [];

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

var busStopInfo = [];
var busStopMarkerList = [];
var inforWindowList = [];
var markerList = [];
var bus_shapes = [];

var point_of_interest_service;

var selectInserted = false;

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


function changePointOfInterestType(point_of_interest_type, map)
{
	var nearest_point = 0;
	for (j=0; j<markerList.length; j++)
		markerList[j].setMap(null);
	var nearest_lat = 0;
	var nearest_lon = 0;
	var nearest_distance = 99999999999999;
	if (points_of_interest[point_of_interest_type].length === 0)
	{

		//missing screen_point since the js file first created, I add it but don't know whether it is correct
		//var screen_point = new google.maps.LatLng(65.057333, 25.472555);
		
		
		
		$.ajax({
			type: "GET",
			url: "oulunliikenne_places.php?category_id=" + point_of_interest_type.toString(),
			async:false,
			cache: false,
			dataType: "xml",
			success: function(xml) {
				
				$(xml).find('place').each(function(){
				
					
					var company_id = $(this).find("company_id").text();
					var company_name = $(this).find("company_name").text();
					var business_id = $(this).find("business_id").text();
					var description_1 = $(this).find("description_1").text();
					var description_2 = $(this).find("description_2").text();
					var url = $(this).find("url").text();
					var address = $(this).find("address").text();
					var phone = $(this).find("phone").text();
					var email = $(this).find("email").text();
					var office_hours_1 = $(this).find("office_hours_1").text();
					var office_hours_2 = $(this).find("office_hours_2").text();
					var image = $(this).find("image").text();
					
					var lat = $(this).find("lat").text();
					var lon = $(this).find("lon").text();
				
					var distance = Math.sqrt(Math.pow(parseFloat(lat) - screen_lat, 2) + Math.pow(parseFloat(lon) - screen_lon, 2));
					if (distance < nearest_distance)
					{
						nearest_distance = distance;
						nearest_lat = lat;
						nearest_lon = lon;
					}
					
					if (description_1 !== '')
						description_1 = '<p>' +  description_1 + '</p>';
					if (phone !== '')
						phone = '<p>Phone: ' +  phone + '</p>';
					if (url !== '')
						url = '<p>Website: ' +  url + '</p>';
					if (email !== '')
						email = '<p>Email: ' +  email + '</p>';
					if (office_hours_1 !== '')
						office_hours_1 = '<p>Opening hours: ' +  office_hours_1 + '</p>';
					if (image !== '')
						image = '<p><img src="' + image + '" /></p>';
							
					var place_content_string = '<div id="content">'+
					  '<h2 id="markerHeading" class="markerHeading">'+company_name+'</h2>'+
					  '<div id="bodyContent">'+		  
					  description_1 + 
					  '<p>Address: '+address+'</p>'+
					  phone + 
					  email +
					  url +
					  office_hours_1 +
					  image + 
					  '</div></div>';
					//console.log(placeContentString);
							
					//create marker image
					var image = {
						url: "images/point_of_interest/" + point_of_interest_type + "_icon.png",
						scaledSize: new google.maps.Size(25, 25)
					};
							  
					//create actual marker
					var place_marker = new google.maps.Marker({
						map: map,
						position: new google.maps.LatLng(lat, lon),
						icon: image,
						title: company_name
					});
					
					place_marker.setMap(map);
					
					markerList.push(place_marker);
					points_of_interest[point_of_interest_type].push(place_marker);
					
					var infowindow = new google.maps.InfoWindow({
						content: place_content_string
					});
					google.maps.event.addListener(place_marker, 'click', function() {
						
						for (var i = 0; i < inforWindowList.length; i++) {
							inforWindowList[i].close();
						}
						
						infowindow.open(map,place_marker);
						email_text = infowindow.getContent();
						RabbitMQ_send("html",email_text);
								
								
						$.ajax({
							type: "GET",
							url: "oulunliikenne_statistic.php",
							data: { instance_id: "xyz", // change instance_id to the right variable
									action: "CLICK_PLACE", 
									data_1: point_of_interest_type.toString(),
									data_2: company_id}, 
							cache: false
						});
					});
					inforWindowList.push(infowindow);
					
				});
			}
		});
		
		var center = new google.maps.LatLng(nearest_lat, nearest_lon);
		map.panTo(center);

	}
	else
	{

		
		for (j=0; j<points_of_interest[point_of_interest_type].length; j++)
		{
			points_of_interest[point_of_interest_type][j].setMap(map);
			
			
			var distance = Math.sqrt(Math.pow(points_of_interest[point_of_interest_type][j].internalPosition.A - screen_lat, 2) + Math.pow(points_of_interest[point_of_interest_type][j].internalPosition.F - screen_lon, 2));
			if (distance < nearest_distance)
			{
				nearest_distance = distance;
				nearest_lat = points_of_interest[point_of_interest_type][j].internalPosition.A;
				nearest_lon = points_of_interest[point_of_interest_type][j].internalPosition.F;
			}
			
			
		}
		var center = new google.maps.LatLng(nearest_lat, nearest_lon);
		map.panTo(center);
	}

	for (i = 0, len = point_of_interest_types.length; i < len; i++)
	{
		$("#"+point_of_interest_types[i]+"_information_button").attr("class", "button");
	}

	$("#bus_stop_information_button").attr("class", "button");
	$('#point_of_interest').fadeOut(0);


	//This cause the problem with the position change after click
	//$("#"+point_of_interest_type+"_information_button").attr("class", "button chosen_mode");
	$.ajax({
                type: "GET",
                url: "oulunliikenne_statistic.php",
                data: { instance_id: "xyz", // change instance_id to the right variable
                                                action: "PLACE",
                                                data_1: point_of_interest_type},
                cache: false
	});

}

function get_all_bus_stops(map)
{
	for (j=0; j<markerList.length; j++)
		markerList[j].setMap(null);
	var nearest_distance = 9999999999999;
	var nearest_lat = 0;
	var nearest_lon = 0;
	$('#point_of_interest').fadeOut(0);
	if (busStopMarkerList.length == 0)
	{
		$.ajax({
			type: "GET",
			url: "oulunliikenne_siri_service.php?service=all_bus_stops",
			cache: false,
			dataType: "xml",
			success: function(xml) {
				
				$(xml).find('stop').each(function(){
					var stop_id = $(this).find("stop_id").text();
					var name = $(this).find("stop_name").text();
					var latitude = $(this).find("stop_lat").text();
					var longitude = $(this).find("stop_lon").text();
					var distance = Math.sqrt(Math.pow(parseFloat(latitude) - screen_lat, 2) + Math.pow(parseFloat(longitude) - screen_lon, 2));
					if (distance < nearest_distance)
					{
						nearest_distance = distance;
						nearest_lat = latitude;
						nearest_lon = longitude;
					}
					var busStopMarker = new google.maps.Marker({
						position: new google.maps.LatLng(latitude,longitude),
						map: map,
						title: name,
						icon: 'images/bus_stop_icon.png'
					});
					busStopMarker.setMap(map);
					markerList.push(busStopMarker);
					busStopMarkerList.push(busStopMarker);
					
					google.maps.event.addListener(busStopMarker, 'click', function() {
						for (var i = 0; i < inforWindowList.length; i++)
						{
							inforWindowList[i].close();
						}
						var infowindow = null;
						$.ajax({
							type: "GET",
							url: "oulunliikenne_siri_service.php?service=bus_stop&stop_id="+stop_id,
							async :false,
							cache: false,
							dataType: "xml",
							success: function(xml_bus)
							{
								var incoming_buses = '';
								$(xml_bus).find('bus').each(function()
								{
									var bus_name = $(this).find("route_short_name").text();
									var bus_headsign = $(this).find("trip_headsign").text();
									var bus_arrival_time = $(this).find("arrival_time").text();
									
									incoming_buses += '<tr><td>' + bus_name + '</td><td>' + bus_headsign + '</td><td>' + bus_arrival_time + '</td></tr>';
								});
								
								var contentString = '<table>'+
													'<tr><td colspan="3"><center><b>' + name + '</b></center></td></tr>'+
													'<tr><td>Number</td><td>Destination</td><td>Arrival time</td></tr>'+
													incoming_buses+
													'</table>';

								infowindow = new google.maps.InfoWindow({
									content: contentString
								});
								inforWindowList.push(infowindow);
							}
						});
						
						
						
						infowindow.open(map,busStopMarker);
						email_text = infowindow.getContent();
						RabbitMQ_send("html",email_text);
						
						
						$.ajax({
							type: "GET",
							url: "oulunliikenne_statistic.php",
							data: { instance_id: "xyz", // change instance_id to the right variable
															action: "CLICK_PLACE", 
															data_1: 'bus_stop',
															data_2: stop_id}, 
							cache: false
						});
					});
					
					
					
				});
				var center = new google.maps.LatLng(nearest_lat, nearest_lon);
				map.panTo(center);
			}
		});
	}
	else
	{
		for (j=0; j<busStopMarkerList.length; j++)
		{
			busStopMarkerList[j].setMap(map);
			var distance = Math.sqrt(Math.pow(busStopMarkerList[j].internalPosition.A - screen_lat, 2) + Math.pow(busStopMarkerList[j].internalPosition.F - screen_lon, 2));
			if (distance < nearest_distance)
			{
				nearest_distance = distance;
				nearest_lat = busStopMarkerList[j].internalPosition.A;
				nearest_lon = busStopMarkerList[j].internalPosition.F;
			}
		}
		var center = new google.maps.LatLng(nearest_lat, nearest_lon);
		map.panTo(center);
	}
	
	for (i = 0, len = point_of_interest_types.length; i < len; i++)
	{
		$("#"+point_of_interest_types[i]+"_information_button").attr("class", "button");
	}
	$("#bus_stop_information_button").attr("class", "button chosen_mode");
	$.ajax({

		type: "GET",

		url: "oulunliikenne_statistic.php",

		data: { instance_id: "xyz", // change instance_id to the right variable
				action: "PLACE",
				data_1: 'bus_stops'},
		cache: false

	});
}

function show_shape(route_id, direction_id)
{
	for (j=0; j<markerList.length; j++)
		markerList[j].setMap(null);
	for (j=0; j<bus_shapes.length; j++)
		bus_shapes[j].setMap(null);
		
	$.ajax({
			type: "GET",
			url: "oulunliikenne_siri_service.php?service=route&route_id=" + route_id + "&direction_id=" + direction_id,
			cache: false,
			dataType: "xml",
			success: function(xml) {
				//display bus routes
				var shapes = [];
				$(xml).find('shape').each(function()
				{
					var shape_point = new google.maps.LatLng($(this).find("shape_pt_lat").text(), $(this).find("shape_pt_lon").text())
					shapes.push(shape_point);
				});
				var bus_path = new google.maps.Polyline({
					path: shapes,
					geodesic: true,
					strokeColor: '#FF0000',
					strokeOpacity: 1.0,
					strokeWeight: 2
				});
				bus_shapes.push(bus_path);
				bus_path.setMap(map);
				
				//display bus stops
				$(xml).find('stop').each(function()
				{
					var stop_id = $(this).find("stop_id").text();
					var busStopMarker = new google.maps.Marker({
						position: new google.maps.LatLng($(this).find("stop_lat").text(), $(this).find("stop_lon").text()),
						map: map,
						title: name,
						icon: 'images/bus_stop_icon.png'
					});
					if ($(this).find("is_next_stop").text() == '1')
					{
						busStopMarker.setIcon('images/busIcon.png');
						busStopMarker.setAnimation(google.maps.Animation.BOUNCE);
					}
					markerList.push(busStopMarker);
					
					google.maps.event.addListener(busStopMarker, 'click', function() {
						for (var i = 0; i < inforWindowList.length; i++)
						{
							inforWindowList[i].close();
						}
						
						var infowindow = null;
						$.ajax({
							type: "GET",
							url: "oulunliikenne_siri_service.php?service=bus_stop&stop_id="+stop_id,
							async :false,
							cache: false,
							dataType: "xml",
							success: function(xml_bus)
							{
								var incoming_buses = '';
								$(xml_bus).find('bus').each(function()
								{
									var bus_name = $(this).find("route_short_name").text();
									var bus_headsign = $(this).find("trip_headsign").text();
									var bus_arrival_time = $(this).find("arrival_time").text();
									
									incoming_buses += '<tr><td>' + bus_name + '</td><td>' + bus_headsign + '</td><td>' + bus_arrival_time + '</td></tr>';
								});
								
								var contentString = '<table>'+
													'<tr><td colspan="3"><center><b>' + name + '</b></center></td></tr>'+
													'<tr><td>Number</td><td>Destination</td><td>Arrival time</td></tr>'+
													incoming_buses+
													'</table>';

								infowindow = new google.maps.InfoWindow({
									content: contentString
								});
								inforWindowList.push(infowindow);
							}
						});
						
						
						infowindow.open(map,busStopMarker);
						email_text = infowindow.getContent();
						RabbitMQ_send("html",email_text);
						
						
						$.ajax({
							type: "GET",
							url: "oulunliikenne_statistic.php",
							data: { instance_id: "xyz", // change instance_id to the right variable
															action: "CLICK_BUS_STOP_FROM_TIMETABLE", 
															data_1: route_id,
															data_2: direction_id,
															data_3: stop_id}, 
							cache: false
						});
					});
					
					
				});
				
			}
		});
	
	$.ajax({

                type: "GET",
                url: "oulunliikenne_statistic.php",
                data: { instance_id: "xyz", // change instance_id to the right variable
                                                action: "BUS_TIMETABLE",
                                                data_1: route_id,
                                                data_2: direction_id},
                cache: false

	});
}

function menu(map)
{



	// menu container definition
	var $container = $(document.createElement('DIV'));
	$container.attr("style", "position:absolute;margin-bottom:50px");
	var $innerContainer = $(document.createElement('DIV'));
	$innerContainer.attr("style", "margin-bottom:60px;margin-left:-150px");
	var $mainContainer = $(document.createElement('DIV'));
	$mainContainer.attr("style", "");

	
	//new code
	var $menu = $(document.getElementById("wrap_menu"));
	google.maps.event.addDomListener($menu.get(0), 'mouseover', function() {
		$('#point_of_interest_menu').css('display', 'inline');
		$('#traffic_congestion_menu').css('display', 'inline');
		$('#navigation_button').css('display', 'inline');
		$('#bus_timetable_button').css('display', 'inline');
		$('#main_menu').css('display', 'inline');
		main_menu_opening=true;
	});
	google.maps.event.addDomListener($menu.get(0), 'mouseout', function() {
		//$('#innerContainer').css('display', 'none');
		main_menu_opening=false;
	});
	var $main_menu = $(document.getElementById("main_menu"));
	$mainContainer.append($menu);
	

	//__________________



	//main menu - navigate button
	var $navigation_button = $(document.createElement("DIV"));
	$navigation_button.attr("id", "navigation_button");
	$navigation_button.attr("class", "button");
	$navigation_button.attr("style", "margin-left:3px;margin-top:10px;");
	$navigation_button.html("<center><img src='images/navigation_button.png' style='margin-left:15px' /><div style='margin-top:-10px' ><h3>Navigation</div></center>");
	google.maps.event.addDomListener($navigation_button.get(0), 'click', function() {
		
		change_sub_menu('navigation_menu');
		
	});
	$main_menu.append($navigation_button);

	
	//main menu - bus timetable button
	var $bus_timetable_button = $(document.createElement("DIV"));
	$bus_timetable_button.attr("id", "bus_timetable_button");
	$bus_timetable_button.attr("class", "button");
	$bus_timetable_button.attr("style", "margin-left:12px;margin-top:10px;");
	$bus_timetable_button.html("<center><img src='images/bus_timetable_button.png' /><div style='margin-top:-10px;'><h3>Timetable</div></center>");
	google.maps.event.addDomListener($bus_timetable_button.get(0), 'click', function() {
		change_sub_menu('bus_timetable_menu');
		var content = "";
		$.ajax({
				type: "GET",
				url: "oulunliikenne_siri_service.php?service=bus_lines",
				cache: true,
				dataType: "xml",
				success: function(xml) 
				{
					console.log("success");
					if(!selectInserted)
					{
						$(xml).find('line').each(function(){						
							var route_short_name = $(this).find("route_short_name").text();
							var route_long_name = $(this).find("route_long_name").text();
							$('#bus_line').append($("<option></option>").attr("value", route_short_name).text(route_short_name+ " ("+route_long_name+")")); 
						});
						selectInserted = true;
					}
					if (content === "")
					{
						$("#bus_direction_form").html("Cannot find route, please try again");
					}
					else
					{
						$("#bus_direction_form").html("<div>Direction:</div><table>"+ content + "</table>");
					}
				}
			});
	});
	$main_menu.append($bus_timetable_button);
	
	//main menu - place button
	var $place_button = $(document.createElement("DIV"));
	$place_button.attr("id", "place_button");
	$place_button.attr("class", "button");
	$place_button.attr("style", "margin-left:3px;margin-top:10px;");
	$place_button.html("<center><img src='images/point_of_interest.png' style='' /><div style='margin-top:-10px'><h3>Places</div></center>");
	google.maps.event.addDomListener($place_button.get(0), 'click', function() {
		change_sub_menu('point_of_interest');
	});
	$main_menu.append($place_button);
	
	//main menu - traffic congestion button
	var $traffic_congestion_button = $(document.createElement("DIV"));
	$traffic_congestion_button.attr("id", "traffic_congestion_button");
	$traffic_congestion_button.attr("class", "button");
	$traffic_congestion_button.attr("style", "margin-left:3px;margin-top:10px;");
	$traffic_congestion_button.html("<center><img src='images/traffic_congestion_button.png' /><div style='margin-top:-10px'><h3>Traffic</div></center>");
	google.maps.event.addDomListener($traffic_congestion_button.get(0), 'click', function() {
		change_sub_menu('traffic_congestion');
	});
	$main_menu.append($traffic_congestion_button);
	
	// point of interest menu
	var $point_of_interest_menu = $(document.createElement("DIV"));
	$point_of_interest_menu.attr("id", "point_of_interest");
	$point_of_interest_menu.attr("style", "display:none;position:absolute;margin-left:-50px;bottom:110px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:470px;height:280px;");
	// point of interest menu - restaurant button
	var $restaurant_information_button = $(document.createElement("DIV"));
	$restaurant_information_button.attr("id", "restaurant_information_button");
	$restaurant_information_button.attr("class", "button");
	$restaurant_information_button.attr("style", "margin-left:10px;width:90px;margin-top:7px;");
	$restaurant_information_button.html("<center><img src='images/point_of_interest/3_button.png' style='height:40px;' width='40px' /><div style='font-size:15px'>Cafes and Restaurants</div></center>");
	google.maps.event.addDomListener($restaurant_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("3", map);
	});
	$point_of_interest_menu.append($restaurant_information_button);
	// point of interest menu - cafe button
	var $cafe_information_button = $(document.createElement("DIV"));
	$cafe_information_button.attr("id", "cafe_information_button");
	$cafe_information_button.attr("class", "button");
	$cafe_information_button.attr("style", "margin-left:10px;width:90px;margin-top:7px;");
	$cafe_information_button.html("<center><img src='images/point_of_interest/4_button.png' style='height:40px;' width='40px' /><div style='font-size:15px'>Beauty ja Health</div></center>");
	google.maps.event.addDomListener($cafe_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("4", map);
	});
	$point_of_interest_menu.append($cafe_information_button);
	// point of interest menu - movie_theater button
	var $movie_theater_information_button = $(document.createElement("DIV"));
	$movie_theater_information_button.attr("id", "movie_theater_information_button");
	$movie_theater_information_button.attr("class", "button");
	$movie_theater_information_button.attr("style", "margin-left:10px;width:90px;margin-top:7px;");
	$movie_theater_information_button.html("<center><img src='images/point_of_interest/2_button.png' style='height:40px;' width='40px' /><div style='font-size:15px'>Real Estate Management</div></center>");
	google.maps.event.addDomListener($movie_theater_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("2", map);
	});
	$point_of_interest_menu.append($movie_theater_information_button);
	// point of interest menu - art_gallery button
	var $art_gallery_information_button = $(document.createElement("DIV"));
	$art_gallery_information_button.attr("id", "art_gallery_information_button");
	$art_gallery_information_button.attr("class", "button");
	$art_gallery_information_button.attr("style", "margin-left:10px;width:90px;margin-top:7px;");
	$art_gallery_information_button.html("<center><img src='images/point_of_interest/6_button.png' style='height:40px;' width='40px' /><div style='font-size:15px'>Culture ja Leisure</div></center>");
	google.maps.event.addDomListener($art_gallery_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("6", map);
	});
	$point_of_interest_menu.append($art_gallery_information_button);
	// point of interest menu - hair_care button
	var $hair_care_information_button = $(document.createElement("DIV"));
	$hair_care_information_button.attr("id", "hair_care_information_button");
	$hair_care_information_button.attr("class", "button");
	$hair_care_information_button.attr("style", "margin-left:10px;width:90px;margin-top:10px;");
	$hair_care_information_button.html("<center><img src='images/point_of_interest/8_button.png' style='height:40px;' width='40px' /><div style='font-size:15px'>Marketing ja Media</div></center>");
	google.maps.event.addDomListener($hair_care_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("8", map);
	});
	$point_of_interest_menu.append($hair_care_information_button);
	// point of interest menu - pharmacy button
	var $pharmacy_information_button = $(document.createElement("DIV"));
	$pharmacy_information_button.attr("id", "pharmacy_information_button");
	$pharmacy_information_button.attr("class", "button");
	$pharmacy_information_button.attr("style", "margin-left:10px;width:90px;margin-top:10px;");
	$pharmacy_information_button.html("<center><img src='images/point_of_interest/33_button.png' style='height:40px;' width='40px' /><div style='font-size:15px'>Shopping</div></center>");
	google.maps.event.addDomListener($pharmacy_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("33", map);
	});
	$point_of_interest_menu.append($pharmacy_information_button);
	// point of interest menu - travel_agency button
	var $travel_agency_information_button = $(document.createElement("DIV"));
	$travel_agency_information_button.attr("id", "travel_agency_information_button");
	$travel_agency_information_button.attr("class", "button");
	$travel_agency_information_button.attr("style", "margin-left:10px;width:90px;margin-top:10px;");
	$travel_agency_information_button.html("<center><img src='images/point_of_interest/50_button.png' style='height:40px;' width='40px' /><div style='font-size:15px'>Authorities</div></center>");
	google.maps.event.addDomListener($travel_agency_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("50", map);
	});
	$point_of_interest_menu.append($travel_agency_information_button);
	// point of interest menu - home_goods_store button
	var $home_goods_store_information_button = $(document.createElement("DIV"));
	$home_goods_store_information_button.attr("id", "home_goods_store_information_button");
	$home_goods_store_information_button.attr("class", "button");
	$home_goods_store_information_button.attr("style", "margin-left:10px;width:90px;margin-top:10px;");
	$home_goods_store_information_button.html("<center><img src='images/point_of_interest/54_button.png' style='height:40px;' width='40px' /><div style='font-size:15px'>Specialist Services</div></center>");
	google.maps.event.addDomListener($home_goods_store_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("54", map);
	});
	$point_of_interest_menu.append($home_goods_store_information_button);
	// point of interest menu - home_goods button
	var $grocery_or_supermarket_information_button = $(document.createElement("DIV"));
	$grocery_or_supermarket_information_button.attr("id", "grocery_or_supermarket_information_button");
	$grocery_or_supermarket_information_button.attr("class", "button");
	$grocery_or_supermarket_information_button.attr("style", "margin-left:10px;width:90px;margin-top:7px;");
	$grocery_or_supermarket_information_button.html("<center><img src='images/point_of_interest/61_button.png' style='height:40px;' width='40px' /><div style='font-size:15px'>Finance ja Insurance</div></center>");
	google.maps.event.addDomListener($grocery_or_supermarket_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("61", map);
	});
	$point_of_interest_menu.append($grocery_or_supermarket_information_button);
	
	// point of interest menu - bus stop button
	var $bus_stop_information_button = $(document.createElement("DIV"));
	$bus_stop_information_button.attr("id", "bus_stop_information_button");
	$bus_stop_information_button.attr("class", "button");
	$bus_stop_information_button.attr("style", "margin-left:10px;width:90px;margin-top:7px;");
	$bus_stop_information_button.html("<center><img src='images/bus_stop_button.png' style='height:40px;' width='40px' /><div style='font-size:15px'>Bus Stops</div></center>");
	google.maps.event.addDomListener($bus_stop_information_button.get(0), 'click', function()
	{
		//changePointOfInterestType("shoe_store", map);
		get_all_bus_stops(map);
	});
	$point_of_interest_menu.append($bus_stop_information_button);
	
	// point of interest menu - hospital button
	var $hospital_information_button = $(document.createElement("DIV"));
	$hospital_information_button.attr("id", "hospital_information_button");
	$hospital_information_button.attr("class", "button");
	$hospital_information_button.attr("style", "margin-left:10px;width:90px;margin-top:7px;height:70px;");
	$hospital_information_button.html("<center><img src='images/point_of_interest/hospital_button.png' style='height:40px;' width='40px' /><div style='font-size:15px'>Others</div></center>");
	google.maps.event.addDomListener($hospital_information_button.get(0), 'click', function()
	{
		changePointOfInterestType("-1", map);
	});
	$point_of_interest_menu.append($hospital_information_button);
	
	
	$innerContainer.append($point_of_interest_menu);
	
	
	// traffic congestion menu
	var $traffic_congestion_menu = $(document.createElement("DIV"));
	$traffic_congestion_menu.attr("id", "traffic_congestion");
	$traffic_congestion_menu.attr("style", "display:none;position:absolute;margin-left:-50px;bottom:110px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:420px;height:90px;");
	var $weather_information_button = $(document.createElement("DIV"));
	$weather_information_button.attr("id", "weather_information_button");
	$weather_information_button.attr("class", "button");
	$weather_information_button.attr("style", "margin-left:40px;width:100px;margin-top:5px;");
	$weather_information_button.html("<center><img src='images/weather_sign.png' style='height:40px;' width='40px' /><div style='font-size:15px'>Weather Information</div></center>");
	google.maps.event.addDomListener($weather_information_button.get(0), 'click', function() {
		


		// change class to chosen_mode
		$("#weather_information_button").attr('class', 'button chosen_mode');
		$("#camera_information_button").attr('class', 'button');
		$("#parking_information_button").attr('class', 'button');
		// remove other markers
		for (j=0; j<markerList.length; j++)
			markerList[j].setMap(null);
		
		var nearest_distance = 99999999999999999999;
		var nearest_lat = 0;
		var nearest_lon = 0;
		
		$('#traffic_congestion').fadeOut(0);
		
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
							weather_marker.setMap(map);
							var distance = Math.sqrt(Math.pow(parseFloat(weather_place.latitude) - screen_lat, 2) + Math.pow(parseFloat(weather_place.longitude) - screen_lon, 2));
							
							if (distance < nearest_distance)
							{
								nearest_distance = distance;
								nearest_lat = weather_place.latitude;
								nearest_lon = weather_place.longitude;
								
							}
							
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
								email_text = infowindow.getContent();
								RabbitMQ_send("html",email_text);
								$.ajax({

									type: "GET",

									url: "oulunliikenne_statistic.php",

									data: { instance_id: "xyz", // change instance_id to the right variable

																	action: "CLICK_TRAFFIC_PLACE",

																	data_1: "WEATHER", // change to {WEATHER, CAMERA, PARKING} in actual code

																	data_2: name},

									cache: false

								});
							});
							weather_markers.push(weather_marker);
						}
						
					});

					var center = new google.maps.LatLng(nearest_lat, nearest_lon);
					map.panTo(center);
				}
			});
			
		}
		else
		{
			// add weather info markers
			for (var i = 0; i < weather_markers.length; i++) {
				weather_markers[i].setMap(map);
				var distance = Math.sqrt(Math.pow(weather_markers[i].internalPosition.A - screen_lat, 2) + Math.pow(weather_markers[i].internalPosition.F - screen_lon, 2));
				if (distance < nearest_distance)
				{
					nearest_distance = distance;
					nearest_lat = weather_markers[i].internalPosition.A;
					nearest_lon = weather_markers[i].internalPosition.F;
				}
			}
			var center = new google.maps.LatLng(nearest_lat, nearest_lon);
			map.panTo(center);
		}
		$.ajax({

                type: "GET",

                url: "oulunliikenne_statistic.php",

                data: { instance_id: "xyz", // change instance_id to the right variable

                                                action: "TRAFFIC",

                                                data_1: 'WEATHER'}, // change to {WEATHER, CAMERA, PARKING} in actual code

                cache: false

		});
		
	});
	$traffic_congestion_menu.append($weather_information_button);
	
	var $camera_information_button = $(document.createElement("DIV"));
	$camera_information_button.attr("id", "camera_information_button");
	$camera_information_button.attr("class", "button");
	$camera_information_button.attr("style", "margin-left:20px;width:100px;margin-top:5px;");
	$camera_information_button.html("<center><img src='images/camera_sign.png' style='height:40px;' width='40px' /><div style='font-size:15px'>Live Camera</div></center>");
	google.maps.event.addDomListener($camera_information_button.get(0), 'click', function() {
		
		// TODO: 
		// change class to chosen_mode
		$("#weather_information_button").attr('class', 'button');
		$("#camera_information_button").attr('class', 'button chosen_mode');
		$("#parking_information_button").attr('class', 'button');
		// remove other markers
		for (j=0; j<markerList.length; j++)
			markerList[j].setMap(null);
		
		var nearest_distance = 9999999999;
		var nearest_lat = 0;
		var nearest_lon = 0;
		$('#traffic_congestion').fadeOut(0);
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
							camera_marker.setMap(map);
							var distance = Math.sqrt(Math.pow(parseFloat(camera_place.latitude) - screen_lat, 2) + Math.pow(parseFloat(camera_place.longitude) - screen_lon, 2));
							if (distance < nearest_distance)
							{
								nearest_distance = distance;
								nearest_lat = camera_place.latitude;
								nearest_lon = camera_place.longitude;
							}
							
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
								email_text = infowindow.getContent();
								RabbitMQ_send("html",email_text);
								$.ajax({
                					type: "GET",
                					url: "oulunliikenne_statistic.php",
                					data: { instance_id: "xyz", // change instance_id to the right variable
                                                action: "CLICK_TRAFFIC_PLACE",
                                                data_1: "CAMERA", // change to {WEATHER, CAMERA, PARKING} in actual code
                                                data_2: name},
                					cache: false

									});
							});
							camera_markers.push(camera_marker);
						}
						
					});
					var center = new google.maps.LatLng(nearest_lat, nearest_lon);
					map.panTo(center);
				}
			});
		}
		else
		{
			// add camera info markers
			for (var i = 0; i < camera_markers.length; i++) {
				camera_markers[i].setMap(map);
				var distance = Math.sqrt(Math.pow(camera_markers[i].internalPosition.A - screen_lat, 2) + Math.pow(camera_markers[i].internalPosition.F - screen_lon, 2));
				if (distance < nearest_distance)
				{
					nearest_distance = distance;
					nearest_lat = camera_markers[i].internalPosition.A;
					nearest_lon = camera_markers[i].internalPosition.F;
				}
			}
			var center = new google.maps.LatLng(nearest_lat, nearest_lon);
			map.panTo(center);
		}
		
		$.ajax({
			type: "GET",
			url: "oulunliikenne_statistic.php",
			data: { instance_id: "xyz", // change instance_id to the right variable

                                                action: "TRAFFIC",

                                                data_1: 'CAMERA'}, // change to {WEATHER, CAMERA, PARKING} in actual code

			cache: false

		});
		
	});
	$traffic_congestion_menu.append($camera_information_button);
	
	var $parking_information_button = $(document.createElement("DIV"));
	$parking_information_button.attr("id", "parking_information_button");
	$parking_information_button.attr("class", "button");
	$parking_information_button.attr("style", "margin-left:20px;width:100px;margin-top:5px;");
	$parking_information_button.html("<center><img src='images/parking_sign.png' style='height:40px;' width='40px' /><div style='font-size:15px'>Parking Slots</div></center>");
	google.maps.event.addDomListener($parking_information_button.get(0), 'click', function() {
		
		// TODO: 
		// change class to chosen_mode
		$("#weather_information_button").attr('class', 'button');
		$("#camera_information_button").attr('class', 'button');
		$("#parking_information_button").attr('class', 'button chosen_mode');
		// remove other markers
		for (j=0; j<markerList.length; j++)
			markerList[j].setMap(null);
		
		var nearest_distance = 9999999999;
		var nearest_lat = 0;
		var nearest_lon = 0;
		$('#traffic_congestion').fadeOut(0);
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
						
						parking_marker.setMap(map);
						var distance = Math.sqrt(Math.pow(parseFloat(geo_point[1]) - screen_lat, 2) + Math.pow(parseFloat(geo_point[0]) - screen_lon, 2));
						if (distance < nearest_distance)
						{
							nearest_distance = distance;
							nearest_lat = geo_point[1];
							nearest_lon = geo_point[0];
						}
							
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
							email_text = infowindow.getContent();
							RabbitMQ_send("html",email_text);
							$.ajax({
								type: "GET",
								url: "oulunliikenne_statistic.php",
								data: { instance_id: "xyz", // change instance_id to the right variable
																action: "CLICK_TRAFFIC_PLACE",
																data_1: "PARKING", // change to {WEATHER, CAMERA, PARKING} in actual code
																data_2: name},
								cache: false

							});
						});
						parking_markers.push(parking_marker);
						
					});
					var center = new google.maps.LatLng(nearest_lat, nearest_lon);
					map.panTo(center);
				}
			});
		}
		else
		{
			// add parking info markers
			for (var i = 0; i < parking_markers.length; i++) {
				parking_markers[i].setMap(map);
				var distance = Math.sqrt(Math.pow(parking_markers[i].internalPosition.A - screen_lat, 2) + Math.pow(parking_markers[i].internalPosition.F - screen_lon, 2));
				if (distance < nearest_distance)
				{
					nearest_distance = distance;
					nearest_lat = parking_markers[i].internalPosition.A;
					nearest_lon = parking_markers[i].internalPosition.F;
				}
			}
			var center = new google.maps.LatLng(nearest_lat, nearest_lon);
			map.panTo(center);
		}
		
		$.ajax({
			type: "GET",
			url: "oulunliikenne_statistic.php",
			data: { instance_id: "xyz", // change instance_id to the right variable

                                                action: "TRAFFIC",

                                                data_1: 'PARKING'}, // change to {WEATHER, CAMERA, PARKING} in actual code

			cache: false

		});
		
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
	$navigateForm.attr("style", "margin:17px;margin-left:10px;margin-right:0;font-size:18px;float:left;");
	$navigateForm.html("From:&nbsp;&nbsp;<input id='start_place' class='text_box' value='' />&nbsp;&nbsp;To:&nbsp;&nbsp;<input id='destination' class='text_box' value='' />");
	$navigation_left_panel.append($navigateForm);
	
	
	$navigation_travel_mode = $(document.createElement("DIV"));
	$navigation_travel_mode.attr("style", "margin-left:8px;margin-right:0;font-size:18px;");
	$navigation_travel_mode_span = $(document.createElement("span"));
	$navigation_travel_mode_span.html("Travel mode: ");
	$navigation_travel_mode.append($navigation_travel_mode_span);
	// navigate menu - walking icon
	$walking_icon = $(document.createElement("img"));
	$walking_icon.attr("id", "walking_icon");
	$walking_icon.attr("src", "images/pedestrial.png");
	$walking_icon.attr("width", "60px");
	$walking_icon.attr("height", "60px");
	$walking_icon.attr("class", "travel_mode chosen_mode");
	google.maps.event.addDomListener($walking_icon.get(0), 'click', function() {
		change_travel_mode('walking_icon', google.maps.TravelMode.WALKING);
	});
	$navigation_travel_mode.append($walking_icon);
	// navigate menu - bicycle icon
	$bicycle_icon = $(document.createElement("img"));
	$bicycle_icon.attr("id", "bicycle_icon");
	$bicycle_icon.attr("src", "images/bike.png");
	$walking_icon.attr("width", "60px");
	$walking_icon.attr("height", "60px");
	$bicycle_icon.attr("class", "travel_mode");
	google.maps.event.addDomListener($bicycle_icon.get(0), 'click', function() {
		change_travel_mode('bicycle_icon', google.maps.TravelMode.BICYCLING);
	});
	$navigation_travel_mode.append($bicycle_icon);
	// navigate menu - transit icon
	$bus_icon = $(document.createElement("img"));
	$bus_icon.attr("id", "bus_icon");
	$bus_icon.attr("src", "images/bus.png");
	$walking_icon.attr("width", "60px");
	$walking_icon.attr("height", "60px");
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
	
	$navigate_button.html("<img src='images/startNavigation.png' style='width:100px;height:100px;margin-top:17px;margin-left:5px;' >");
	
	google.maps.event.addDomListener($navigate_button.get(0), 'click', function() {
		navigate_route();
		$('#navigation_menu').css('display', 'none');
		
	});
	$navigation_right_panel.append($navigate_button);
	$navigation_menu.append($navigation_left_panel);
	$navigation_menu.append($navigation_right_panel);
	$innerContainer.append($navigation_menu);
	
	// bus_timetable menu
	var $bus_timetable_menu = $(document.createElement("DIV"));
	$bus_timetable_menu.attr("id", "bus_timetable_menu");
	$bus_timetable_menu.attr("style", "display:none;position:absolute;margin-left:-50px;bottom:110px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:420px;height:230px;");
	// bus_timetable menu - bus number form
	var $bus_number_form = $(document.createElement("DIV"));
	$bus_number_form.attr("id", "bus_number_form");
	$bus_number_form.attr("style", "margin:17px;margin-left:10px;margin-right:0;font-size:18px;");
	$bus_number_form.html("Type bus number: <input id='bus_number' class='text_box' value='' style='width:80px;' /> ");
	
	//bus dropdown
	var $bus_dropdown = $(document.createElement("DIV"));
	$bus_dropdown.attr("id", "bus_dropdown");
	$bus_dropdown.attr("style", "margin:17px;margin-left:10px;margin-right:0;font-size:18px;");
	$bus_dropdown.html("or choose bus number: <select id='bus_line' value='' style='width:225px;' /> ");
	google.maps.event.addDomListener($bus_dropdown.get(0), 'click', function() {
		var content = "";
		$('#bus_number').val($("#bus_line").val());
		$.ajax({
				type: "GET",
				url: "oulunliikenne_siri_service.php?service=bus_directions&route_id=" + $("#bus_line").val(),
				cache: true,
				dataType: "xml",
				success: function(xml) 
				{
					$(xml).find('direction').each(function(){
						var route_id = $(this).find("route_id").text();
						var route_short_name = $(this).find("route_short_name").text();
						var route_long_name = $(this).find("route_long_name").text();
						var direction_id = $(this).find("direction_id").text();
						var trip_headsign = $(this).find("trip_headsign").text();
						content += "<tr height='30px;'><td>Direction to <b>" + trip_headsign + "</b></td><td><button style='margin-left:5px;' onclick='show_shape(\"" + route_id + "\", "+direction_id+");'>Show this direction</button></td></tr>";
					});
					if (content === "")
					{
						$("#bus_direction_form").html("Cannot find route, please try again");
					}
					else
					{
						$("#bus_direction_form").html("<div>Direction:</div><table>"+ content + "</table>");
					}
				}
			});
		
	});
	
	$bus_info_button = $(document.createElement("button"));
	$bus_info_button.attr("id", "display_bus_info");
	$bus_info_button.attr("style", "height:28px;");
	$bus_info_button.html("Display Bus Information");
	google.maps.event.addDomListener($bus_info_button.get(0), 'click', function() {
		var content = "";
		$("#bus_line").val($("#bus_number").val());
		$.ajax({
				type: "GET",
				url: "oulunliikenne_siri_service.php?service=bus_directions&route_id=" + $("#bus_number").val(),
				cache: true,
				dataType: "xml",
				success: function(xml) 
				{
					$(xml).find('direction').each(function(){
						var route_id = $(this).find("route_id").text();
						var route_short_name = $(this).find("route_short_name").text();
						var route_long_name = $(this).find("route_long_name").text();
						var direction_id = $(this).find("direction_id").text();
						var trip_headsign = $(this).find("trip_headsign").text();
						content += "<tr><td>Direction to <b>" + trip_headsign + "</b></td><td><input type='radio' name='show_bus_shape' onclick='show_shape(\"" + route_id + "\", "+direction_id+");'></td></tr>";
					});
					if (content === "")
					{
						$("#bus_direction_form").html("Cannot find route, please try again");
					}
					else
					{
						$("#bus_direction_form").html("<div>Direction:</div><table>"+ content + "</table>");
					}
				}
			});
	});
	
	
	$bus_number_form.append($bus_info_button);
	$bus_number_form.append($bus_dropdown);
	$bus_timetable_menu.append($bus_number_form);
	// bus_timetable menu - direction_form
	var $bus_direction_form = $(document.createElement("DIV"));
	$bus_direction_form.attr('id', 'bus_direction_form');
	$bus_direction_form.attr('style', 'font-size:14px;margin-left:10px;');
	$bus_direction_form.html("");
	$bus_timetable_menu.append($bus_direction_form);
	
	$innerContainer.append($bus_timetable_menu);
	
	var $zoom_menu = $(document.createElement('DIV'));
	$zoom_menu.attr("style", "");
	// zoom-in button
	var $zoomin_menu = $(document.createElement('DIV'));
	$zoomin_menu.attr("id", "zoomin_menu");
	$zoomin_menu.attr("style", "float:left;");
	$zoomin_menu.html("<img style='' width='50px;' src='images/zoom_in.png' />");
	google.maps.event.addDomListener($zoomin_menu.get(0), 'click', function()
	{
		var zoom_value = map.getZoom();
		map.setZoom(zoom_value+1);
		$.ajax({
                type: "GET",
                url: "oulunliikenne_statistic.php",
                data: { instance_id: "xyz", // change instance_id to the right variable
                                                action: "ZOOM_IN",
                                                data_1: zoom_value,
                                                data_2: zoom_value+1},
                cache: false
		});
	});
	$zoom_menu.append($zoomin_menu);
	// zoom-out button
	var $zoomout_menu = $(document.createElement('DIV'));
	$zoomout_menu.attr("id", "zoomout_menu");
	$zoomout_menu.attr("style", "float:left;");
	$zoomout_menu.html("<img style='' width='50px;' src='images/zoom_out.png' />");
	google.maps.event.addDomListener($zoomout_menu.get(0), 'click', function() {
		var zoom_value = map.getZoom();
		map.setZoom(zoom_value-1);
		$.ajax({
                type: "GET",
                url: "oulunliikenne_statistic.php",
                data: { instance_id: "xyz", // change instance_id to the right variable
					action: "ZOOM_OUT",
                    data_1: zoom_value,
					data_2: zoom_value-1},
                cache: false
		});
	});
	$zoom_menu.append($zoomout_menu);
	
	// download menu
	var $download_menu = $(document.createElement('DIV'));
	$download_menu.attr("id", "download_menu");
	$download_menu.attr("style", "float:left;");
	$download_menu.html("<img style='' width='50px;' src='images/download_button.png' />");
	google.maps.event.addDomListener($download_menu.get(0), 'click', function() {		
		if($('#download_div').css('display') == 'none')
		{
			$('#download_div').css('display', 'inline');
			if($('#QRcode'))
			{
				$('#QRcode').css('display', 'none');
			}
			if ($('#mailDone'))
			{
				$('#mailDone').css('display', 'none');
			}
		}
		else
			$('#download_div').css('display', 'none');
		
	});
	$zoom_menu.append($download_menu);
	
	//download div
	var $download_div = $(document.createElement('DIV'));
	$download_div.attr('id', 'download_div');
	//$download_div.attr("style", "font-size:14px; display:none;position:absolute;left:"+$('#map_panel').width()*0.25+"px;top:230px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:420px;height:136px;");
	$download_div.attr("style", "font-size:14px; display:none;position:absolute;left:280px;top:400px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:420px;height:136px;");
	$download_div.html("<center style='margin-top:5px;'>Download map to phone</center>");
	$('body').append($download_div);
	
	//close button for download div
	$closeButton = $(document.createElement('a'));
	$closeButton.attr("id", "close_download");
	$closeButton.attr("style", "position:absolute;height:35px; width:35px;float:right; right:-20px; top:-20px;cursor:pointer;border:1px solid;border-color: #2a3333;border-radius:15px;display:inline-block; padding: 2px 5px; background: #ccc; text-align:center; padding-top:10px;");
	$closeButton.html("close");
	$download_div.append($closeButton);
	
	google.maps.event.addDomListener($closeButton.get(0), 'click', function() {
		$download_div.css('display', 'none');
	});
	
	
	//qr code div
	var $qrcode_div = $(document.createElement('DIV'));
	$qrcode_div.attr("id", "qrcode_div");
	$qrcode_div.attr("class", "button");
	$qrcode_div.attr("style", "margin-left:80px;width:100px;margin-top:5px;");
	$qrcode_div.html("");
	
	//qr button
	$qr_button = $(document.createElement("button"));
	$qr_button.attr("id", "qr_button");
	$qr_button.attr("style", "height:38px;");
	$qr_button.html("Create QR Code");
	$qrcode_div.append($qr_button);
	
	google.maps.event.addDomListener($qr_button.get(0), 'click', function() {
		downloadToPhone("QR");
	});
	$download_div.append($qrcode_div);	
	
	
	//actual qr code
	var $qrcode = $(document.createElement('DIV'));
	$qrcode.attr("id", "QRcode");	
	$qrcode.attr("style", "position:absolute;width:110px;height:110px;right:162px;background-color:white;border:2px solid;border-color:#2a3333;border-radius:6px;");
	$qrcode.html("");
	
	
	//create close button for qr code
	/*var $closeB = $(document.createElement('a'));
	$closeB.attr("id", "close_qr");
	$closeB.attr("style", "position:absolute;height:17px; width:9px;float:right; right:-10px; top:-10px;cursor:pointer;border:1px solid;border-color: #2a3333;border-radius:15px;display:inline-block; padding: 2px 5px; background: #ccc;");
	$closeB.html("x");
	$qrcode.append($closeB);
	
	google.maps.event.addDomListener($closeB.get(0), 'click', function() {
		$qrcode.css('display', 'none');
	});*/
	$download_div.append($qrcode);	
	
	
	
	//e-mail div
	var $email_div = $(document.createElement('DIV'));
	$email_div.attr("id", "email_div");
	$email_div.attr("class", "button");
	$email_div.attr("style", "margin-left:60px;width:100px;margin-top:5px;");
	$email_div.html("<center><input id='email_input' class='text_box' value='' style='width:125px;'></input></center>"); //<img src='images/weather_sign.png' style='height:40px;' width='40px' /><div>E-mail</div>
	
	var $mailDone = $(document.createElement('DIV'));
	$mailDone.attr("id", "mailDone");					
	$mailDone.attr("style", "display=none; position:absolute;width:210px;height:110px;right:105px;background-color:white;border:2px solid;border-color:#2a3333;border-radius:6px; padding:10px;");
	$mailDone.html("");
	$email_div.append($mailDone);	
	
	//email button
	$email_button = $(document.createElement("button"));
	$email_button.attr("id", "email_button");
	$email_button.attr("style", "height:28px;");
	$email_button.html("Send E-mail");
	$email_div.append($email_button);	
	google.maps.event.addDomListener($email_button.get(0), 'click', function() {
		downloadToPhone("Email");
	});
	
	$download_div.append($email_div);	
	
	
	
	// Insert menu to map
	//$container.append($innerContainer);
	$container.append($mainContainer);
	map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push($container.get(0));
	map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push($innerContainer.get(0));
	map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push($zoom_menu.get(0));
}