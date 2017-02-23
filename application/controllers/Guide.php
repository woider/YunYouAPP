<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guide extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('tool');
        $this->load->library('session');
    }

    /**
     * 用户引导页面
     */
    public function index()
    {
        $this->load->helper('url');
        $this->load->library('yunyou');
        /* 该页面只有登录才可访问 */
        if ($this->yunyou->is_logged()) {
            $this->load->view('guide');
        } else {
            redirect(base_url('login'));
        }
    }

    /**
     * 提交用户数据
     */
    public function submit()
    {
        $profile = $this->input->post('profile');
        $nickname = $this->input->post('nickname');
        $nickname = preg_replace('/\s+/', '', $nickname);
        try {
            if (empty($_SESSION['user_id'])) {
                throw new Exception('请先登录');
            }
            if (empty($profile)) {
                throw new Exception('缺少头像');
            }
            if (empty($nickname)) {
                throw new Exception('未填昵称');
            }
            if (!file_exists(FCPATH . $profile)) {
                throw new Exception('图片不存在');
            }
            $pattern = '/^[A-Za-z0-9_\x{4e00}-\x{9fa5}]{1,12}$/u';
            if (!preg_match($pattern, $nickname)) {
                throw new Exception('昵称格式错误');
            }
        } catch (Exception $e) {
            output_json(false, $e->getMessage(), null);
            return;
        }
        $this->load->model('user');
        $data = array('profile' => $profile, 'nickname' => $nickname);
        $this->user->update($_SESSION['user_id'], $data);
        output_json(true, null, array('href' => '/home'));
    }

}