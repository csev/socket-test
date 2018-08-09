
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

Leave this running - it logs activity of opening, closing, and messaging
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

WebSockets and frameworks like SocketIO that build atop WebSocket connections
allow for a very rich way of developing multi-browser low-latency interactions.

Tsugi cannot make that rich fabric available in a reliable way, so
we provide a simple, generic service that works across all tools reliably
that does not compromise the integrity or memory footprint of the socket server.
So Tsugi only provides a single broadcast notification service.  All the browsers
in a particular link in a particular course have a secure and isolated space.

The application can make "rooms" within that space using whatever room naming convention
it likes.  The rooms are isolated by room name through security-by-obscurity.  To
get a notification socket, the pplication makes the following call in JavaScript
when it is loaded:

    var global_web_socket = tsugiNotifySocket("place_42");

This will return false if web sockets are not enabled on the Tsugi server.  But
you won't know that the web socket can be used until the `onopen()` method
is called or the `readyState` attribute is OPEN.  A failure to open 
might be due to bad token or missing server.

See the source code in `index.php` to see how to open and use or handle falures with
the Tsugi notification sockets.



