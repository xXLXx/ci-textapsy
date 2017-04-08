<?php

class Psychic_model extends Base_model {

	public $id;
    public $username;
    public $fname;
	public $lname;
    public $password;
    public $home_address;
    public $email_address;
    public $paypal_address;
    public $profile_img;
    public $mobile_num;
    public $home_phone;

    public function __get($name)
    {
    	switch ($name) {
    		case 'displayName':
    			return $this->fname." ".$this->lname;
    		
    		default:
    			return parent::__get($name);
    	}
    }
}