<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('tool');
        $this->load->model('scenery');
        $this->load->library('yunyou');
        $this->load->library('session');
    }

    /**
     * 云游入口
     */
    public function index()
    {
        $profile = null;
        /* 获取用户头像 */
        $this->load->model('user');
        if ($this->yunyou->is_logged()) {
            $user_id = $_SESSION['user_id'];
            $profile = $this->user->get_data($user_id, 'profile')['profile'];
        }
        /* 获取景点数据 */
        $param = array(
            'select' => 'id, name, address, belong, cover, sumscore, sumtimes',
            'where' => 'cover IS NOT NULL AND status = 1',
            'order' => 'CEIL(( ' . time() . ' - create_time ) / ( 3600 * 24 )) ASC, commend DESC',
            'limit' => 5,
        );
        $scenery = $this->scenery->search($param);
        $this->load->view('main', array(
            'profile' => $profile,
            'scenery' => $scenery,
        ));
    }

    /**
     * 随机数据
     */
    public function random()
    {
        $select = 'id, name, address, belong, cover, sumscore, sumtimes';
        $where = 'cover IS NOT NULL AND status = 1';
        $data = $this->scenery->search(array('select' => 'id', 'where' => $where));
        /* 随机取出5条ID */
        $num = (count($data) > 5) ? 5 : count($data);
        $list = array_rand($data, $num);
        for ($i = 0; $i < count($list); $i++) {
            $list[$i] = $data[$list[$i]]['id'];
        }
        $scenery = array();
        shuffle($list); //打乱数组
        foreach ($list as $id) {
            $data = $this->scenery->get_data($id, $select);
            array_push($scenery, $data);
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($scenery));
    }
}