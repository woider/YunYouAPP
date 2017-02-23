<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discuss extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('user');
        $this->load->helper('tool');
        $this->load->model('opinion');
        $this->load->model('comment');
        $this->load->library('yunyou');
        $this->load->library('session');
    }

    /**
     * 讨论页面
     * @param null $id
     */
    public function index($id = null)
    {
        /* 检查登录状态 */
        $profile = $opinion = null;
        if ($this->yunyou->is_logged()) {
            $user_id = $_SESSION['user_id'];
            /* 获取用户头像 */
            $profile = $this->user->get_data($user_id, 'profile')['profile'];
        }
        if (!is_numeric($id)) {
            error_exit('URL Error');
        }
        /* 获取评价数据 */
        $this->load->model('review');
        $this->load->model('scenery');
        $review = $this->review->get_data($id);
        if (empty($review)) {
            error_exit('404 Not Found');
        }
        /* 获取作者和景点信息 */
        $review['creator'] = $this->user->get_data($review['creator_id'], 'nickname, profile');
        $review['scenery'] = $this->scenery->get_data($review['scenery_id'], 'name, address, belong');
        /* 获取评价看法 */
        if (isset($user_id)) {
            $opinion['attitude'] = $this->opinion->get_attitude($user_id, $review['id']);
        }
        $opinion['approve'] = $this->opinion->get_total_opinion($review['id'], 1);
        $opinion['oppose'] = $this->opinion->get_total_opinion($review['id'], -1);
        /* 获取评论总数 */
        $sumcom = $this->comment->count(array($review['id']));
        $this->load->view('discuss', array(
            'profile' => $profile,
            'review' => $review,
            'opinion' => $opinion,
            'sumcom' => $sumcom,
        ));
    }

    /**
     * 发表看法
     */
    public function opinion()
    {
        /* 判断是否登录 */
        if ($this->yunyou->is_logged()) {
            $user_id = $_SESSION['user_id'];
        } else {
            return;
        }
        /* 检查参数 */
        $review_id = $this->input->post('review_id');
        $attitude = $this->input->post('attitude');
        if (!is_numeric($review_id) || !is_numeric($attitude)) {
            output_json(false, '参数错误', null);
            return;
        }
        if ($attitude > 0) {
            $attitude = 1;
        } else if ($attitude < 0) {
            $attitude = -1;
        }
        /* 记录看法 */
        $opinion_id = $this->opinion->get_opinion_id($user_id, $review_id);
        if ($opinion_id == 0) { // 创建模式
            $data = array(
                'attitude' => $attitude,
                'judger_id' => $user_id,
                'review_id' => $review_id,
            );
            $this->opinion->create($data);
        } else { //更新模式
            $data['attitude'] = $attitude;
            $this->opinion->update($opinion_id, $data);
        }
        output_json(true, null, array('attitude' => $attitude));
    }

    /**
     * 添加评论
     */
    public function submit()
    {
        /* 获取信息 */
        $content = $this->input->post('content');
        $review_id = $this->input->post('review_id');
        $user_id = $this->session->user_id;
        if (empty($user_id)) return;
        /* 处理数据 */
        $this->load->helper('security');
        $content = mb_substr(xss_clean($content), 0, 200);
        $data = array(
            'content' => $content,
            'review_id' => $review_id,
            'creator_id' => $user_id,
        );
        $id = $this->comment->create($data);
        $data = $this->comment->get_data($id);
        if (empty($data)) {
            output_json(false, '评论失败', null);
            return;
        }
        $user = $this->user->get_data($user_id, 'profile, nickname');
        $data['comment_id'] = $data['id'];
        $data['profile'] = $user['profile'];
        $data['nickname'] = $user['nickname'];
        $data['user_id'] = $data['creator_id'];
        $data['content'] = htmlspecialchars($data['content']);
        output_json(true, null, $data);
    }

    /**
     * 获取评论列表
     */
    public function comment()
    {
        $page = $this->input->get('comm_page');
        $review_id = $this->input->get('review_id');
        if (!is_numeric($page) || !is_numeric($review_id)) {
            output_json(false, '参数错误', null);
            return;
        }
        $comment = $this->comment->get_comment_list($review_id, $page);
        for ($i = 0; $i < count($comment); $i++) {
            $comment[$i]['content'] = htmlspecialchars($comment[$i]['content']);
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($comment));
    }

    public function approve()
    {
        $comment_id = $this->input->post('comment_id');
        if (empty($comment_id)) return;
        /* 获取记录 */
        $approve = get_cookie('approve');
        if ($approve == null) {
            $approve = array();
        } else {
            $approve = explode(',', $approve);
        }
        /* 点赞查重 */
        if (in_array($comment_id, $approve)) {
            output_json(false, '重复赞同', null);
            return;
        }
        /* 增加赞同 */
        if (!$this->comment->increase($comment_id)) {
            output_json(false, '点赞失败', null);
            return;
        }
        /* 保存记录 */
        array_push($approve, $comment_id);
        $approve = implode(',', $approve);
        set_cookie('approve', $approve, 3600 * 24 * 7);
        output_json(true, null, '');
    }
}