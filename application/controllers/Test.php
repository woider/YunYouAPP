<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (ENVIRONMENT === 'production') exit('Access not allowed');

class Test extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('tool');
        $this->load->helper('cookie');
        $this->load->helper('string');
        $this->load->library('session');
    }

    public function index()
    {
        
    }

    /**
     * 账号调试
     * @param $id
     */
    public function user($id = null)
    {

        if (empty($id)) {
            unset($_SESSION['user_id']);
            delete_cookie('user_id');
            delete_cookie('user_xs');
        } else {
            $_SESSION['user_id'] = $id;
        }
        print_r($_SESSION);
        print_r($_COOKIE);
    }

    /**
     * 删除cookie
     * @param null $name
     */
    public function remove($name = null)
    {
        if ($name === null) {
            delete_cookie('user_id');
            delete_cookie('user_xs');
            delete_cookie('commend');
            delete_cookie('approve');
        } else {
            delete_cookie($name);
        }
        print_r($_COOKIE);
    }
}


