<?php

	class md5
	{
	
		function config($name, $value, $params = null)
	    {
	    
	        $this->ci =& get_instance();
	        
	        $this->name = $name;
			$this->value = $value;
			$this->size = (isset($params[1]) ? $params[1] : '50');
			$this->required = (isset($params[2]) ? 'required' : '');
			$this->withConfirm = true;
	    }
		
		function field_view()
		{
			$subtext = '';
			$required = 'required';
			if (preg_match('/.*edit|save.*/', $this->ci->router->method)) {
				$subtext = "** Leave Blank To Keep Current Password ** Encrypted using BCRYPT Encryption **";
				$required = "";
			}
			$html = "<input type='password' name='{$this->name}' class='tb' size='{$this->size}' $required>
					<div class='caption' style='padding:5px 0 0;'>$subtext</div>";

			// This part is an HTML injection
			if ($this->withConfirm) {
				$titleBase = ucwords($this->name);
				$title = "Confirm $titleBase";
				$html .= "
				</tr>
				<tr class='special_td'>
					<td class='std' valign='top' width='200 style=' background:#e0e0e0;'>
						<b>$title</b>
						<span class='required_field'>*</span>
					</td>
					<td class='std' valign='top'>
						<input type='password' name='confirm-{$this->name}' class='tb' size='{$this->size}' $required>
						<div class='caption' style='padding:5px 0 0;'>** $title should be the same as $titleBase **</div>
					</td>
				</tr>
				<script>
					var \$confirmEle = $('[name=\"confirm-{$this->name}\"]');
					var \$ele = $('[name=\"{$this->name}\"]');
					$('body').on('submit', \$confirmEle.closest('form'), function (e) {
						\$confirmEle.find('+ .caption').hide();
						if (\$ele.val() != \$confirmEle.val()) {
							\$confirmEle.find('+ .caption').show();
							$('html, body').animate({scrollTop: \$confirmEle.offset().top - 50}, 1000);
							e.preventDefault();
						}
					});
				</script>
				";
			}
			return $html;
		}
		
		function display_view()
		{
		
			return "** Password Hidden **";
		
		}
		
		function process_form()
		{
		
			if(!trim( $this->value )) return '[%skip%]';
			else return Auth::hash($this->value);
		
		}
	
	}

?>