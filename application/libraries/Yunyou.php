<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yunyou
{
    // CodeIgniter 对象
    public static $CI = null;

    public function __construct()
    {
        Yunyou::$CI = &get_instance();
        Yunyou::$CI->load->database();
        Yunyou::$CI->load->helper('cookie');
        Yunyou::$CI->load->library('session');
    }

    /**
     * 检查登录状态
     * @return bool
     */
    public function is_logged()
    {
        /* 查看是否保存SESSION */
        if (isset($_SESSION['user_id'])) {
            return TRUE;
        }
        /* 查看是否保存COOKIE */
        if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_xs'])) {
            $query = Yunyou::$CI->db->select('phone,status,create_time')
                ->from('user')->where('id', $_COOKIE['user_id'])->get();
            if (count($query->result()) === 1) {
                $data = $query->result()[0];
                /* 验证账户STATUS状态 */
                if ($data->status . '' === '0') {
                    /*清除COOKIE退出登录*/
                    delete_cookie('user_id');
                    delete_cookie('user_xs');
                    return FALSE;
                }
                /* 校检COOKIE数据防止攻击 */
                $xss = sha1($data->phone . $data->create_time);
                if ($xss === $_COOKIE['user_xs']) {
                    /* 保存SESSION数据 */
                    $_SESSION['user_id'] = $_COOKIE['user_id'];
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * 发送短信
     * @param $phone
     * @param $code
     * @return array
     */
    public function send_sms($phone, $code)
    {
        /* 加载SDK及配置 */
        Yunyou::$CI->config->load('server');
        require_once FCPATH . 'vendor/TopSdk.php';
        /* 初始化服务 */
        $client = new TopClient();
        $client->appkey = Yunyou::$CI->config->item('appkey');
        $client->secretKey = Yunyou::$CI->config->item('secret');
        /* 设置配置参数 */
        $request = new AlibabaAliqinFcSmsNumSendRequest();
        $request->setSmsType('normal');
        $request->setSmsFreeSignName('云游验证');
        $param = array('code' => $code);
        $request->setSmsParam(json_encode($param));
        $request->setRecNum($phone);
        $request->setSmsTemplateCode('SMS_37745051');
        $response = $client->execute($request);
        /* 返回Array结果 */
        $json = json_encode($response);
        return json_decode($json, true);
    }

}