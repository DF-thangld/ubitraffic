<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple markers</title>
	
    
	
	<!-- jQuery -->
	<script src="javascript/jquery.js" type="text/javascript"></script>
	
	<!-- jQuery UI -->
    <link href="javascript/jquery-ui-1.11.3/jquery-ui.css" rel="stylesheet" />
	<script src="javascript/jquery-ui-1.11.3/jquery-ui.min.js" type="text/javascript"></script>
	
	<!-- Facebox -->
	<link href="javascript/facebox/src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="javascript/facebox/facebox.js" type="text/javascript"></script>
	
	<!-- Virtual keyboard -->
	<link href="javascript/Keyboard-master/css/keyboard.css" rel="stylesheet" />
	<script src="javascript/Keyboard-master/js/jquery.keyboard.js" type="text/javascript"></script>
	
	<!-- Google Map -->
	<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
	<script src="javascript/gmap3/gmap3.js" type="text/javascript"></script>
	
	<style>
		.gmap3{
			width: 500px;
			height: 250px;
		}
		.button
		{
			width:100px;
			float:left;
		}
		.text_box
		{
			width:200px;
			height:30px;
		}
		.travel_mode
		{
			height:30px;
			float:left;
			margin-top:17px;
			margin-left:3px;
		}
		.chosen_mode
		{
			border:2px solid;
			border-color: #2a3333;
			border-radius: 1px
		}
    </style>
	
	<script type="text/javascript">
	
	//menu parameters
	var showMenu = null, fullMenu = null;
	var fullMenu_show = false;
	var navigationMenu_show = false;
	
	var travel_mode_map = google.maps.TravelMode.WALKING;
	var travel_mode_link = 'walking';
	var screen_address = 'yliopistokatu 12';
	var origin_place = screen_address;
	var destination_place = 'torikatu 9';
	var markers = [];
	markers.push({id:'ubi_sreen', latLng:[65.013130, 25.476192], data:"UBI Screen"});
	
	// Remove a marker from markers array
	function removeMarker(markers, marker_id)
	{
		return markers.filter(function (el) 
			{
				return el.id !== marker_id;
            });
	}
	
	function initMap()
	{
		$("#map_panel").gmap3({
			marker:{
				values:markers,
				options:{
					draggable: false
				}
			},
			map:{
				options:{
					zoom: 16,
					disableDefaultUI: true
				},
					
				callback: function(map){
					showMenu = new ShowMenuButton(map);
					
					new NavigationMenu(map);
					fullMenu = new Menu(map);
					
					google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
						//this part runs when the mapobject is created and rendered
						google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
							// check if full menu is shown, if yes display it, if no hide it
							if (fullMenu_show)
							{
								$('#showMenu').css('display', 'none');
								$('#fullMenu').css('display', 'inline');
							}
							else
							{
								$('#showMenu').css('display', 'inline');
								$('#fullMenu').css('display', 'none');
							}
							
							//check if map navigation is shown, if yes display it, if no hide it
							if (navigationMenu_show) // display
								$('#navigationMenu').css('display', 'inline');
							else //hide
								$('#navigationMenu').css('display', 'none');
						});
					});
					
				}
			}
		});
		
		
	}
	
	
	markers.push({id:'ubi_sreen11', latLng:[65.014130, 25.476192], data:"UBI Screen"});
	markers.push({id:'ubi_sreen3', latLng:[65.015130, 25.476192], data:"UBI Screen"});
	markers = removeMarker(markers, 'ubi_sreen');
	
	function ShowMenuButton(map) 
	{
		var $container = $(document.createElement('DIV')),
			$outer = $(document.createElement('DIV')),
			$inner = $(document.createElement('DIV'));
        
		$inner.addClass("inner").html("<div style='margin-bottom:50px;float:left;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:73px;height:68px;'><img style='' src='images/open_button.png' alt='Smiley face'/></div>");
		$container.addClass("outer").attr('title', "Click to set the map to Home");
		$container.attr("id", "showMenu");
      
		$container.append( $outer.append( $inner ) );
      
		google.maps.event.addDomListener($outer.get(0), 'click', function() {
			fullMenu_show = true;
			$('#showMenu').fadeOut();
			$('#fullMenu').fadeIn();
		});
      
		this.index = 1;
		map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push($container.get(0));
    }
	
	function Menu(map)
	{
		var $container = $(document.createElement('DIV'));
		$container.attr("id", "fullMenu");
		$container.attr("style", "display:none;float:left;margin-left:-77px;margin-bottom:50px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:800px;height:68px;");
		
		
		var $closeButton = $(document.createElement('DIV'));
		$closeButton.attr("class", "button");
		$closeButton.attr("style", "margin-left:3px");
		$closeButton.html("<img src='images/close_button.png' />");
		google.maps.event.addDomListener($closeButton.get(0), 'click', function() {
			fullMenu_show = false;
			$('#fullMenu').fadeOut();
			$('#showMenu').fadeIn();
		});
		
		var $navigationButton = $(document.createElement('DIV'));
		$navigationButton.attr("class", "button");
		$navigationButton.html("<center><img src='images/navigation_button.png' /><div>Navigation</div></center>");
		google.maps.event.addDomListener($navigationButton.get(0), 'click', function() {

			if (navigationMenu_show === false)
			{
				$('#start_place').keyboard();
				$('#destination').keyboard();
				$('#navigationMenu').fadeIn();
				navigationMenu_show = true;
			}
			else 
			{
				$('#navigationMenu').fadeOut();
				navigationMenu_show = false;
			}
		});
		
		var $busTimetableButton = $(document.createElement('DIV'));
		$busTimetableButton.attr("class", "button");
		$busTimetableButton.html("<center><img src='images/bus_timetable_button.png' /><div>Timetable</div></center>");
		google.maps.event.addDomListener($busTimetableButton.get(0), 'click', function() {
			//TODO
		});
		
		var $placeButton = $(document.createElement('DIV'));
		$placeButton.attr("class", "button");
		$placeButton.html("<center><img src='images/point_of_interest.png' style='width:45px;margin-top:3px;' /><div>Points of interest</div></center>");
		google.maps.event.addDomListener($placeButton.get(0), 'click', function() {
			//TODO
		});
		
		var $trafficCongestionButton = $(document.createElement('DIV'));
		$trafficCongestionButton.attr("class", "button");
		$trafficCongestionButton.html("<center><img src='images/traffic_congestion_button.png' /><div>Traffic Congestion</div></center>");
		google.maps.event.addDomListener($trafficCongestionButton.get(0), 'click', function() {
			//TODO
		});
		
		var $resetCSS = $(document.createElement('DIV'));
		$resetCSS.attr("style", "clear:both");
		
		$container.append($closeButton);
		$container.append($navigationButton);
		$container.append($busTimetableButton);
		$container.append($placeButton);
		$container.append($trafficCongestionButton);
		$container.append($resetCSS);
		//$container.append($navigationForm);
		
		map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push($container.get(0));
		
	}
	
	//
	function NavigationMenu(map)
	{
		var $container = $(document.createElement('DIV'));
		//$container.attr("id", "navigationMenu");
		$container.attr("id", "navigationMenu");
		$container.attr("style", "display:none;margin-left:-77px;margin-bottom:140px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:800px;height:68px;");
		
		$inner = $(document.createElement('div'));
		$inner.attr("style", "margin:17px;margin-left:30px;margin-right:0;font-size:17px;float:left;");
		$inner.html("From:&nbsp;&nbsp;&nbsp;<input id='start_place' class='text_box' value='" + origin_place + "' />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To:&nbsp;&nbsp;&nbsp;<input id='destination' class='text_box' value='" + destination_place + "' />");
		$container.append($inner);
		
		$walking_icon = $(document.createElement('img'));
		$walking_icon.attr("id", "walking_icon");
		$walking_icon.attr("src", "images/pedestrial.png");
		$walking_icon.attr("class", "travel_mode chosen_mode");
		google.maps.event.addDomListener($walking_icon.get(0), 'click', function() {
			travel_mode_map = google.maps.TravelMode.WALKING;
			travel_mode_link = 'walking';
			$("#walking_icon").attr('class', 'travel_mode chosen_mode');
			$("#bicycle_icon").attr('class', 'travel_mode');
			$("#bus_icon").attr('class', 'travel_mode');
		});
		$container.append($walking_icon);
		
		$bicycle_icon = $(document.createElement('img'));
		$bicycle_icon.attr("id", "bicycle_icon");
		$bicycle_icon.attr("src", "images/bike.png");
		$bicycle_icon.attr("class", "travel_mode");
		google.maps.event.addDomListener($bicycle_icon.get(0), 'click', function() {
			travel_mode_map = google.maps.TravelMode.BICYCLING;
			travel_mode_link = 'bicycling';
			$("#walking_icon").attr('class', 'travel_mode');
			$("#bicycle_icon").attr('class', 'travel_mode chosen_mode');
			$("#bus_icon").attr('class', 'travel_mode');
		});
		$container.append($bicycle_icon);
		
		$bus_icon = $(document.createElement('img'));
		$bus_icon.attr("id", "bus_icon");
		$bus_icon.attr("src", "images/bus.png");
		$bus_icon.attr("class", "travel_mode");
		google.maps.event.addDomListener($bus_icon.get(0), 'click', function() {
			travel_mode_map = google.maps.TravelMode.TRANSIT;
			travel_mode_link = 'transit';
			$("#walking_icon").attr('class', 'travel_mode');
			$("#bicycle_icon").attr('class', 'travel_mode');
			$("#bus_icon").attr('class', 'travel_mode chosen_mode');
		});
		$container.append($bus_icon);
		
		$button = $(document.createElement('button'));
		$button.attr("style", "width:100px;height:37px;font-size:17px;margin-top:17px;margin-left:5px;");
		$button.html("Navigate");
		google.maps.event.addDomListener($button.get(0), 'click', function() {

			origin_place = $('#start_place').val();
			destination_place = $('#destination').val();
			
			//refresh map
			$('#map_panel').gmap3('destroy').remove();
			$("#map_container").append('<div style="border:10px solid;border-color: #2a3333;border-radius: 25px;width:960px;height:486px" id="map_panel"></div>');
			initMap();
			
			//find route
			$("#map_panel").gmap3({
			  getroute:{
				id:'abc',
				options:{
					origin: origin_place + ', oulu, finland',
					destination: destination_place + ', oulu, finland',
					travelMode: travel_mode_map
				},
				callback: function(results){
					/*var map = $(this).gmap3("get");
					var directionDisplay = new google.maps.DirectionsRenderer();
					directionDisplay.suppressMarkers = true;
					directionDisplay.setMap(map);*/
					//console.log(map.controls);
				  if (!results) return;
				  $(this).gmap3({
					map:{
					  options:{
						zoom: 13,  
						center: [-33.879, 151.235]
					  }
					},
					directionsrenderer:{
						//container: $('#info_panel'),
					  options:{
						directions:results
					  } 
					}
				  });
				  $('#info_panel').html('');
				  $.each(results.routes[0].legs[0].steps, function(index, value){
						//console.log("INDEX: " + index + " VALUE: " + value.instructions);
						$('#info_panel').html($('#info_panel').html()+'<div>'+value.instructions+'</div>');
					});
				}
			  }
			});
			
			
		});
		
		$container.append($button);
		map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push($container.get(0));
	}
	
    $(function(){
        initMap();
		
    });
    </script>

  </head>
	
    
	
	<body>
		<div id='map_container'>
			<div style="border:10px solid;border-color: #2a3333;border-radius: 25px;width:960px;height:486px" id="map_panel"></div>
		</div>
		
		
		<div id="info_panel"></div>
	
	
	</body>
</html>