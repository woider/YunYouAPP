<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assess extends CI_Controller
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
     * 评价页面入口
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
        /* 获取景点ID */
        if (is_numeric($id)) {
            $scenery_id = $id;
        } else {
            error_exit('URL Error');
        }
        /* 验证景点ID */
        $data = $this->load_data($user_id, $scenery_id);
        if (!empty($data)) {
            $this->load->view('assess', $data);
        } else {
            error_exit('404 Not Found');
        }

    }

    /**
     * 加载相关数据
     * @param $user_id
     * @param $scenery_id
     * @return array
     */
    private function load_data($user_id, $scenery_id)
    {
        /* 获取景点信息 */
        $this->load->model('scenery');
        $scenery_data = $this->scenery->get_data($scenery_id, 'name,address,belong');
        if (empty($scenery_data)) return array();
        /* 获取评价数据 */
        $this->load->model('review');
        $review_id = $this->review->get_review_id($user_id, $scenery_id);
        $review_data = $this->review->get_data($review_id);
        return array(
            'scenery' => $scenery_data,
            'review' => $review_data,
        );
    }

    /**
     * 上传BASE64图片
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
        $src = '/picture/photo/' . date('Ymd') . '/' . $file_name;
        $result = base64_to_image($base64, FCPATH . $src);
        if ($result === true) {
            $this->load->model('picture');
            $this->picture->save($src, 'photo', $_SESSION['user_id']);
            output_json(true, null, array('src' => $src));
        } else {
            output_json(false, 'upload failed', null);
        }
    }

    /**
     * 提交评论
     * @param null $id
     */
    public function submit($id = null)
    {
        if (is_numeric($id) && isset($_SESSION['user_id'])) {
            $scenery_id = $id;
            $user_id = $_SESSION['user_id'];
        } else {
            output_json(false, 'Parameter error', null);
            return;
        }
        /* 获取相关数据 */
        $grade = $this->input->post('grade');
        $content = $this->input->post('content');
        $photo = $this->input->post('photo');
        if (empty($grade) || empty($content)) {
            output_json(false, 'Miss key data', null);
            return;
        }
        $this->load->helper('security');
        $data = array(
            'content' => xss_clean($content),//XSS攻击过滤
            'photo' => empty($photo) ? null : json_encode($photo),
        );
        /* 更新评价数据 */
        $this->load->model('review');
        $review_id = $this->review->get_review_id($user_id, $scenery_id);
        if ($review_id == 0) {//创建模式
            $data['grade'] = 0;
            $data['creator_id'] = $user_id;
            $data['scenery_id'] = $scenery_id;
            $review_id = $this->review->create($data);
        } else {//更新模式
            $rows = $this->review->update($review_id, $data);
        }
        /* 提交分数 */
        if ($this->mark_grade($review_id, $scenery_id, $grade)) {
            output_json(true, null, array('id' => $review_id));
        } else {
            output_json(true, 'score error', null);
        }
    }

    /**
     * 提交分数
     */
    private function mark_grade($review_id, $scenery_id, $grade)
    {
        /* 获取原分数 */
        $origin = $this->review->get_data($review_id, 'grade')['grade'];
        $times = ($origin == 0) ? 0 : 1;
        $grade = (1 <= $grade && $grade <= 5) ? $grade : 3;
        /* 更新表SQL语句 */
        $scenery_sql = "UPDATE scenery SET sumtimes = (sumtimes - $times) + 1,"
            . "sumscore = (sumscore - $origin) + $grade WHERE id = $scenery_id";
        $review_sql = "UPDATE review SET grade = $grade WHERE id = $review_id";
        $this->db->trans_start();
        $this->db->query($scenery_sql);
        $this->db->query($review_sql);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

}