

function TsugiWebSocket () {
    console.log("Constructing...");
    var self = this;
    this.micro_time = 0;
    this.closed = false;
    this.opened = false;
    this.id = Math.floor(Math.random() * Math.floor(1000));

    // https://stackoverflow.com/questions/5911211/settimeout-inside-javascript-class-using-this
    this.handleTime = function () {
        console.log('Timer called '+self.id);
        if ( self.timeout ) clearTimeout(self.timeout);
        self.timeout = false;

        if ( self.onopen && ! self.opened ) {
            console.log('Calling onopen from timer '+self.id);
            self.opened = true;
            self.onopen();
        }

        $.ajax({
            url: addSession(_TSUGI_WEB_SOCKET_FALLBACK + '?since=' + self.micro_time),
            type: 'GET',
            dataType:'json',
            cache: false,
            success: function(data) {
                console.log('Reveived '+data.length+' messages '+self.id);
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
        console.log('Close called '+self.id);
        if ( self.timeout ) {
            clearTimeout(self.timeout);
            self.timeout = false;
        }
        if ( self.onclose && ! self.closed ) self.onclose();
        self.closed = true;
    }

    this.startUp = function () {
        console.log('Startup called '+self.id);
        // Make sure we don't get old messages so catch us up.
        $.ajax({
            url: addSession(_TSUGI_WEB_SOCKET_FALLBACK + '?since=' + self.micro_time),
            type: 'GET',
            dataType:'json',
            cache: false,
            success: function(data) {
                console.log('Startup got '+data.length+' messages '+self.id);
                for(var i=0; i < data.length; i++ ) {
                    var message = data[i];
                    if ( message.micro_time > self.micro_time ) self.micro_time = message.micro_time;
                }
                console.log('Initial microtime',self.micro_time, self.id);
                if ( self.onopen ) {
                    self.opened = true;
                    console.log('Calling onopen from startup ', self.id);
                    self.onopen()
                }
                if ( self.closed ) return;
                console.log('Setting timer',self.id);
                self.timeout = setTimeout(self.handleTime, 3000);
            }
        });
    }
    this.send = function (message) {
        console.log(self.id, 'Sending...', message);
        // Turn off the time
        if ( this.timeout ) clearTimeout(this.timeout);
        this.timeout = false;

        // Send the data using post and immediately get new messages when post completes
        var posting = $.post( addSession(_TSUGI_WEB_SOCKET_FALLBACK), { message: message } );
        posting.done(function( data ) {
            console.log('Post success', self.id);
            self.handleTime();
        });
    }

    this.startUp();
}
