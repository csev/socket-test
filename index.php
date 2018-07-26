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

?>
  <h2>WebSocket Notify Test</h2>

<p>This does not send back to the originating client so two clients are needed to test.
Also there is no message history kept.  Notifications are given to active sockets.</p>

<div id="output"></div>

<form action="#" id="messageForm" style="display:none">
  <input type="text" name="message" placeholder="Message...">
  <input type="submit" value="Send">
</form>

<?php
$OUTPUT->footerStart();
?>

<script>


  function writeToScreen(message)
  {
    var pre = document.createElement("p");
    pre.style.wordWrap = "break-word";
    pre.innerHTML = message;
    output.appendChild(pre);
  }

global_web_socket = new WebSocket('ws://localhost:2021/notify?xyzzy=42&room=14');

global_web_socket.onclose = function(evt) {
    if ( evt.code == 1006 ) {
        writeToScreen('Websocket server is not available');
    } else if ( evt.code ) {
        writeToScreen('Websocket server cannot be used: '+evt.code);
    } else {
        writeToScreen('Websocket server cannot be used');
    }
    writeToScreen(global_web_socket.url);
}

global_web_socket.onopen = function(evt) {
    $("#messageForm").show();
    console.log('Opened!!');
}

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
  console.log('Sending',message);
  global_web_socket.send(message);
  form.find( "input[name='message']" ).val('');
});
</script>

<?php
$OUTPUT->footerEnd();

