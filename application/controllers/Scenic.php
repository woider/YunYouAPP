<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scenic extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('tool');
        $this->load->library('yunyou');
        $this->load->library('session');
    }

    /**
     * 景点编辑入口
     * @param null $id
     */
    public function index($id = null)
    {
        $this->load->helper('url');
        /* 验证登录状态 */
        if ($this->yunyou->is_logged()) {
            $user_id = $_SESSION['user_id'];
        } else {
            redirect(base_url('login'));
            return;
        }
        if (is_numeric($id)) {//编辑景点
            $this->revise($user_id, $id);
        } else { //新建景点
            $this->load->view('scenic');
        }
    }

    /**
     * 修改景点信息
     * @param $user_id
     * @param $scenic_id
     */
    private function revise($user_id, $scenic_id)
    {
        $this->load->model('scenery');
        $data = $this->scenery->get_data($scenic_id);
        if (!empty($data) && $data['creator_id'] == $user_id) {
            $this->load->view('scenic', $data);
        } else {
            redirect(base_url('scenic'));
        }
    }

    /**
     * 图片上传
     */
    public function upload()
    {
        $this->load->helper('img');
        /* 获取BASE64编码 */
        $base64 = $this->input->post('base64');
        if (empty($base64)) {
            output_json(false, 'base64 is empty', null);
            return;
        }
        /* 转换BASE64为图片 */
        $file_name = md5(microtime(true)) . '.jpg';
        $file_path = FCPATH . 'picture/temp/' . $file_name;
        if (!base64_to_image($base64, $file_path)) {
            output_json(false, 'upload failed', null);
            return;
        }
        /* 居中裁剪图片 */
        $src = '/picture/cover/' . date('Ymd') . '/' . $file_name;
        $result = image_center_crop($file_path, 960, 600, FCPATH . $src);
        if ($result === true) {
            $this->load->model('picture');
            $this->picture->save($src, 'cover', $_SESSION['user_id']);
            output_json(true, null, array('src' => $src));
        } else {
            output_json(false, 'upload failed', null);
            return;
        }
        unlink($file_path);
    }

    /**
     * 提交景点数据
     * @param null $scenic_id
     */
    public function submit($scenic_id = null)
    {
        $this->load->model('scenery');
        /* 获取scenic相关数据 */
        $name = trim($this->input->post('name'));
        $address = trim($this->input->post('address'));
        $belong = trim($this->input->post('belong'));
        $introduce = $this->input->post('introduce');
        $cover = $this->input->post('cover');
        if (empty($name) || empty($address) || empty($belong)) {
            output_json(false, 'miss key data', null);
            return;
        }
        /* 默认数据 */
        $this->load->helper('security');
        $data = array(
            'name' => mb_substr($name, 0, 16),
            'address' => mb_substr($address, 0, 8),
            'belong' => mb_substr($belong, 0, 8),
            'introduce' => xss_clean($introduce),//XSS攻击过滤
            'cover' => empty($cover) ? null : json_encode($cover),
        );
        if (is_numeric($scenic_id)) {//更新模式
            $this->scenery->update($scenic_id, $data);
            output_json(true, null, array('id' => $scenic_id));
        } else {//创建模式
            $data['creator_id'] = $_SESSION['user_id'];
            $id = $this->scenery->create($data);
            output_json(true, null, array('id' => $id));
        }
    }

}