<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user');
        $this->load->helper('tool');
        $this->load->library('yunyou');
        $this->load->library('session');
    }

    /**
     * 设置页面入口
     */
    public function index()
    {
        $this->load->helper('url');
        /* 该页面只有登录才可访问 */
        if (!$this->yunyou->is_logged()) {
            redirect(base_url('login'));
        } else {
            $data = $this->user->get_data($_SESSION['user_id']);
            $this->load->view('setup', $data);
        }
    }

    /**
     * 上传用户头像
     */
    public function upload()
    {
        $this->load->helper('img');
        $this->load->library('upload');
        /* 创建临时目录 */
        $temp_dir = FCPATH . 'picture/temp';
        if (!file_exists($temp_dir)) mkdir($temp_dir, 0777, true);
        /* 设置上传参数 */
        $this->upload->initialize(array(
            'upload_path' => $temp_dir,
            'allowed_types' => 'jpg',
            'max_size' => '2048',
            'encrypt_name' => TRUE,
        ));
        /* 上传图片 */
        if ($this->upload->do_upload('profile')) {
            $info = $this->upload->data();
        } else {
            output_json(false, '头像上传失败', null);
            return;
        }
        /* 裁剪图片 */
        $src = '/picture/profile/' . date('Ymd') . '/' . $info['file_name'];
        $result = image_center_crop($info['full_path'], 480, 480, FCPATH . $src);
        if ($result) {
            $this->load->model('picture');
            $this->picture->save($src, 'profile', $_SESSION['user_id']);
            output_json(true, null, array('src' => $src));
        } else {
            output_json(false, '头像上传失败', null);
        }
        unlink($info['full_path']);
    }

    /**
     * 保存用户信息
     */
    public function submit()
    {
        $data = array();
        /* 获取相关数据 */
        $profile = $this->input->post('profile');
        $nickname = $this->input->post('nickname');
        $birthday = $this->input->post('birthday');
        $gender = $this->input->post('gender');
        $profession = $this->input->post('profession');
        /* 检查图片合法性 */
        if (!empty($profile) && file_exists(FCPATH . $profile)) {
            $data['profile'] = $profile;
        }
        /* 检查昵称合法性 */
        $pattern = '/^[A-Za-z0-9_\x{4e00}-\x{9fa5}]{1,12}$/u';
        if (!empty($nickname) && preg_match($pattern, $nickname)) {
            $data['nickname'] = $nickname;
        }
        /* 检查生日合法性 */
        $pattern = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/';
        if (!empty($birthday) && preg_match($pattern, $birthday)) {
            $data['birthday'] = $birthday;
        }
        /* 验证性别合法性 */
        if ($gender . '' === '0' || $gender . '' === '1') {
            $data['gender'] = $gender;
        }
        /* 验证职业合法性 */
        $pattern = '/^[A-Za-z0-9_\x{4e00}-\x{9fa5}]{1,12}$/u';
        if (!empty($profession) && preg_match($pattern, $profession)) {
            $data['profession'] = $profession;
        }
        /* 保存符合条件的数据 */
        $user_id = $_SESSION['user_id'];
        $this->user->update($user_id, $data);
        /* 将用户数据输出 */
        $data = $this->user->get_data($user_id);
        output_json(true, null, array(
            'profile' => $data['profile'],
            'nickname' => $data['nickname'],
            'birthday' => $data['birthday'],
            'gender' => $data['gender'],
            'profession' => $data['profession'],
        ));
    }

    /**
     * 退出登录
     */
    public function sign_out()
    {
        unset($_SESSION['user_id']);
        $this->input->set_cookie('user_id', null, null);
        $this->input->set_cookie('user_xs', null, null);
        output_json(true, null, array('href' => '/login'));
    }

}