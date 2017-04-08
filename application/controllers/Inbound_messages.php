<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'libraries/REST_Controller.php');

class Inbound_messages extends REST_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */


	// public function index()
	// {	
	// 	$data['page_title'] = 'Bulletin board';
	// 	$this->load->view('template/header.php', $data);
	// 	$this->load->view('bulletin_board.php');
	// 	$this->load->view('template/footer.php');
	// }


	public function all_get() {
		$errors = [];
		$success = true;
		
		if (count($errors)) {
            $success = false;
        }

        $data = $this->Inbound_message_model->all([
	        'where' => [
	            'status'	=> [
	            	Inbound_message_model::STATUS_AVAILABLE,
	            	Inbound_message_model::STATUS_PENDING
	            ]
	        ]
        ]);

        return $this->response(compact('data', 'success', 'errors'));
	}


	public function resolved_messages_get($psychicId = null) {
		$errors = [];
		$success = true;
		$extraFilters = [];
		
		if ($psychicId) {
			$extraFilters = ['responded_by' => $psychicId];
		}

		if (count($errors)) {
            $success = false;
        }

        $data = $this->Inbound_message_model->all([
	        'where' => [
	            'status'	=> [
	            	Inbound_message_model::STATUS_RESOLVED
	            ]
	        ] + $extraFilters,
	        'with'	=> ['outbound_message']
        ]);
        return $this->response(compact('data', 'success', 'errors'));
	}


	public function accept_message_post()
	{
		$errors = [];
		$success = true;
		$data = null;

        $psychicId = $this->post('psychic_id');
        $messageId = $this->post('message_id');
        
        if (!$psychicId) {
        	$errors[] = 'Include a psychic ID';
        }
        if (!$messageId) {
        	$errors[] = 'Include a message ID';
        }

        if (!count($errors)) {
	        // Check if psychic is not reading other messages
	        $heldMessage = $this->Inbound_message_model->first([
	        	'where' => [
	        		'status'		=> Inbound_message_model::STATUS_PENDING,
	        		'responded_by'	=> $psychicId 
	        	]
	        ]);
	        if ($heldMessage) {
	        	$errors[] = 'Psychic is still reading another message';
	        }

	        if (!count($errors)) {
		        // Check if this message is held by someone else
		        $currentMessage = $this->Inbound_message_model->first([
		        	'where' => [
		        		'id'	=> $messageId
		        	]
		        ]);
		        if (!$currentMessage) {
		        	$errors[] = 'Message does not exist';
		        } else if ($currentMessage->status != Inbound_message_model::STATUS_AVAILABLE) {
		        	$errors[] = 'Message is not available';
		        } else {
		        	$data = $currentMessage;
		        }
		    }
	    }

        if (count($errors)) {
            $success = false;
        } else {
        	$data->status = Inbound_message_model::STATUS_PENDING;
        	$data->responded_by = $psychicId;
	        $data->save();
	        $data = $data->to('array');

	        SocketIO_helper::sendEvent('message_accepted', $data);
        }


        return $this->response(compact('data', 'success', 'errors'));
	}

	public function decline_message_post()
	{
		$errors = [];
		$success = false;
		$data = null;

		$messageId = $this->post('message_id');

		if (!$messageId) {
        	$errors[] = 'Include a message ID';
        }

        if (!count($errors)) {
        	$heldMessage = $this->Inbound_message_model->first([
	        	'where' => [
	        		'status'	=> Inbound_message_model::STATUS_PENDING,
	        		'id'		=> $messageId 
	        	]
	        ]);

	        if (!$heldMessage) {
	        	$errors[] = 'Message does not exist or not read';
	        }
        }

        if (!count($errors)) {
        	$heldMessage->status = Inbound_message_model::STATUS_AVAILABLE;
        	$heldMessage->responded_by = 0;
	        if ($heldMessage->save()) {
		        $data = $heldMessage->to('array');

		        SocketIO_helper::sendEvent('message_declined', $data);
		        $success = true;
	        } else {
	        	$errors[] = 'Unable to save';
	        }
        }

        return $this->response(compact('data', 'success', 'errors'));
	}
}
