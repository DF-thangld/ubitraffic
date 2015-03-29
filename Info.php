<!-- LOG
    Instruction of the RabbitMQ  2015.03.29 Pingjiang

    Each client has same function: send and receive messages.
    This webpage used to receive the messages and display the information.
  You can use the function like that:
  client.connect(login, passcode, function(frame) {
    client.subscribe(destination, function(message) {
      if(message.headers.type!='keep_alive')
      {
        //write your code here

        //tell whether is your message by checking the 'type' value in headers, 
        //for example your message type is 'abc' 
        if(message.headers.type!='abc')
        {
          //how to process
          //the data is in message.body
          //for example you want to display the data on the screen
          $("#messages").append("<p>" + message.body + "</p>\n");
        }
        

      }
    });
  });

  To send the message, you can use the function in default.php
  here is a brief introduction about how to send message, for exaple the type is "test", the data is "I can use RabbitMQ now.":
  
  client.send(destination, {type:'test'}, 'I can use RabbitMQ now.');



-->

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>MAP INFO</title>
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
    <script>
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
          if(message.headers.type!='keep_alive')// if is not keep connection message
          {

            $("#messages").append("<p>" + message.body + "</p>\n");//just for debug

          }
        });
      });

      //This function is Very Important. Can not be removed.
      //The RabbitMQ server will disconnect in less that 3 second.
      //This function send message to keep connecting.
      setInterval(function(){
        client.send(destination, {type:'keep_alive'}, 'client is alive');

      },1000);
    });
    </script>

  </body>
</html>
