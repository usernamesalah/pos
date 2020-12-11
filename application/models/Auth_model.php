<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    public function verifyLogIn($data)
    {
        $email = $data['email'];
        $password = $this->encryptPassword($data['password']);

        $query = $this->db->get_where('users', array('email' => $email));
        $user_data = $query->row();

        if ($user_data) {
            $result = array();
            if ($password == $user_data->password) {
                if ($user_data->status == 0) {
                    $result['valid'] = false;
                    $result['error'] = 'Your account is suspended! Please contact to Administrator!';
                } else {
                    $result['valid'] = true;
                    $result['user_id'] = $user_data->id;
                    $result['user_email'] = $user_data->email;
                    $result['role_id'] = $user_data->role_id;
                    $result['outlet_id'] = $user_data->outlet_id;
                }
            } else {
                $result['valid'] = false;
                $result['error'] = 'Invalid Password!';
            }

            return $result;
        } else {
            $result['valid'] = false;
            $result['error'] = 'Email Address do not exist at the system!';

            return $result;
        }
    }

    public function encryptPassword($password)
    {
        return md5("$password");
    }
}
