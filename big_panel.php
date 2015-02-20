<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple markers</title>
	
    <style>
		.gmap3{
			margin: 20px auto;
			border: 1px dashed #C0C0C0;
			width: 500px;
			height: 250px;
		}
		
    </style>
    
	<link href="javascript/facebox/src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
	<script src="javascript/jquery.js" type="text/javascript"></script>
	<script src="javascript/facebox/facebox.js" type="text/javascript"></script>
	<script type="text/javascript" src="javascript/gmap3/gmap3.js"></script>
	
	<script type="text/javascript">
	var chicago = new google.maps.LatLng(65.013130, 25.476192);
	function HomeControl(map) {
		var $container = $(document.createElement('DIV')),
			$outer = $(document.createElement('DIV')),
			$inner = $(document.createElement('DIV'));
        
		$inner.addClass("inner").html("<img style='margin-bottom:50px;' src='images/open_button.png'/>");
		$outer.addClass("outer").attr('title', "Click to set the map to Home");
		$container.attr("id", "homeControl");
      
		$container.append( $outer.append( $inner ) );
      
		google.maps.event.addDomListener($outer.get(0), 'click', function() {
			map.setCenter(chicago)
		});
      
		this.index = 1;
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
					new HomeControl(map);
				}
			}
        });
        
    });
    </script>

  </head>
	
    
	<div style="border:10px solid;border-color: #2a3333;border-radius: 25px;width:100px;width:960px;height:486px" id="test"></div>
  <body>

  </body>
</html>