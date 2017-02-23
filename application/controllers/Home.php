<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
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
     * 用户主页
     * @param null $id
     */
    public function index($id = null)
    {
        $this->load->helper('url');
        $this->load->model('user');

        $logged = $this->yunyou->is_logged();
        if (is_numeric($id)) {
            $user_id = $id; //游客访问模式
        } else {
            if ($logged) { // 个人主页模式
                $user_id = $_SESSION['user_id'];
            }
        }
        /* 禁止游客访问主页 */
        if (!isset($user_id) && !$logged) {
            redirect(base_url('login'));
            return;
        }
        $data = $this->user->get_data($user_id, 'id, profile, nickname, gender');
        if (empty($data)) {
            error_exit('404 Not Found');
        }
        /* 计算赞同数 */
        $sql = "SELECT COUNT(id) AS approve FROM opinion WHERE attitude = 1 AND review_id IN "
            . "(SELECT id FROM review WHERE creator_id = {$user_id})";
        $data['approve'] = $this->db->query($sql)->result()[0]->approve;
        /* 计算友善度 */
        $sql = "SELECT SUM(grade)/COUNT(grade) AS level  FROM review WHERE creator_id = {$user_id}";
        $result = $this->db->query($sql)->result();
        $data['kindness'] = empty($result) ? 0 : round($result[0]->level);
        /* 计算评价总数 */
        $sql = "SELECT COUNT(id) AS sumreview FROM review WHERE creator_id = {$user_id}";
        $data['sumreview'] = $this->db->query($sql)->result()[0]->sumreview;
        /* 计算景点总数 */
        $sql = "SELECT COUNT(id) AS sumscenery FROM scenery WHERE creator_id = {$user_id}";
        $data['sumscenery'] = $this->db->query($sql)->result()[0]->sumscenery;
        $this->load->view('home', $data);
    }

    /**
     * 获取用户历史评价
     */
    public function review()
    {
        $page = $this->input->get('page');
        $user_id = $this->input->get('user_id');
        if (empty($user_id) && $this->yunyou->is_logged()) {
            $user_id = $_SESSION['user_id'];
        }
        if (empty($user_id) || !is_numeric($page)) return;
        /* 查询评价信息 */
        $sql = "SELECT review.id AS review_id, scenery.id AS scenery_id, scenery.name AS name, "
            . "review.grade AS grade, review.content AS content, review.update_time AS date "
            . "FROM review INNER JOIN scenery ON review.scenery_id = scenery.id "
            . "WHERE review.creator_id = {$user_id} ORDER BY review.create_time DESC LIMIT " . ($page * 5) . ', 5';
        $review = $this->db->query($sql)->result_array();
        /* 计算赞同和反对 */
        $sql = "SELECT COUNT(id) AS msg FROM opinion WHERE attitude = 1 AND review_id = @RE_ID "
            . "UNION ALL SELECT COUNT(id) AS msg FROM opinion WHERE attitude = -1 AND review_id = @RE_ID ";
        for ($i = 0; $i < count($review); $i++) {
            $data = $this->db->query(str_replace('@RE_ID', $review[$i]['review_id'], $sql))->result();
            $review[$i]['message'] = $data[0]->msg . ' 赞同 · ' . $data[1]->msg . '反对 · ';
            $review[$i]['content'] = build_text($review[$i]['content'], 100, '...');
            $review[$i]['date'] = date('Y-m-d', $review[$i]['date']);
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($review));
    }


    /**
     * 获取用户创建的景点
     */
    public function scenery()
    {
        $page = $this->input->get('page');
        $user_id = $this->input->get('user_id');
        if (empty($user_id) && $this->yunyou->is_logged()) {
            $user_id = $_SESSION['user_id'];
        }
        if (empty($user_id) || !is_numeric($page)) return;
        /* 查询景点信息 */
        $sql = "SELECT id AS scenery_id, name, address, belong, cover, sumscore, sumtimes FROM scenery "
            . "WHERE creator_id = {$user_id} AND status = 1 ORDER BY create_time DESC LIMIT " . ($page * 5) . ', 5';
        $scenery = $this->db->query($sql)->result_array();
        /* 查询评价人数 */
        $sql = "SELECT COUNT(id) AS sumreview FROM review GROUP BY scenery_id HAVING scenery_id";
        for ($i = 0; $i < count($scenery); $i++) {
            $data = $this->db->query($sql . ' = ' . $scenery[$i]['scenery_id'])->result();
            $scenery[$i]['message'] = (empty($data) ? 0 : $data[0]->sumreview) . ' 人评价';
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($scenery));
    }
}