<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 创建评价
     * @param $data
     * @return int
     */
    public function create($data)
    {
        $data['create_time'] = time();
        $data['update_time'] = time();
        $this->db->insert('review', $data);
        return $this->db->insert_id();
    }

    /**
     * 更新评价
     * @param $id
     * @param $data
     * @return int
     */
    public function update($id, $data)
    {
        $data['update_time'] = time();
        $this->db->update('review', $data, array('id' => $id));
        return $this->db->affected_rows();
    }

    /**
     * 根据ID获取数据
     * @param $id
     * @return array
     */
    public function get_data($id, $select = '*')
    {
        $query = $this->db->select($select)
            ->from('review')->where('id', $id)->get();
        foreach ($query->result_array() as $row) {
            return $row;
        }
        return array();
    }

    /**
     * 获取评价ID
     * @param $creator_id
     * @param $scenery_id
     * @return int
     */
    public function get_review_id($creator_id, $scenery_id)
    {
        $where = array(
            'creator_id' => $creator_id,
            'scenery_id' => $scenery_id,
        );
        $query = $this->db->select('id')->from('review')
            ->where($where)->get();
        foreach ($query->result() as $review) {
            return intval($review->id);
        }
        return 0;
    }

    /**
     * 连接review和user
     * @param $review_id_list
     * @return mixed
     */
    public function get_review_list($review_id_list)
    {
        /* 构造连接查询 */
        $this->db->select('review.id AS review_id, user.id AS user_id,'
            . 'grade, content, profile, nickname, update_time AS date');
        $this->db->from('review')->join('user', 'review.creator_id = user.id');
        $this->db->where('review.id in(' . implode(",", $review_id_list) . ')');
        return $this->db->get()->result_array();
    }
}