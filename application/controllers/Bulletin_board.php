<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bulletin_board extends MY_Controller {

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


	public function index()
	{	
		$data['title'] = 'Bulletin board';
		$data['current_login'] = Auth::me();
		$this->load->view('template/header.php', $data);
		$this->load->view('bulletin_board.php');
		$this->load->view('template/footer.php');
	}

}
