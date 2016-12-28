<?php

class Outbound_message_model extends Base_model {
	
	public $id;
	public $ref_message_id;
	public $sender_id;
	public $message;
	public $sent_at;
}