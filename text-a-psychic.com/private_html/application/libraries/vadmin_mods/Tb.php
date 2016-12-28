<?php

	class tb
	{
	
		function config($name, $value, $params = null)
	    {
	    
	        $this->ci =& get_instance();
	        
	        $this->name = $name;
			$this->value = $value;
			$this->size = (isset($params[1]) ? $params[1] : '50');
			$this->required = (!empty($params[2]) ? 'required' : '');
	        
	    }
		
		function field_view($params)
		{
			$disabled = (isset($params[0]) ? 'disabled' : '');
		
			return "<input type='text' name='{$this->name}' value='{$this->value}' class='tb' size='{$this->size}' {$this->required} {$disabled}>";
		
		}
		
		function display_view()
		{
		
			return $this->value;
		
		}
		
		function process_form()
		{
			
			return $this->value;
			
		}
	
	}

?>