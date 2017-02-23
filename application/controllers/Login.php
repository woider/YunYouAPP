<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('tool');
        $this->load->library('yunyou');
        $this->load->helper('cookie');
        $this->load->library('session');
    }

    /**
     * 登录页面入口
     */
    public function index()
    {
        $this->load->view('login');
    }


    /**
     * 发送验证短信
     * @param $phone
     */
    public function send_validate_code($phone)
    {
        $wait = 60; // 设置等待时间
        /* 调试模式 */
        if (ENVIRONMENT !== 'production') {
            $this->session->sms = array(
                'phone' => $phone,
                'code' => '123456',
                'try_times' => 0,
                'time' => time(),
            );
            output_json(true, null, array('wait' => $wait));
            return;
        }
        /* 发送频率限制 */
        if (isset($_SESSION['sms'])) {
            $sms = $this->session->sms;
            if ((time() - $sms['time']) < $wait) {
                output_json(false, '请稍等一会', null);
                return;
            }
        }
        /* 发送验证短信 */
        $this->load->helper('string');
        $code = random_string('numeric', 6);
        $response = $this->yunyou->send_sms($phone, $code);
        /* 输出JSON结果 */
        if (empty($response['result'])) {//发送短信失败
            output_json(false, $response['sub_msg'], null);
        } else {//发送验证短信成功
            output_json(true, null, array('wait' => $wait));
            $this->session->sms = array(
                'phone' => $phone,
                'code' => $code,
                'try_times' => 0,
                'time' => time(),
            );
        }
    }

    /**
     * 身份验证
     * @param $code
     */
    public function authenticate($code)
    {
        /* 验证码校检 */
        try {
            if (!isset($_SESSION['sms'])) {
                throw new Exception('未发送验证码');
            }
            if ($_SESSION['sms']['try_times'] > 9) {
                throw new Exception('验证次数频繁');
            } else {
                $_SESSION['sms']['try_times'] += 1;
            }
            if ($_SESSION['sms']['code'] != $code) {
                throw new Exception('验证码不正确');
            }
            if ($_SESSION['sms']['time'] < time() - 600) {
                throw  new Exception('验证码已过期');
            }
        } catch (Exception $e) {
            output_json(false, $e->getMessage(), null);
            return;
        }
        /* 获取用户ID */
        $this->load->model('user');
        $phone = $_SESSION['sms']['phone'];
        $id = $this->user->query_phone($phone);
        if (empty($id)) {//创建账号
            $user_id = $this->create_new_user($phone);
            $script = 'mui.openWindow("/guide");';
        } else {//保存ID
            $user_id = $id;
            $script = 'mui.back();';
        }
        /* 更新登录状态 */
        $data = array(
            'ip_address' => $this->input->ip_address(),
            'last_login_time' => time(),
        );
        $this->user->update($user_id, $data);
        $this->session->user_id = $user_id;
        /* 登录状态持久化 */
        $data = $this->user->get_data($user_id);
        $xss = sha1($data['phone'] . $data['create_time']);
        set_cookie('user_id', $user_id, 3600 * 24 * 30);
        set_cookie('user_xs', $xss, 3600 * 24 * 30);
        output_json(true, null, array('script' => $script));
        unset($_SESSION['sms']); // 销毁SMS验证信息
    }


    /**
     * 创建用户
     * @param $phone
     * @return int
     */
    private function create_new_user($phone)
    {
        $profile = '/source/img/profile/' . mt_rand(1, 9) . '.jpg';//随机头像
        $nickname = strtoupper(substr(md5($phone), mt_rand(0, 20), 8));//随机昵称
        $data = array(
            'phone' => $phone,
            'profile' => $profile,
            'nickname' => $nickname,
        );
        return $this->user->create($data);
    }

    /* 获取账号状态[待完善] */
    private function account_status($status, $approval, $login, $create)
    {
        if ($status == 0) {
            /* 补偿值=赞同数/使用时间（小时） */
            $compensate = $approval / (($login - $create) / 3600);
            $compensate = ($compensate < 1) ? 1 : $compensate;
            /* 惩罚时间=一个月/补偿值 */
            $punishment = (3600 * 24 * 30) / $compensate;
            /* 计算到期时间 */
            if ($login + $punishment > time()) {
                $due = ceil(($login + $punishment - time()) / (3600 * 24));
                output_json(false, '账号冻结（' . $due . '天）', null);
                return FALSE;
            }
        }
        return TRUE;
    }
}
