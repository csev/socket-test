
Web Socket Test
---------------

Demonstrate / test the Tsugi Web Socket Notification support.

Running This Application
------------------------

This applications requires that the Tsugi hosting environment has a web
sockets enabled:

    $CFG->websocket_secret = 'opensource';
    $CFG->websocket_url = 'ws://localhost:2021';

These need to point to a working socket server. You can start a local
socket server for your local Tsugi as follows:

    cd tsugi/admin
    php rachet.php

Leave`rachet` running - it logs activity of opening, closing, and messaging
for the web sockets it is managing.

Then you need to open the `socket-test` tool in 2 or three windows with each windows
running as a different user (i.e. one windows is Jane, another is Sue and the third
is Ed).   The rachet server only notifies sockets that are connected at 
the exact moment a message is received.  There is no history or replay
capability.

Once this is working, first notice how fast notifications happen - arrange the windows
so you can see all of them as well as watch the output of the `rachet` process.
Another fun experiment is to watch all the windows and abort the `rachet` process
and how quickly the browsers notice the end of the sockets.  If you restart the
rachet process you will need to refresh the tools to get them to re-connect their
sockets.

Browser Notification Service
----------------------------

You can get more information on how Tsugi implements WebSockets at

    http://do1.dr-chuck.com/tsugi/phpdoc/Tsugi/Core/WebSocket.html

