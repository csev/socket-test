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

// https://www.websocket.org/echo.html
?>
  <h2>WebSocket Echo Test</h2>

<a href="test2.php">Interactive Test</a>

<div id="output"></div>

<?php
$OUTPUT->footerStart();
?>
 
  <script language="javascript" type="text/javascript">
_TSUGI_WEB_SOCKET_FALLBACK = '<?= $socket_api ?>';
  </script>

  <script language="javascript" type="text/javascript" src="tws.js"></script>

  <script language="javascript" type="text/javascript">

  var wsUri = "wss://echo.websocket.org/";
  var output;

  function init()
  {
    output = document.getElementById("output");
    testWebSocket();
  }

  function testWebSocket()
  {
    websocket = new TsugiWebSocket(wsUri);
    // websocket = new WebSocket(wsUri);
    websocket.onopen = function(evt) { onOpen(evt) };
    websocket.onclose = function(evt) { onClose(evt) };
    websocket.onmessage = function(evt) { onMessage(evt) };
    websocket.onerror = function(evt) { onError(evt) };
  }

  function onOpen(evt)
  {
    writeToScreen("Straight line Test CONNECTED");
    doSend("WebSocket "+Math.random());
  }

  function onClose(evt)
  {
    writeToScreen("Straight line Test DISCONNECTED");
  }

  function onMessage(evt)
  {
    writeToScreen('<span style="color: blue;">RESPONSE: ' + evt.data+'</span>');
    websocket.close();
  }

  function onError(evt)
  {
    writeToScreen('<span style="color: red;">ERROR:</span> ' + evt.data);
  }

  function doSend(message)
  {
    writeToScreen("SENT: " + message);
    websocket.send(message);
  }

  function writeToScreen(message)
  {
    var pre = document.createElement("p");
    pre.style.wordWrap = "break-word";
    pre.innerHTML = message;
    output.appendChild(pre);
  }

  window.addEventListener("load", init, false);

  </script>

<?php
$OUTPUT->footerEnd();

