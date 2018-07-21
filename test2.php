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
_TSUGI_WEB_SOCKET_FALLBACK = '<?= $socket_api ?>';
  </script>

  <script language="javascript" type="text/javascript" src="tws.js"></script>

<script>


  function writeToScreen(message)
  {
    var pre = document.createElement("p");
    pre.style.wordWrap = "break-word";
    pre.innerHTML = message;
    output.appendChild(pre);
  }

var wsUri = "wss://echo.websocket.org/";
global_web_socket = new TsugiWebSocket();
// global_web_socket = new WebSocket(wsUri);

global_web_socket.onmessage = function(evt) { 
    writeToScreen('<span style="color: blue;">RECEIVE: ' + evt.data+'</span>');
    // Don't close
};

$( "#messageForm" ).submit(function( event ) {
 
  // Stop form from submitting normally
  event.preventDefault();
 
  // Get some values from elements on the page:
  var form = $( this )
  var message = form.find( "input[name='message']" ).val();
  console.log('Message',message);
  global_web_socket.send(message);
});
</script>

<?php
$OUTPUT->footerEnd();

