<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use ElephantIO\Client as ElephantClient;
use ElephantIO\Engine\SocketIO\Version1X as SocketIO;

class SocketIO_helper {

    public static function sendEvent($event, $data)
    {
    	$CI =& get_instance();

        $client = new ElephantClient(new SocketIO($CI->config->item('socket_addr')));
        $client->initialize();
        $client->emit($event, $data);
        $client->close();
    }
}