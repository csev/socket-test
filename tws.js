

function TsugiWebSocket () {
    console.log("Constructing...");
    var self = this;
    this.micro_time = 0;
    this.closed = false;

    // https://stackoverflow.com/questions/5911211/settimeout-inside-javascript-class-using-this
    this.handleTime = function () {
        console.log('Timer called');
        if ( self.timeout ) clearTimeout(self.timeout);
        self.timeout = false;

        $.ajax({
            url: addSession(_TSUGI_WEB_SOCKET_FALLBACK + '?since=' + self.micro_time),
            type: 'GET',
            dataType:'json',
            cache: false,
            success: function(data) {
                console.log('Success');
                console.log(data);
                for(var i=0; i < data.length; i++ ) {
                    var message = data[i];
                    console.log(message);
                    var evt = { data : message.message};
                    if ( self.onmessage && ! self.closed ) self.onmessage(evt);
                    if ( message.micro_time > self.micro_time ) self.micro_time = message.micro_time;
                }
                if ( self.closed ) return;
                self.timeout = setTimeout(self.handleTime, 3000);
            }
        });
    }
    this.close = function() {
        console.log('close called');
        if ( self.timeout ) {
            clearTimeout(self.timeout);
            self.timeout = false;
            echo('Timer cleared');
        }
        if ( self.onclose && ! self.closed ) self.onclose();
        self.closed = true;
    }

    this.startUp = function () {
        console.log('Startup called');
        console.log(self);
        // Make sure we don't get old messages so catch us up.
        $.ajax({
            url: addSession(_TSUGI_WEB_SOCKET_FALLBACK + '?since=' + self.micro_time),
            type: 'GET',
            dataType:'json',
            cache: false,
            success: function(data) {
                console.log('Startup data');
                for(var i=0; i < data.length; i++ ) {
                    var message = data[i];
                    if ( message.micro_time > self.micro_time ) self.micro_time = message.micro_time;
                }
                console.log('Initial microtime',self.micro_time);
                if ( self.onopen ) {
                    console.log('Calling onopen');
                    self.onopen(42)
                    console.log('Back from onopen');
                }
                if ( self.closed ) return;
                console.log('Setting timer');
                self.timeout = setTimeout(self.handleTime, 3000);
            }
        });
    }
    this.send = function (message) {
        console.log('Sending...', message);
        // Turn off the time
        if ( this.timeout ) clearTimeout(this.timeout);
        this.timeout = false;

        // Send the data using post and immediately get new messages when post completes
        var posting = $.post( addSession(_TSUGI_WEB_SOCKET_FALLBACK), { message: message } );
        posting.done(function( data ) {
            console.log('Posting done');
            self.handleTime();
        });
    }

    this.startUp();
}
