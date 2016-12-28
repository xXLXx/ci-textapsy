<?php

class Base_model extends CI_Model {

	public $tableName = '';

    protected $_attrs = [];

    public function __construct($data = [])
    {   
    	foreach ($data as $key => $value) {
            $this->{$key} = $value;
    	}
        // $this->db->set($data);

        parent::__construct();
    }

    public function __set($name, $value)
    {
        if (!property_exists(__CLASS__, $name)) {
            if (!isset($this->_attrs)) {
                $this->_attrs = [];
            }
            $this->_attrs[$name] = $value;
        } else {
            $this->{$name} = $value;
        }
    }

    public function __isset($name)
    {
        if (isset($this->_attrs)) {
            if (isset($this->_attrs[$name])) {
                return true;
            }
        }

        return false;
    }

    public function __get($name)
    {
        if (!property_exists(__CLASS__, $name)) {
            if (isset($this->_attrs)) {
                if (isset($this->_attrs[$name])) {
                    return $this->_attrs[$name];
                }
            }
        }

        $CI =& get_instance();
        return isset($CI->$name) ? $CI->$name : null;
    }

    public function table()
    {
    	if ($this->tableName) {
    		return $this->tableName;
    	}

    	return plural(strtolower(str_replace('_model', '', get_class($this))));
    }

    public function all($options = [])
    {
    	$defaultOptions = [
    		'where' => [],
    		'limit' => null
    	];
    	$options = array_merge($defaultOptions, $options);

        foreach ($options['where'] as $key => $value) {
            if (is_array($value)) {
                $this->db->group_start();
                if ($key == 'or_where') {
                    foreach ($value as $orKey => $orValue) {
                        $this->db->or_where($orKey, $orValue);
                    }
                } else {
                    $this->db->where_in($key, $value);
                }
                $this->db->group_end();
            } else {
                $this->db->where($key, $value);
            }
        }
        $this->db->limit($options['limit']);

        return $this->db->get($this->table())->result();
    }

    public function first($options = [])
    {
    	$defaultOptions = [
    		'where' => []
    	];
    	$options = array_merge($defaultOptions, $options);

    	$result = $this->all([
    		'where' => $options['where'],
    		'limit' => 1
    	]);

    	foreach ($result as $row) {
    		return new static($row);
    		break;
    	}

    	return null;
    }

    public function save()
    {
        $success = false;

        if (empty($this->id)) {
            // var_dump($this->db); exit;
            $success = $this->db->insert($this->table(), $this->_attrs);
    		$this->id = $this->db->insert_id();
    	} else {
    		$success = $this->db->update($this->table(), $this->_attrs, ['id' => $this->id]);
    	}

        return $success;
    }

    public function update()
    {

    }

    public function delete()
    {
        $success = false;

        if (isset($this->id)) {
            $this->db->where('id', $this->id);
            $this->db->delete($this->table());
        }

        return $success;
    }

    public function to($type = 'array')
    {
        if ($type == 'array') {
            return $this->_attrs;
        }
    }
}