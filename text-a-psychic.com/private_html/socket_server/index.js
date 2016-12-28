const PORT = 3001;
var fs = require('fs');

var app = require('https').createServer({
	cert: fs.readFileSync('server.cert').toString(),
    key: fs.readFileSync('server.key').toString(),
    NPNProtocols: ['http/2.0', 'spdy', 'http/1.1', 'http/1.0']
})
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