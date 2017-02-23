<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Picture extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 保存图片信息
     * @param string $src
     * @param int $class
     * @param int $uploader_id
     * @return int
     */
    public function save($src, $class, $uploader_id)
    {
        $path = FCPATH . $src;
        if (!file_exists($path)) return FALSE;
        $img_data = getimagesize($path);
        if (empty($img_data)) return FALSE;
        $class = $this->class_encode($class);
        /* 插入图片信息 */
        $data = array(
            'src' => $src,
            'width' => $img_data[0],
            'height' => $img_data[1],
            'type' => $img_data[2],
            'class' => $class,
            'uploader_id' => $uploader_id,
            'upload_time' => time(),
        );
        $this->db->insert('picture', $data);
        return $this->db->insert_id();
    }

    /**
     * 转换类型编码
     * @param $class
     * @return int
     */
    private function class_encode($class)
    {
        if (is_numeric($class)) {
            return $class;
        }
        switch (strtoupper($class)) {
            case 'PROFILE':
                return 1;
            case 'COVER':
                return 2;
            case 'PHOTO':
                return 3;
            default:
                return 0;
        }
    }

}