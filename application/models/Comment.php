<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 创建评论
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        $data['create_time'] = time();
        $this->db->insert('comment', $data);
        return $this->db->insert_id();
    }

    /**
     * 增加赞同数
     * @param $id
     * @return mixed
     */
    public function increase($id)
    {
        $this->db->set('approve', 'approve + 1', false);
        $this->db->where('id', $id)->update('comment');
        return $this->db->affected_rows();
    }

    /**
     * 获取评论
     * @param $id
     * @param string $select
     * @return array
     */
    public function get_data($id, $select = '*')
    {
        $query = $this->db->select($select)
            ->from('comment')->where('id', $id)->get();
        foreach ($query->result_array() as $row) {
            return $row;
        }
        return array();
    }

    /**
     * 获取评论列表
     * @param $review_id
     * @param $page
     * @return mixed
     */
    public function get_comment_list($review_id, $page)
    {
        $this->db->select('user.id AS user_id, comment.id AS comment_id, user.nickname AS nickname, '
            . 'user.profile AS profile, comment.approve AS approve, comment.content AS content');
        $this->db->from('comment')->join('user', 'comment.creator_id = user.id');
        $this->db->where('comment.status = 1 AND comment.review_id  = ' . $review_id);
        $this->db->order_by('comment.create_time + (comment.approve * 3600) DESC');
        return $this->db->limit(5, $page * 5)->get()->result_array();
    }

    /**
     * 获取评论总数
     * @param $review_id_list
     * @return mixed
     */
    public function count($review_id_list)
    {

        $where = 'review_id IN(' . implode(",", $review_id_list) . ')';
        $where .= ' AND status = 1';
        $this->db->select('COUNT(id) AS sumcom')->from('comment');
        return $this->db->where($where)->get()->result()[0]->sumcom;
    }
}