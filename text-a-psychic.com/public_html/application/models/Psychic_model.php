<?php

class Psychic_model extends Base_model {

	public $id;
	public $name;

    public function __get($name)
    {
    	switch ($name) {
    		case 'displayName':
    			return 'yeah';
    		
    		default:
    			return parent::__get($name);
    	}
    }
}