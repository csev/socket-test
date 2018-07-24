<?php
// https://github.com/tsugiproject/trophy
require_once "../config.php";

use \Tsugi\Util\U;
use \Tsugi\Core\LTIX;

// Handle all forms of launch
$LTI = LTIX::requireData();

// Render view
$OUTPUT->header();
?>
  <title>WebSocket Test</title>
<?php
$OUTPUT->bodyStart();
$OUTPUT->topNav();

$OUTPUT->welcomeUserCourse();

$socket_api = $CFG->wwwroot . '/api/socket';
$socket = U::addSession($socket_api);

// https://www.websocket.org/echo.html

?>
  <h2>WebSocket Interactive Test</h2>

<a href="index.php">Echo Test</a>


<div id="output"></div>

<form action="#" id="messageForm">
  <input type="text" name="message" placeholder="Message...">
  <input type="submit" value="Send">
  <input type="submit" value="Clear" onclick="$('#output').erase(); return false;">
</form>

<?php
$OUTPUT->footerStart();
?>
 
  <script language="javascript" type="text/javascript">
_TSUGI.web_socket_fallback = '<?= $socket_api ?>';
  </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.4/socket.io.js"></script>


<script>

// Create SocketIO instance, connect
var socket = new io.Socket('localhost',{
    port: 2020
});
socket.connect(); 

// Add a connect listener
socket.on('connect',function() {
    console.log('Client has connected to the server!');
});
// Add a connect listener
socket.on('message',function(data) {
    console.log('Received a message from the server!',data);
});
// Add a disconnect listener
socket.on('disconnect',function() {
    console.log('The client has disconnected!');
});

// Sends a message to the server via sockets
function sendMessageToServer(message) {
    socket.send(message);
}

sendMessageToServer('Yada 42');

</script>

<?php
$OUTPUT->footerEnd();

