<!-- 
LOG 2015.04.05
Change UI with new slide button
Chnage teh icon to meet the requirement, but some I can't find how to change the size
the minium size of touchable icon is 32*32, so if you find some icon is too small, please enlarge it
Change the black and white icon to color one, not all
There is a problem that the innerContainner (a div) in menu.js don't have hide function, that need to be finished

LOG 2015.03.26
Our application layout is A, which has two kind of resolution: 1920*1080 and 1920*1200 
LayoutA: A1 50% (W) x 90% (H), A2 50% (W) x 45% (H).
So I change the div's width and heighth to dynamic value.
I hind the left side bar. Because we need two URLs , the map is one, the left panen is another
indenpendent URLs, I will implement this changes soon.
-->

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Oulu Map</title>
	
    
	
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
	
	<script src="javascript/ubitraffic_traffic_places.js" type="text/javascript"></script>
	<script src="javascript/ubitraffic_menu.js" type="text/javascript"></script>

	<link rel="stylesheet" type="text/css" href="css/ModernBlue.css" />
    <link rel="stylesheet" type="text/css" href="css/style4.css" />
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css' />
	
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
	var markers_list = [];
	var weather_markers = [];
	var camera_markers = [];
	var parking_markers = [];
	
	var directionsDisplay;
	var directionsService = new google.maps.DirectionsService();
	var map;
	var oulu = new google.maps.LatLng(65.0123600, 25.4681600);
	var orig = new google.maps.LatLng(65.059248, 25.466337);
	var dest = new google.maps.LatLng(65.010786, 25.469942);
	var txtInfo = '';

	
	function reset(){
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
	
	function change_sub_menu(new_menu){
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
	
	function change_travel_mode(travel_mode_icon, travel_mode){
		
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
	
	function find_route(start_point, end_point, travel_mode){
		var request = {
			origin: start_point,
			destination: end_point,
			travelMode: travel_mode
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
				else if (travel_mode_map===google.maps.TravelMode.BICYCLING)
				{
					txtInfo += "";
					txtInfo += "<div><center><h2>Bicycle Route</h2></center></div>";
					txtInfo += "<div><b>From:</b> " + response.routes[0].legs[0].start_address + "</div>";
					txtInfo += "<div><b>To:</b> " + response.routes[0].legs[0].end_address + "</div>";
					txtInfo += "<div><b>Steps:</b></div>";
					$.each(response.routes[0].legs[0].steps, function( index, step )
					{
						txtInfo += "	<div>- " + step.instructions + " (" + step.distance.text + " - " + step.duration.text + ")</div>";
					});
				}
				else if (travel_mode_map===google.maps.TravelMode.TRANSIT)
				{
					txtInfo += "";
					txtInfo += "<div><center><h2>Bus Route</h2></center></div>";
					txtInfo += "<div><b>From:</b> " + response.routes[0].legs[0].start_address + "</div>";
					txtInfo += "<div><b>To:</b> " + response.routes[0].legs[0].end_address + "</div>";
					txtInfo += "<div><b>Steps:</b></div>";
					$.each(response.routes[0].legs[0].steps, function( index, step )
					{
						if (step.travel_mode === 'TRANSIT')
							txtInfo += "	<div>- Take bus number <b>" + step.transit.line.short_name + " (" + step.transit.line.name + ")</b> at <b>" + step.transit.arrival_stop.name +"</b>, pass " + step.transit.num_stops + " stops to <b>" + step.transit.arrival_stop.name + "</b></div>";
						else
							txtInfo += "	<div>- " + step.instructions + " (" + step.distance.text + " - " + step.duration.text + ")</div>";
					});
				}
				$("#info_panel").html(txtInfo);
			}
		});
		
	}
	
	function navigate_route(){
		
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
				else if (travel_mode_map===google.maps.TravelMode.BICYCLING)
				{
					txtInfo += "";
					txtInfo += "<div><center><h2>Bicycle Route</h2></center></div>";
					txtInfo += "<div><b>From:</b> " + response.routes[0].legs[0].start_address + "</div>";
					txtInfo += "<div><b>To:</b> " + response.routes[0].legs[0].end_address + "</div>";
					txtInfo += "<div><b>Steps:</b></div>";
					$.each(response.routes[0].legs[0].steps, function( index, step )
					{
						txtInfo += "	<div>- " + step.instructions + " (" + step.distance.text + " - " + step.duration.text + ")</div>";
					});
				}
				else if (travel_mode_map===google.maps.TravelMode.TRANSIT)
				{
					txtInfo += "";
					txtInfo += "<div><center><h2>Bus Route</h2></center></div>";
					txtInfo += "<div><b>From:</b> " + response.routes[0].legs[0].start_address + "</div>";
					txtInfo += "<div><b>To:</b> " + response.routes[0].legs[0].end_address + "</div>";
					txtInfo += "<div><b>Steps:</b></div>";
					$.each(response.routes[0].legs[0].steps, function( index, step )
					{
						if (step.travel_mode === 'TRANSIT')
							txtInfo += "	<div>- Take bus number <b>" + step.transit.line.short_name + " (" + step.transit.line.name + ")</b> at <b>" + step.transit.arrival_stop.name +"</b>, pass " + step.transit.num_stops + " stops to <b>" + step.transit.arrival_stop.name + "</b></div>";
						else
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
			<!-- <div style="float:left;border:10px solid;border-color: #2a3333;border-radius:25px; width:480px;height:270px;padding:20px;overflow:scroll;" id="info_panel"></div> -->
			<!-- <div style="float:left;border:10px solid;border-color: #2a3333;border-radius:25px;width:480px;height:540px;" id="background" > -->
			<div id="background" > 
				<div id="connected" style="display:none">
		            <div class="page-header">
		              Send message to Info example
		            </div>
		            <div id="messages">
		            </div>
		            <form class="well form-search" id='send_form'>
		              <input class="input-medium" id='send_form_input' placeholder="Type your message here" class="span6"/>
		              <button class="btn" type="submit">Send</button>
		            </form>
		          </div>
		     	</div>
		     	<div id="wrap_menu" class="button-wrapper" >
					<div href="#" class="a-btn">
						<!-- <div class="a-btn-text" ><font face="Arial Narrow" size="4px">Menu+</font></div> -->
						<div class="a-btn-text" ><h1>Menu</div>
                        <div class="a-btn-slide-text" id= "main_menu" >
                        </div>
						<div class="a-btn-icon-right"><span></span></div>
					</div>
				</div>
				<div id="map_panel"></div>
		</div>

		<!-- set the width and heighth -->
	    <script language=javascript>
			var map=document.getElementById("map_panel");
			map.style.height=screen.height*0.898 + "px";
			map.style.width=screen.width*0.50+ "px";
		</script>
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<script src="stomp.js"></script>
	    <script language=javascript>
	    	var client, destination;
			var url = 'ws://bunny.ubioulu.fi:15674/stomp/websocket';
			var login = 'ubitraffic';
			var passcode = '2iUn1oX3q4v35rP';
			destination = '/exchange/ubitraffic';

			client = Stomp.client(url);

			// this allows to display debug logs directly on the web page
			client.debug = function(str) {
				$("#debug").append(str + "\n");
			};
			// the client is notified when it is connected to the server.
			client.connect(login, passcode, function(frame) {
				client.subscribe(destination, function(message) {
				//call-back function after receive new message can process here
				});
			});
		    $('#send_form').submit(function() {
		    	

	          var text = $('#send_form_input').val();
	          if (text) {
	            RabbitMQ_send('debug', text);
	            $('#send_form_input').val("");
	          }
	          return false;
	        });
	    	function RabbitMQ_send(message_type,message){
	    		client.send(destination, {type:message_type}, message);
			};
		</script>
	</body>
</html>