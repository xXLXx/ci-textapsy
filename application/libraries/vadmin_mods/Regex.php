<?php

	class regex
	{
	
		function config($name, $value, $params = null)
	    {
	    
	        $this->ci =& get_instance();
	        
	        $this->name = $name;
			$this->value = $value;
			$this->regex = (isset($params[1]) ? $params[1] : '');
			$this->size = (isset($params[2]) ? $params[2] : '50');
			$this->required = (isset($params[3]) ? 'required' : '');
	        
	    }
		
		function field_view()
		{
			$regexChecker = '';
			$fieldId = "regex_{$this->name}_" . time();

			if ($this->regex && preg_match('/\/(.+)\/(\w+)?/', $this->regex, $matches)) {
				$matches[1] = str_replace('\\', '\\\\', $matches[1]);
				$regexChecker = "
					<script>
						$('#$fieldId').closest('form').on('submit', function (e) {
							$('#$fieldId + .caption').hide();
							if (!new RegExp('{$matches[1]}').exec($('#$fieldId').val())) {
								$('#$fieldId + .caption').show();
								$('html, body').animate({scrollTop: $('#$fieldId').offset().top - 50}, 1000);
								e.preventDefault();
							}
						});
					</script>
				";
			}

			return "
				<input type='text' name='{$this->name}' value='{$this->value}' class='tb regex' id='$fieldId' size='{$this->size}' {$this->required}>
				<div class='caption' style='padding:5px 0 0; display: none;'>** Value doesn't follow expected format **</div>
				$regexChecker
			";
		
		}
		
		function display_view()
		{
		
			return $this->value;
		
		}
		
		function process_form()
		{
			if ($this->regex && !preg_match($this->regex, $this->value)) {
				return "";
			}

			return $this->value;
			
		}
	
	}

?>