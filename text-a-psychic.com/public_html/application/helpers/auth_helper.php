<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Auth {

	public static $errors = [];
	public static $context = null;

	public static function init()
	{
		static::$context =& get_instance();
	}

	public static function login($username, $password)
	{
		static::$errors = [];

		if (isset(static::$context->session->userdata['user'])) {
			return true;
		} else {
			$userdata = null;
			$usermatches = static::$context->Psychic_model->all([
				'where' => [
					'or_where'	=> [
						'username'			=> $username,
						'email_address'		=> $username
					]
				]
			]);

			foreach ($usermatches as $user) {
				if (password_verify($password, $user->password)) {
					$userdata = $user;
					break;
				}
			}

			if (!$userdata) {
				static::$errors[] = 'Invalid username or password';
			} else {
				static::$context->session->set_userdata('user', $userdata);
			}
			
		}

		return false;
	}

	public static function is_logged_in()
	{
		return (boolean) static::me();
	}

	public static function me()
	{
		if (isset(static::$context->session->userdata['user'])) {
			return static::$context->session->userdata['user'];
		} else {
			return null;
		}
	}

	public static function logout()
	{
		static::$context->session->unset_userdata('user', []);
	}

	public static function hash($password)
	{
		return password_hash($password, PASSWORD_BCRYPT, [
			'cost' => static::$context->config->item('password_cost'),
			'salt' => static::$context->config->item('password_salt')
		]);
	}
}

Auth::init();