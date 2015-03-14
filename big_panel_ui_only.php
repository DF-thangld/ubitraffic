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
			width:130px;
			float:left;
		}
		.button img
		{
			width:45px;
			height:45px;
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
	var sub_menu_opening = '';
	var main_menu_opening = false;
	
	var travel_mode_map = google.maps.TravelMode.WALKING;
	var travel_mode_link = 'walking';
	var screen_address = 'yliopistokatu 12';
	var origin_place = screen_address;
	var destination_place = 'torikatu 9';   

    $(function(){
        //initMap();
		$('#start_place').val(origin_place);
		$('#destination').val(destination_place);
    });
	
	function change_sub_menu(new_menu)
	{
		if (sub_menu_opening===new_menu)
		{
			$('#'+new_menu).css('display', 'none');
			sub_menu_opening='';
		}
		else
		{
			$('#'+sub_menu_opening).css('display', 'none');
			sub_menu_opening=new_menu;
			$('#'+new_menu).css('display', 'inline');
		}
	}
	
	function change_travel_mode(travel_mode_icon, travel_mode)
	{
		
		travel_mode_map = travel_mode;
		
		if (travel_mode_icon==='walking_icon')
		{
			$("#walking_icon").attr('class', 'travel_mode chosen_mode');
			$("#bicycle_icon").attr('class', 'travel_mode');
			$("#bus_icon").attr('class', 'travel_mode');
		}
		else if (travel_mode_icon==='bicycle_icon')
		{
			$("#walking_icon").attr('class', 'travel_mode');
			$("#bicycle_icon").attr('class', 'travel_mode chosen_mode');
			$("#bus_icon").attr('class', 'travel_mode');
		}
		else if (travel_mode_icon==='bus_icon')
		{
			$("#walking_icon").attr('class', 'travel_mode');
			$("#bicycle_icon").attr('class', 'travel_mode');
			$("#bus_icon").attr('class', 'travel_mode chosen_mode');
		}
		
	}
	
	function navigate_route()
	{
	
	}
	
    </script>

  </head>
	
    
	
	<body>
		<div id='map_container'>
			<div style="border:10px solid;border-color: #2a3333;border-radius: 25px;width:960px;height:486px" id="map_panel"></div>
		</div>
		<hr/>
		
		<div style="border:10px solid;border-color: #2a3333;border-radius: 25px;width:960px;height:486px;position: relative;" id="map_panel1">
			<div id="menu" style="margin-top:50px;margin-left:50px;position:absolute;left:0;bottom:0">
				<div style="position: relative;">
					<!-- Open menu button -->
					<div id="show_menu">
						<div style='position:absolute;bottom:50px;float:left;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:73px;height:68px;' onClick="$('#show_menu').css('display', 'none');$('#main_menu').css('display', 'inline');main_menu_opening=true;">
							<img style='' src='images/open_button.png'/>
						</div>
					</div>
					
					<!-- Main menu -->
					<div id="main_menu" style="display:none;position:absolute;bottom:50px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:800px;height:68px;">
						
						<div id="close_button" class="button" style="margin-left:3px;" onclick="$('#main_menu').css('display', 'none');$('#show_menu').css('display', 'inline');main_menu_opening=false;$('#'+sub_menu_opening).css('display', 'none');">
							<img src='images/close_button.png' style="width:70px;height:67px;" />
						</div>
						
						<div id="navigation_button" class="button" onclick="change_sub_menu('navigation_menu');">
							<center><img src='images/navigation_button.png' /><div>Navigation</div></center>
						</div>
						
						<div id="bus_timetable_button" class="button">
							<center><img src='images/bus_timetable_button.png' /><div>Timetable</div></center>
						</div>
						
						<div id="place_button" class="button">
							<center><img src='images/point_of_interest.png' style='' /><div>Points of interest</div></center>
						</div>
						
						<div id="traffic_congestion_button" class="button">
							<center><img src='images/traffic_congestion_button.png' /><div>Traffic Congestion</div></center>
						</div>
					</div>
					
					<!-- Navigation menu -->
					<div id="navigation_menu" style="display:none;position:absolute;bottom:150px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:800px;height:68px;">
						<div style="margin:17px;margin-left:30px;margin-right:0;font-size:17px;float:left;">
							From:&nbsp;&nbsp;&nbsp;<input id='start_place' class='text_box' value='' />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To:&nbsp;&nbsp;&nbsp;<input id='destination' class='text_box' value='' />
						</div>
						<img id="walking_icon" src="images/pedestrial.png" class="travel_mode chosen_mode" onclick="change_travel_mode('walking_icon', google.maps.TravelMode.WALKING);" />
						<img id="bicycle_icon" src="images/bike.png" class="travel_mode" onclick="change_travel_mode('bicycle_icon', google.maps.TravelMode.BICYCLING);" />
						<img id="bus_icon" src="images/bus.png" class="travel_mode" onclick="change_travel_mode('bus_icon', google.maps.TravelMode.TRANSIT);" />
						<button style="width:100px;height:37px;font-size:17px;margin-top:17px;margin-left:5px;" onclick="navigate_route();">Navigate</div>

					</div>

				</div>
			</div>
		</div>

		
		
		<div style="clear:both;"></div>
		<hr/>
		<div id="info_panel"></div>
	
	
	</body>
</html>