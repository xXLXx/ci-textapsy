const PORT = 3000;

var app = require('http').createServer()
var io = require('socket.io')(app);
var fs = require('fs');

app.listen(PORT);

io.on('connection', function (socket) {
    console.log('listening on port ' + PORT);
    socket
    	.on('message_recieved', function (data) {
	        io.emit('message_recieved', data);
	    })
	    .on('message_accepted', function (data) {
	    	io.emit('message_accepted', data);
	    })
	    .on('message_declined', function (data) {
	    	io.emit('message_declined', data);
	    })
	    .on('message_resolved', function (data) {
	    	io.emit('message_resolved', data);
	    });
});