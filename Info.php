<!-- 
LOG 2015,03,28
	The problem now: can't connect to the server,or lost connection 
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

	<!-- RadditMQ -->
	<script src="stomp.js" type="text/javascript"></script>

  </head>
	<body>
		<div >
        	<div >
            	<h2>Debug Log</h2>
        	</div>
        	<pre id="debug"></pre>
        </div>
        <div >
        	<div >
            	<h2>Messages</h2>
        	</div>
        	<pre id="messages"></pre>
        </div>
		<div>
			<script language=javascript>
				
				// Create a STOMP-client over WebSocket and set heartbeat
				var client = Stomp.client('ws://bunny.ubioulu.fi:15674/stomp/websocket');
				//var client = Stomp.overWS('ws://bunny.ubioulu.fi:15674/stomp/websocket');
				client.heartbeat.outgoing = 20000;
				client.heartbeat.incoming = 0;

				// this allows to display debug logs directly on the web page
				client.debug = function(str) {
					$('#debug').append(str + '\n');
				};

				// Operations performed on connect...
				var on_connect = function() {
					
					// Subscribe to an exchange "example" for a topic "nothing.special"
					// Pass in also a callback-function where messages are handled

					subId = client.subscribe('/exchange/example/nothing.special', handleMessage);
					
					console.log('connected');
					
					// Send message to the channel (without any headers)
					var data = 'Hello World!';
					client.send('/exchange/example/nothing.special', {}, data);

					
				};

				var handleMessage = function(d) {
					// Callback-function where received messages can be handled
					$('#messages').append(d.body + '\n');
					document.write('#messages');
				};

				// Operations performed on error...
				var on_error =  function(error) {
					console.log(error);
				};

				// Connect to the broker and pass in callbacks for operations performed on connect and on error.
				var user = 'ubitraffic';
				var pass = '2iUn1oX3q4v35rP';
				var virtualHost = 'vm.node0002.ubioulu.fi';
				//var virtualHost = 'virtualHost';
				client.connect(user, pass, on_connect, on_error, virtualHost);
				// the client is notified when it is connected to the server.
				/*client.connect('ubitraffic','2iUn1oX3q4v35rP', function(frame) {
					client.debug("connected to Stomp");
					client.subscribe('/exchange/example/nothing.special', function(message) {
						$("#messages").append("<p>" + message.body + "</p>\n");
					});
				});*/
				

				//sned new text
				client.send('/exchange/example/nothing.special', {}, 'new message');
				
			</script>
		</div>
		<!-- set the width and heighth -->
	    <script language=javascript>
			var map=document.getElementById("info");
			map.style.height=screen.height*0.45 + "px";
			map.style.width=screen.width*0.50+ "px";
		</script>
	</body>
</html>