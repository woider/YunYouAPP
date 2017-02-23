<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Navbar extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('tool');
        $this->load->library('yunyou');
        $this->load->library('session');
    }

    public function index()
    {
        $this->load->view('navbar');
    }

    /**
     * 搜索景点
     * @param $keyword
     */
    public function search()
    {
        $keyword = $this->input->get('keyword');
        /* 设置参数 */
        $select = 'id,name,address,belong';
        $where = '';
        $order = 'sumtimes DESC';
        $limit = 10;
        /* 处理关键字 */
        $keyword = trim(urldecode($keyword));
        $keyword = preg_replace('/\s+/', ",", $keyword);
        $key_arr = explode(',', $keyword);
        foreach ($key_arr as $key) {
            $where .= "CONCAT(name,address,belong) LIKE '%{$key}%' OR ";
        }
        $where .= 'id IS NULL';
        /* 搜索数据 */
        $this->load->model('scenery');
        $param = array(
            'select' => $select,
            'where' => $where,
            'order' => $order,
            'limit' => $limit,
        );
        $data = $this->scenery->search($param);
        /* 打印结果 */
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($data));
    }
}