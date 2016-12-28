<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use ElephantIO\Client as ElephantClient;
use ElephantIO\Engine\SocketIO\Version1X as SocketIO;

class SocketIO_helper {

    protected static $_socket_addr = 'https://localhost:3001';

    public static function sendEvent($event, $data)
    {
        $client = new ElephantClient(new SocketIO(static::$_socket_addr));
        $client->initialize();
        $client->emit($event, $data);
        $client->close();
    }
}