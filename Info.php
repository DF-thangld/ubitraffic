<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>MAP INFO</title>
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->  
  </head>

  <body>
    
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span6">
            <div class="page-header">
              <h2>Content</h2>
            </div>
            <div id="messages">
            </div>
        </div>
        <div class="span4" style="display:none">
          <div class="page-header">
            <h2>Debug Log</h2>
          </div>
          <pre id="debug"></pre>
        </div>
      </div>
    </div>

    <!-- Scripts placed at the end of the document so the pages load faster -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="stomp.js"></script>
    <script>//<![CDATA[
    $(document).ready(function() {
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
              $("#messages").append("<p>" + message.body + "</p>\n");
            });
          });
    });
    //]]></script>

  </body>
</html>
