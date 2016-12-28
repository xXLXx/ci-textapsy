<?php

class Inbound_message_model extends Base_model {

	const STATUS_AVAILABLE = 0;
	const STATUS_PENDING = 1;
	const STATUS_RESOLVED = 2;

	public static $statuses = [
		'Available',
		'Pending',
		'Resolved'
	];

	// public $id;
	// public $txtnation_msg_id;
	// public $number;
	// public $message;
	// public $request_url;
	// public $network;
	// public $shortcode;
	// public $billing;
	// public $country;
	// public $responded_by;
	// public $status;
	// public $sent_at;
}