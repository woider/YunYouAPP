<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detail extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('user');
        $this->load->helper('tool');
        $this->load->helper('cookie');
        $this->load->library('yunyou');
        $this->load->library('session');
    }


    /**
     * 景点详情页面
     * @param null $id
     */
    public function index($id = null)
    {
        if (!is_numeric($id)) {
            error_exit('URL Error');
        }
        $profile = null;
        /* 获取用户头像 */
        if ($this->yunyou->is_logged()) {
            $user_id = $_SESSION['user_id'];
            $profile = $this->user->get_data($user_id, 'profile')['profile'];
        }
        /* 获取景区数据 */
        $this->load->model('scenery');
        $scenery = $this->scenery->get_data($id);
        if (empty($scenery)) {
            error_exit('404 Not Found');
        }
        $this->load->view('detail', array(
            'profile' => $profile,
            'scenery' => $scenery,
        ));
    }

    /**
     * 推荐景点
     */
    public function commend()
    {
        /* 获取景点ID */
        $scenery_id = $this->input->post('scenery_id');
        if (!is_numeric($scenery_id)) {
            output_json(false, '参数错误', null);
            return;
        }
        /* 获取记录 */
        $commend = get_cookie('commend');
        if ($commend == null) {
            $commend = array();
        } else {
            $commend = explode(',', $commend);
        }
        /* 增加推荐数 */
        if (in_array($scenery_id, $commend)) {
            output_json(false, '重复推荐', null);
            return;
        }
        $sql = "UPDATE scenery SET commend = commend + 1 WHERE id = $scenery_id";
        $this->db->query($sql);
        if ($this->db->affected_rows() == 0) {
            output_json(false, '推荐失败', null);
            return;
        }
        /* 保存记录 */
        array_push($commend, $scenery_id);
        $commend = implode(',', $commend);
        set_cookie('commend', $commend, 3600 * 24 * 7);
        output_json(true, null, '');
    }

    /**
     * 获取评论数据
     */
    public function reviews()
    {
        $scenery_id = $this->input->get('scenery_id');
        $page = $this->input->get('page');
        $order = $this->input->get('order');
        if (!is_numeric($scenery_id) || !is_numeric($page)) {
            output_json(false, '参数错误', null);
            return;
        }
        $this->load->model('review');
        $this->load->model('opinion');
        $this->load->model('comment');
        $sql = $this->review_list_sql($scenery_id, $order) . ' LIMIT ' . ($page * 5) . ', 5';
        $id_list = array_column($this->db->query($sql)->result_array(), 'review_id');
        if (empty($id_list)) return;// 查询结束
        $data = $this->review->get_review_list($id_list);
        /* 处理查询结果 */
        $review_list = array();
        for ($i = 0; $i < count($data); $i++) {
            for ($j = 0; $j < count($data); $j++) {
                if ($id_list[$i] == $data[$j]['review_id']) {
                    $review_list[$i] = $data[$j];
                    $review_list[$i]['date'] = date('Y-m-d', $data[$j]['date']);
                    $review_list[$i]['content'] = build_text($data[$j]['content'], 100, '...');
                    $approve = $this->opinion->get_total_opinion($data[$j]['review_id'], 1);
                    $oppose = $this->opinion->get_total_opinion($data[$j]['review_id'], -1);
                    $sumcom = $this->comment->count(array($data[$j]['review_id']));
                    $review_list[$i]['message'] = $approve . ' 赞同 · ' . $oppose . ' 反对 · ' . $sumcom . ' 评论';
                }
            }
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($review_list));
    }

    /**
     * 获取SQL语句
     * @param $scenery_id
     * @param $order
     * @return string
     */
    private function review_list_sql($scenery_id, $order)
    {
        switch (strtoupper($order)) {
            case 'LIKE':
                return "SELECT review_id FROM opinion GROUP BY review_id HAVING review_id IN "
                . "(SELECT id FROM review WHERE scenery_id = {$scenery_id} AND status = 1) "
                . "AND SUM(attitude) > 0 ORDER BY SUM(attitude) DESC";
            case 'TIME':
                return "SELECT id AS review_id FROM review WHERE scenery_id = {$scenery_id} "
                . "AND status = 1 ORDER BY create_time DESC";
            case 'DROP':
                return "SELECT review_id FROM opinion GROUP BY review_id HAVING review_id IN "
                . "(SELECT id FROM review WHERE scenery_id = {$scenery_id} AND status = 1) "
                . "AND SUM(attitude) < 0 ORDER BY SUM(attitude) DESC " . ' # ';
            default:
                return "SELECT id FROM review WHERE scenery_id = {$scenery_id}";
        }
    }
}