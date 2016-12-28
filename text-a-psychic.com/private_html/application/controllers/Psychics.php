<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'libraries/REST_Controller.php');

class Psychics extends REST_Controller {

    public function psychic_get ($id) {
        $errors = [];
        $success = true;
        
        if (count($errors)) {
            $success = false;
        }

        $data = $this->Psychic_model->all([
            'where' => [
                'id'    => [
                    $id
                ]
            ]
        ]);

        return $this->response(compact('data', 'success', 'errors'));
    }

    public function psychic_all_get () {
        $errors = [];
        $success = true;
        
        if (count($errors)) {
            $success = false;
        }

        $data = $this->Psychic_model->all();

        return $this->response(compact('data', 'success', 'errors'));
    }



    public function login_post ()
    {
        $errors = [];
        $success = true;
        $data = null;

        $username = $this->post('username');
        $password = $this->post('password');

        if (!$username) {
            $errors[] = 'Missing username';
        }
        if (!$password) {
            $errors[] = 'Missing password';
        }

        if (!count($errors)) {
            if (!Auth::login($username, $password)) {
                $errors = Auth::$errors;    
            }
        }

        if (count($errors)) {
            $success = false;
        }

        setcookie('user', json_encode(Auth::me()), time() + 7200, '/');

        return $this->response(compact('data', 'success', 'errors'));
    }

    public function logout_get()
    {
        $errors = [];
        $success = true;
        $data = null;

        Auth::logout();

        setcookie('user', '', 0, '/');

        return $this->response(compact('data', 'success', 'errors'));
    }
}
