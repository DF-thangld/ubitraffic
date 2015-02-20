<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple markers</title>
	
    <style>
		.gmap3{
			margin: 20px auto;
			
			width: 500px;
			height: 250px;
		}
		.button
		{
			width:100px;
			float:left;
		}
		
    </style>
    
	<link href="javascript/facebox/src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
	<script src="javascript/jquery.js" type="text/javascript"></script>
	<script src="javascript/facebox/facebox.js" type="text/javascript"></script>
	<script type="text/javascript" src="javascript/gmap3/gmap3.js"></script>
	
	<script type="text/javascript">
	var showMenu = null, fullMenu = null;
	function ShowMenuButton(map) 
	{
		var $container = $(document.createElement('DIV')),
			$outer = $(document.createElement('DIV')),
			$inner = $(document.createElement('DIV'));
        
		$inner.addClass("inner").html("<div style='margin-bottom:50px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:73px;height:68px;'><img style='' src='images/open_button.png' alt='Smiley face'/></div>");
		$container.addClass("outer").attr('title', "Click to set the map to Home");
		$container.attr("id", "showMenu");
      
		$container.append( $outer.append( $inner ) );
      
		google.maps.event.addDomListener($outer.get(0), 'click', function() {
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
		$container.attr("style", "display:none;margin-left:-77px;margin-bottom:50px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:800px;height:68px;");
		
		
		var $closeButton = $(document.createElement('DIV'));
		$closeButton.attr("class", "button");
		$closeButton.attr("style", "margin-left:3px");
		$closeButton.html("<img src='images/close_button.png' />");
		google.maps.event.addDomListener($closeButton.get(0), 'click', function() {
			$('#fullMenu').fadeOut();
			$('#showMenu').fadeIn();
		});
		
		var $navigationButton = $(document.createElement('DIV'));
		$navigationButton.attr("class", "button");
		$navigationButton.html("<center><img src='images/navigation_button.png' /><div>Navigation</div></center>");
		google.maps.event.addDomListener($navigationButton.get(0), 'click', function() {
			$('#navigationForm').fadeIn();
		});
		
		var $busTimetableButton = $(document.createElement('DIV'));
		$busTimetableButton.attr("class", "button");
		$busTimetableButton.html("<center><img src='images/bus_timetable_button.png' /><div>Timetable</div></center>");
		google.maps.event.addDomListener($busTimetableButton.get(0), 'click', function() {
			//TODO
		});
		
		var $placeButton = $(document.createElement('DIV'));
		$placeButton.attr("class", "button");
		$placeButton.html("<center><img src='images/place_button.png' /><div>Place</div></center>");
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
		map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push($container.get(0));
	}
	
	function Navigation(map)
	{
		var $container = $(document.createElement('DIV'));
		$container.attr("id", "navigationForm");
		$container.attr("style", "display:none;margin-left:-77px;margin-bottom:150px;border:2px solid;border-color: #2a3333;border-radius: 6px;background-color: white;width:800px;height:200px;");
		map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push($container.get(0));
	}
	
    $(function(){
        $("#test").gmap3({
			marker:{
				values:[
					{latLng:[65.013130, 25.476192], data:"Paris !"},
					{latLng:[65.013130, 25.476292], data:"Paris !"},
					{latLng:[65.013130, 25.476392], data:"Paris !"},
					{address:"Isokatu 8, Oulu, Finland", data:"Poitiers : great city !"},
					{address:"Isokatu 15, Oulu, Finland", data:"Perpignan ! GO USAP !",options:{icon: "http://maps.google.com/mapfiles/marker_green.png"}}
				],
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
					fullMenu = new Menu(map);
					new Navigation(map);
				}
			}
        });
        
    });
    </script>

  </head>
	
    
	<div style="border:10px solid;border-color: #2a3333;border-radius: 25px;width:960px;height:486px" id="test"></div>
  <body>

  </body>
</html>