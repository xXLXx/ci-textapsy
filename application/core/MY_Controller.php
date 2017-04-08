<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public static $admin_only = [
		'bulletin_board'	=> [
			'index'
		]
	];

	public function __construct()
	{
		$result = parent::__construct();

		$redirect = false;
		if (isset(static::$admin_only[$this->router->fetch_class()])) {
			$methods = static::$admin_only[$this->router->fetch_class()];
			$this_method = $this->router->fetch_method();
			if (is_array($methods)) {
				foreach ($methods as $method) {
					if ($method == $this_method) {
						$redirect = true;
					}
				}
			} else {
				$redirect = true;
			}
		}

		if ($redirect && !Auth::is_logged_in()) {
			return redirect('/');
		}

		return $result;
	}
}