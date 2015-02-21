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

  /*
  later will move to another part out side initialize(),
  this function initialize() is just for initialize the map.
  in real website, we need to press some button then call the function
  I put it here just for you to test your assignment function
  */

  navigation();

  directionsDisplay = new google.maps.DirectionsRenderer();
  var mapOptions = {
    zoom: 14,
    center: CenterPoint
  }
  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
  directionsDisplay.setMap(map);

  //that is the simplest version, and calcRoute is function to show the route
  //and in real website, we need to press some button then call the function
  //I put it here just for you to test your assignment function
  calcRoute();
}

function navigation() {
  /*this just show the assignment format, it is an example,
    you need to delete them and use your function to change the value of the variables
  */
    //assign the value for the start position;
  StartPoint = new google.maps.LatLng(65.059248, 25.466337);
  //assign the value for the end position;
  EndPoint = new google.maps.LatLng(65.010786, 25.469942);
  //assign the value for the navigation mode, there are 4 mode:"WALKING","DRIVING","BICYCLING" and "TRANSIT"
  Mode= "WALKING";
}
function calcRoute() {
  //var selectedMode = document.getElementById('mode').value;
  var selectedMode = Mode;
  var request = {
      origin: StartPoint,
      destination: EndPoint,
      // Note that Javascript allows us to access the constant
      // using square brackets and a string value as its
      // "property."
      travelMode: google.maps.TravelMode[selectedMode]
  };
  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
    }
  });
}

google.maps.event.addDomListener(window, 'load', initialize);

</script>



<div id="map-canvas"></div>
