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
    		'limit' => null,
            'with'  => []
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

        $this->db->select($this->table() . '.*');

        foreach ($options['with'] as $value) {
            if (isset(static::$_belongs_to[$value])) {
                $relation = static::$_belongs_to[$value];
                $this->db->order_by($this->table() . '.' . $relation['from']);
                
                // I'm not sure why this is structured like this
                $tableName = (new $relation['model'])->table();

                $relation['fields'][] = $relation['from'];
                $relation['fields'][] = $relation['to'];

                foreach ($relation['fields'] as $field) {
                    $this->db->select($tableName . '.' . $field . ' AS ' . $value . '_' . $field);
                }
                $this->db->join($tableName, $this->table() . '.' . $relation['from'] . '=' . $tableName . '.' . $relation['to']);
            }
        }

        $this->db->limit($options['limit']);

        $results = $this->db->get($this->table())->result();
        $new_results = [];
        $belongs_to_done = [];

        // reform what we've organized
        if ($options['with']) {
            foreach ($results as $row) {

                foreach ($options['with'] as $value) {
                    if (isset(static::$_belongs_to[$value])) {
                        $relation = static::$_belongs_to[$value];

                        foreach ($row as $key => $attr) if (preg_match('/' . $value . '_(.+)/', $key, $matches)) {
                            if (empty($row->{$value})) {
                                $row->{$value} = new stdClass();
                            }

                            $row->{$value}->{$matches[1]} = $attr;
                            unset($row->{$key});

                            // make sure we're getting a single result
                            if (empty($belongs_to_done[$row->{$relation['from']}])) {
                                $new_results[] = $row;
                            }

                            $belongs_to_done[$row->{$relation['from']}] = true;
                        }
                    }
                }
            }
        } else {
            $new_results = $results;
        }

        return $new_results;
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