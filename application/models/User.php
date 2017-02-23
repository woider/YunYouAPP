<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 查询手机号
     * @param $phone
     * @return int
     */
    public function query_phone($phone)
    {
        $this->db->select('id');
        $this->db->from('user');
        $this->db->where('phone', $phone);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            return $row->id;
        }
        return 0;
    }


    /**
     * 创建用户
     * @param $data
     * @return int
     */
    public function create($data)
    {
        $data['create_time'] = time();
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }

    /**
     * 更新用户数据
     * @param $id
     * @param $data
     * @return int
     */
    public function update($id, $data)
    {
        $this->db->update('user', $data, array('id' => $id));
        return $this->db->affected_rows();
    }

    /**
     * 获取用户数据
     * @param $id
     * @return array
     */
    public function get_data($id, $select = '*')
    {
        $query = $this->db->select($select)
            ->from('user')->where('id', $id)->get();
        foreach ($query->result_array() as $row) {
            return $row;
        }
        return array();
    }


}