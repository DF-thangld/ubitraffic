<style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
</style>



<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
    <script>
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var map;
var CenterPoint;
var StartPoint;
var EndPoint;
var Mode;

function initialize() {



  CenterPoint=new google.maps.LatLng(65.059248, 25.466337);


  directionsDisplay = new google.maps.DirectionsRenderer();
  var mapOptions = {
    zoom: 14,
    center: CenterPoint
  }
  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
  directionsDisplay.setMap(map);


  Show_traffic();
}


function Show_traffic() {
	var trafficLayer = new google.maps.TrafficLayer();
	trafficLayer.setMap(map);
}

google.maps.event.addDomListener(window, 'load', initialize);

</script>



<div id="map-canvas"></div>
