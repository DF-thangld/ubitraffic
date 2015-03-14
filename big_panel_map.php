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
	
	<script src="javascript/ubitraffic_menu.js" type="text/javascript"></script>
	
	<style>
		document
		{
			font-size:10.5px;
		}
		.button
		{
			width:80px;
			font-size:10.5px;
			float:left;
		}
		.button img
		{
			width:45px;
			height:45px;
		}
		.text_box
		{
			width:100px;
			height:20px;
		}
		.travel_mode
		{
			height:30px;
			margin-top:17px;
			margin-left:10px;
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
	
	var directionsDisplay;
	var directionsService = new google.maps.DirectionsService();
	var map;
	var oulu = new google.maps.LatLng(65.0123600, 25.4681600);
	var orig = new google.maps.LatLng(65.059248, 25.466337);
	var dest = new google.maps.LatLng(65.010786, 25.469942);
	var txtInfo = '';
	
	function reset()
	{
		//reset menu
		$('#'+sub_menu_opening).css('display', 'none');
		$('#main_menu').css('display', 'none');
		$('#show_menu').css('display', 'inline');
		
		//reset variables
		sub_menu_opening = '';
		main_menu_opening = false;
		travel_mode_map = google.maps.TravelMode.WALKING;
		travel_mode_link = 'walking';
		origin_place = screen_address;
		destination_place = 'torikatu 9';
		txtInfo = '';		
		
		//reset navigation form value
		$("#walking_icon").attr('class', 'travel_mode chosen_mode');
		$("#bicycle_icon").attr('class', 'travel_mode');
		$("#bus_icon").attr('class', 'travel_mode');
		$("#start_place").val(origin_place);
		$("#destination").val(destination_place);
		
	}

    $(function(){
        //initMap();
		
		
		directionsDisplay = new google.maps.DirectionsRenderer();
		var mapOptions = {
		  center: oulu,
		  zoom: 16,
		  disableDefaultUI: true
		};
		map = new google.maps.Map(document.getElementById('map_panel'), mapOptions);
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
		
		menu(map);
		google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
			reset();
			$("#start_place").keyboard();
			$("#destination").keyboard();
			google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
				
			});
		});
		
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
		
		origin_place = $("#start_place").val();
		destination_place = $("#destination").val();
		
		var request = {
			origin: origin_place + ",oulu, finland",
			destination: destination_place + ",oulu, finland",
			travelMode: travel_mode_map
		};
	  
		directionsService.route(request, function(response, status) {
			if (status == google.maps.DirectionsStatus.OK) 
			{
				directionsDisplay.setDirections(response);
				console.log(response);
				txtInfo = '';
				if (travel_mode_map===google.maps.TravelMode.WALKING)
				{
					txtInfo += "";
					txtInfo += "<div><center><h2>Walking Route</h2></center></div>";
					txtInfo += "<div><b>From:</b> " + response.routes[0].legs[0].start_address + "</div>";
					txtInfo += "<div><b>To:</b> " + response.routes[0].legs[0].end_address + "</div>";
					txtInfo += "<div><b>Steps:</b></div>";
					$.each(response.routes[0].legs[0].steps, function( index, step )
					{
						txtInfo += "	<div>- " + step.instructions + " (" + step.distance.text + " - " + step.duration.text + ")</div>";
					});
				}
				$("#info_panel").html(txtInfo);
			}
		});
	}
	
    </script>

  </head>
	
    
		

	<body>
		<div style="">
			
			<div style="float:left;border:10px solid;border-color: #2a3333;border-radius:25px; width:480px;height:270px;padding:20px;overflow:scroll;" id="info_panel">
				<div style="font-size:15px;"><center><h2>Walking Route</h2></center></div>
				<div><b>From:</b> Torikatu 9, 90100 Oulu, Finland</div>
				<div><b>To:</b> Yliopistokatu 12, 90570 Oulu, Finland</div>
				<div style=""><b>Steps:</b></div>
					<div>- Head <b>south</b> on <b>Yliopistokatu</b> (0.1 km - 2 mins)</div>
					<div>- Slight <b>left</b> to stay on <b>Yliopistokatu</b> (48 m - 1 min)</div>
					<div>- Turn <b>left</b> onto <b>Linnanmaantie</b> (0.2 km - 2 mins)</div>
					<div>- Turn <b>right</b> onto <b>Alakyläntie</b> (0.5 km - 6 mins)</div>
					<div>- Slight <b>right</b> to stay on <b>Alakyläntie</b> (0.7 km - 12 mins)</div>
					<div>- Head <b>south</b> on <b>Yliopistokatutghjnhtrefghfdsefdgvbnfdfgvbhgfrwfghbngfrdn</b> (0.1 km - 2 mins)</div>
					<div>- Head <b>south</b> on <b>Yliopistokatu</b> (0.1 km - 2 mins)</div>
					<div>- Head <b>south</b> on <b>Yliopistokatu</b> (0.1 km - 2 mins)</div>
					<div>- Head <b>south</b> on <b>Yliopistokatu</b> (0.1 km - 2 mins)</div>
					<div>- Head <b>south</b> on <b>Yliopistokatu</b> (0.1 km - 2 mins)</div>
					<div>- Head <b>south</b> on <b>Yliopistokatu</b> (0.1 km - 2 mins)</div>
					<div>- Head <b>south</b> on <b>Yliopistokatu</b> (0.1 km - 2 mins)</div>
					<div>- Head <b>south</b> on <b>Yliopistokatu</b> (0.1 km - 2 mins)</div>
					<div>- Head <b>south</b> on <b>Yliopistokatu</b> (0.1 km - 2 mins)</div>
					<div>- Head <b>south</b> on <b>Yliopistokatu</b> (0.1 km - 2 mins)</div>
					<div>- Head <b>south</b> on <b>Yliopistokatu</b> (0.1 km - 2 mins)</div>
			</div>
			
			<div style="float:left;border:10px solid;border-color: #2a3333;border-radius:25px;width:480px;height:540px;" >
				<div style="margin:5px;width:470px;height:530px;" id="map_panel">
					
				</div>
			
			</div>
		</div>
	</body>
</html>