<?php
// https://github.com/tsugiproject/trophy
require_once "../config.php";

use \Tsugi\Util\U;
use \Tsugi\Core\LTIX;
use Tsugi\Core\WebSocket;

// Handle all forms of launch
$LTI = LTIX::requireData();

// Render view
$OUTPUT->header();
?>
  <title>WebSocket Test</title>
<?php
$OUTPUT->bodyStart();
$OUTPUT->topNav();
?>
<div style="position:fixed; right:10px; top: 10px;">
    <i class="fa fa-link" id="websocket-status" style="color:black;"
     onclick="alert('WebSocket Status');" alt="WebSocket Status"></i>
</div>

  <h2>WebSocket Notify Test</h2>
<?php if (! WebSocket::enabled() ) { ?>
<p>Web sockets are not enabled on this Tsugi server.  You need to set these
values in your <var>config.php</var>:
<pre>
$CFG->websocket_secret = 'opensource';
$CFG->websocket_url = 'ws://localhost:2021';
</pre>
If you are running this on localhost, you will need to also start
the socket server using the following commands in a terminal:
<pre>
cd tsugi/admin
php rachet.php
</pre>
Leave the socket code running in window while you are testing.
</p>
<?php 
    $OUTPUT->footer();
    return;
} ?>
<p>This does not send back to the originating client
so you need to open this application in two windows (i.e. 
one with Jane and one with Sue) to see this actually work.
so two clients are needed to test.
This notifies only those connected at the same time so you must connect
before communicating.
</p>
<pre>
WebSocket: <?= htmlentities($CFG->websocket_url) ?>
</pre>

<div id="output"></div>

<form action="#" id="messageForm">
  <input type="text" size="80" name="message" placeholder="Message...">
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

// Request a socket in room 42 - room can be omitted
global_web_socket = tsugiNotifySocket("place_42");
open_worked = false;

// returns false if there is no socket configured
if ( global_web_socket ) {

    // We ony know if the open worked when this function
    // is called so we show the send form here
    global_web_socket.onopen = function(evt) {
        $("#websocket-status").css('color', 'green');
        writeToScreen('<span style="color: green;">Web socket available</span>');
        open_worked = true;
    }

    // Register close function
    global_web_socket.onclose = function(evt) {
        $("#websocket-status").css('color', 'red');
        if ( open_worked ) {
            writeToScreen('<span style="color:red;">Websocket has closed</span>');
        } else {
            writeToScreen('<span style="color:red;">Websocket open failed</span>');
        }
    }

    global_web_socket.onmessage = function(evt) {
        writeToScreen('<span style="color: blue;">RECEIVE: ' + evt.data+'</span>');
    };
} else {
    writeToScreen('Could not get a notification socket');
}

$( "#messageForm" ).submit(function( event ) {
    // Stop form from submitting normally
    event.preventDefault();

    if ( global_web_socket && global_web_socket.readyState == global_web_socket.OPEN ) {
        var form = $( this )
        var message = form.find( "input[name='message']" ).val();
        global_web_socket.send(message);
        writeToScreen('<span style="color: green;">Message sent</span>');
    } else {
        writeToScreen('<span style="color: red;">Socket is not ready</span>');
    }
    form.find( "input[name='message']" ).val('');
});
</script>

<?php
$OUTPUT->footerEnd();

