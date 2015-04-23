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
	<link href="javascript/keyboard_master/css/keyboard.css" rel="stylesheet" />
	<script src="javascript/keyboard_master/js/jquery.keyboard.js" type="text/javascript"></script>
	
	<!-- Google Map -->
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>
	
	<script src="javascript/ubitraffic_traffic_places.js" type="text/javascript"></script>
	<script src="javascript/ubitraffic_menu.js" type="text/javascript"></script>
	<script src="javascript/html2canvas.js" type="text/javascript"></script>

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
	var screen_address = 'Yliopistokatu 12';
	var origin_place = screen_address;
	var destination_place = ''; 
	var markers_list = [];
	var weather_markers = [];
	var camera_markers = [];
	var parking_markers = [];
	
	var directionsDisplay;
	var directionsService = new google.maps.DirectionsService();
	var map;
	var point_of_interest_service;
	var oulu = new google.maps.LatLng(65.0123600, 25.4681600);
	var orig = new google.maps.LatLng(65.059248, 25.466337);
	var dest = new google.maps.LatLng(65.010786, 25.469942);
	var txtInfo = '';
	var email_text = '';
	var file_name = '';

	
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
		destination_place = '';
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
		
		var contentString = '<div id="content">'+
		  '<div id="siteNotice">'+
		  '</div>'+
		  '<h2>UBI-Screen</h2>'+
		  '<div id="bodyContent">'+
		  '<p>Hello, You are here :)</p>'+
		  '</div>'+
		  '</div>';
		var infowindow = new google.maps.InfoWindow({
			content: contentString
		});
		var myLatlng = new google.maps.LatLng(65.057858, 25.468006);
		
		directionsDisplay = new google.maps.DirectionsRenderer();
		var mapOptions = {
		  center: myLatlng,
		  zoom: 13,
		  disableDefaultUI: true
		};
		map = new google.maps.Map(document.getElementById('map_panel'), mapOptions);
		point_of_interest_service = new google.maps.places.PlacesService(map);
		directionsDisplay.setMap(map);
		
		
			
			
		
		var marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			title:"UBI-Screen"
		});
		google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
		});
		
		google.maps.event.addListener(map, 'click', function(event) {
			
			var latitude = event.latLng.lat();
			var longitude = event.latLng.lng();
			
			dest = new google.maps.LatLng(latitude,longitude);
			origin_place = $("#start_place").val();
			
			find_route(origin_place,dest,travel_mode_map);
			
			
    		if(0)//is navigation mode
			{}
			else
			{
				$('#navigation_menu').css('display', 'none');
				$('#bus_timetable_menu').css('display', 'none');
				$('#download_div').css('display', 'none');
				$('#point_of_interest').css('display', 'none');
				$('#traffic_congestion').css('display', 'none');
			}
  		});
		
		menu(map);
		google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
			reset();
			//window.alert(1);
			$("#start_place").keyboard();
			$("#destination").keyboard();
			$("#bus_number").keyboard();
			$("#email_input").keyboard();
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
					//set destination field
					$("#destination").val(response.routes[0].legs[0].end_address);
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
					//set destination field
					$("#destination").val(response.routes[0].legs[0].end_address);
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
					//set destination field
					$("#destination").val(response.routes[0].legs[0].end_address);
					txtInfo += "<div><b>Steps:</b></div>";
					$.each(response.routes[0].legs[0].steps, function( index, step )
					{
						if (step.travel_mode === 'TRANSIT')
							txtInfo += "	<div>- Take bus number <b>" + step.transit.line.short_name + " (" + step.transit.line.name + ")</b> at <b>" + step.transit.arrival_stop.name +"</b>, pass " + step.transit.num_stops + " stops to <b>" + step.transit.arrival_stop.name + "</b></div>";
						else
							txtInfo += "	<div>- " + step.instructions + " (" + step.distance.text + " - " + step.duration.text + ")</div>";
					});
				}
				//$("#info_panel").html(txtInfo);
				email_text = txtInfo;
				RabbitMQ_send("html",txtInfo);
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
				//$("#info_panel").html(txtInfo);
				email_text = txtInfo;
				RabbitMQ_send("html",txtInfo);
			}
		});
	}
	
	//download map to phone
	function downloadToPhone(selection)
	{		
		//reset the divs to get the image of the full map
		reset();
		//get transform value
		var transform=$("#map_panel").css("transform")
		var comp=transform.split(",") //split up the transform matrix
		var mapleft=parseFloat(comp[4]) //get left value
		var maptop=parseFloat(comp[5])  //get top value
		$("#map_panel").css({ //get the map container. not sure if stable
		  "transform":"none",
		  "left":mapleft,
		  "top":maptop,
		})
		
		//convert google map to canvas and save it as image
		html2canvas($('#map_panel'),
		{
		  useCORS: true,
		  onrendered: function(canvas)
		  {
		
			//Insert info text to canvas
			var ctx=canvas.getContext("2d");			
			
			var maxWidth =960;
			var lineHeight = 17;
			var x = (canvas.width - maxWidth) / 2;
			var y = 1080;
			  
			  
			var div = document.createElement("div");
			div.innerHTML = email_text;
			var text = div.textContent; //document.getElementById('info_panel').textContent;

			ctx.font = '12pt Calibri';
			ctx.fillStyle = '#333';

			wrapText(ctx, text, x, y, maxWidth, lineHeight);
			
			//hold the data to save canvas as image
			var screenshot ={};			
			
			screenshot.img = canvas.toDataURL( "image/png" );
			screenshot.data = { 'image' : screenshot.img };
			
			//call savePNG to save the image to /images/png/[name].png
            $.ajax({
                type: "POST",
                url: "savePNG.php",
                data: screenshot.data,
                success : function(data)
                {
					file_name = data;
                    //console.log("screenshot done "+data);
                }
            }).done(function() {
                //console.log("blablabl");
				if(selection == "QR")
				{
					setTimeout(createQR,1000);
					//createQR();
				}
				else if(selection == "Email")
				{
					setTimeout(sendEmail,1000);
					//sendEmail();
				}
            });

			//set transform back
			$("#map_panel").css({
			  left:0,
			  top:0,
			  "transform":transform
			})
		  },
		  height: 2000
		});
		
		
		
		
	}
	
	//create qr code
	function createQR()
	{
		//use google api chart for QR code
		var source = "https://chart.googleapis.com/chart?cht=qr&chl=";
		
		//create url for the image
		var imgUrl = window.location.href.substring(0, window.location.href.lastIndexOf('/'))+"/images/png/"+file_name+".png";
		var src = source+encodeURIComponent(imgUrl)+"&chs=180x180";
		
		//set image to qr div
		$('#QRcode').html("<img src="+src+" style='position:absolute;width:100px;margin:5px;' />");
			
		//create close button for qr code image
		var closeB = document.createElement("a");
		closeB.id = "close_qr";
		closeB.innerHTML = "x";
		closeB.style = "position:absolute;height:17px; width:9px;float:right; right:-10px; top:-10px;cursor:pointer;border:1px solid;border-color: #2a3333;border-radius:15px;display:inline-block; padding: 2px 5px; background: #ccc;";
		$('#QRcode').append(closeB);
			
		//show it to user
		$('#QRcode').css('display', 'inline');
		//$('#download_div').append($image);
		//console.log(""+image.src);	

		google.maps.event.addDomListener($('#close_qr').get(0), 'click', function() {
		$('#QRcode').css('display', 'none');
		});
	}
	
	//create email
	function sendEmail()
	{
		//get e-mail address from input field
		var emailaddr = $("#email_input").val();		
		var emaildata = "dest="+emailaddr+"&fname="+file_name;
		
		//Send e-mail
		console.log("Sending email");
		$.ajax({
                type: "POST",
                url: "sendMail.php",
                data: emaildata,
                success : function(data)
                {
                    console.log(data);
                }
            }).done(function() {
                //$('body').html(data);
            });
		
	}
	
	
	//wrap text to fit to canvas
	function wrapText(context, text, x, y, maxWidth, lineHeight) {
		var words = text.split(/([)])/);
        var line = '';

        for(var n = 0; n < words.length; n++) {
			var testLine = line + words[n] + ' ';
			var metrics = context.measureText(testLine);
			var testWidth = metrics.width;
			if (testWidth > maxWidth && n > 0) {
				context.fillText(line, x, y);
				line = words[n] + ' ';
				y += lineHeight;
			}
			else {
				line = testLine;
			}
        }
        context.fillText(line, x, y);
    }
    </script>
  </head>
	<body>
		<div style="">
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