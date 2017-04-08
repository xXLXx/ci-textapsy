const PORT = 3001;
var fs = require('fs');

var http = 'https';
var certPath = '../../certs/';
if (process.env.ENV == 'dev') {
	certPath = './';
}

var serverParams = {
	cert: fs.readFileSync(certPath + 'server.cert').toString(),
    key: fs.readFileSync(certPath + 'server.key').toString(),
    NPNProtocols: ['http/2.0', 'spdy', 'http/1.1', 'http/1.0']
};
if (process.env.ENV == 'dev') {
	http = 'http';
	serverParams = null;
}

var app = require(http).createServer(serverParams);
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