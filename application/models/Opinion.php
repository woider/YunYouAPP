<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opinion extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 创建记录
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        $data['create_time'] = time();
        $data['update_time'] = time();
        $this->db->insert('opinion', $data);
        return $this->db->insert_id();
    }

    /**
     * 更新记录
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $data['update_time'] = time();
        $this->db->update('opinion', $data, array('id' => $id));
        return $this->db->affected_rows();
    }

    /**
     * 获取评价ID
     * @param $judger_id
     * @param $review_id
     * @return int
     */
    public function get_opinion_id($judger_id, $review_id)
    {
        $where = array(
            'judger_id' => $judger_id,
            'review_id' => $review_id,
        );
        $query = $this->db->select('id')->from('opinion')
            ->where($where)->get();
        foreach ($query->result() as $opinion) {
            return intval($opinion->id);
        }
        return 0;
    }

    /**
     * 获取数据
     * @param $id
     * @param string $select
     * @return array
     */
    public function get_data($id, $select = '*')
    {
        $query = $this->db->select($select)
            ->from('opinion')->where('id', $id)->get();
        foreach ($query->result_array() as $row) {
            return $row;
        }
        return array();
    }

    /**
     * 获取态度
     * @param $user_id
     * @param $review_id
     * @return int
     */
    public function get_attitude($user_id, $review_id)
    {
        $where = array(
            'judger_id' => $user_id,
            'review_id' => $review_id,
        );
        $query = $this->db->select('attitude')->from('opinion')
            ->where($where)->get();
        foreach ($query->result() as $opinion) {
            return intval($opinion->attitude);
        }
        return 0;
    }


    /**
     * 获取看法总数
     * @param $review_id
     * @param $attitude
     * @return int
     */
    public function get_total_opinion($review_id, $attitude)
    {
        $sql = "SELECT COUNT('attitude') AS sum FROM opinion "
            . "WHERE review_id = $review_id AND attitude = $attitude";
        return intval($this->db->query($sql)->result()[0]->sum);
    }
}