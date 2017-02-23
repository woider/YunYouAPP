<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scenery extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 创建景点
     * @param $data
     * @return int
     */
    public function create($data)
    {
        $data['create_time'] = time();
        $data['update_time'] = time();
        $this->db->insert('scenery', $data);
        return $this->db->insert_id();
    }

    /**
     * 根据ID获取数据
     * @param $id
     * @return array
     */
    public function get_data($id, $select = '*')
    {
        $query = $this->db->select($select)
            ->from('scenery')->where('id', $id)->get();
        foreach ($query->result_array() as $row) {
            return $row;
        }
        return array();
    }

    /**
     * 更新数据
     * @param $id
     * @param $data
     * @return int
     */
    public function update($id, $data)
    {
        $data['update_time'] = time();
        $this->db->update('scenery', $data, array('id' => $id));
        return $this->db->affected_rows();
    }

    /**
     * 搜索数据
     * @return array
     */
    public function search($param)
    {
        if (empty($param['select'])) {
            $param['select'] = '*';
        }
        if (empty($param['where'])) {
            $param['where'] = 'id IS NOT NULL';
        }
        if (empty($param['order'])) {
            $param['order'] = 'id ASC';
        }
        if (empty($param['limit'])) {
            $param['limit'] = 1000;
        }
        if (empty($param['offset'])) {
            $param['offset'] = 0;
        }
        $this->db->select($param['select']);
        $this->db->from('scenery');
        $this->db->where($param['where']);
        $this->db->order_by($param['order']);
        $this->db->limit($param['limit'], $param['offset']);
        $query = $this->db->get();
        return $query->result_array();
    }
}