<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * JSON输出辅助工具
 * @param $status
 * @param $message
 * @param $result
 */
function output_json($status, $message, $result)
{
    header('Content-Type:text/json');
    echo json_encode(array(
        'status' => $status,
        'message' => $message,
        'result' => $result,
    ));
}

/**
 * 变量打印工具
 * @param $value
 */
function dump($value)
{
    if (is_null($value) || is_bool($value)) {
        var_dump($value);
        return;
    }
    if (is_object($value) || is_array($value)) {
        print_r($value);
        return;
    }
    if (is_string($value) || is_numeric($value)) {
        echo $value;
        return;
    }
}

/**
 * 输出错误页面
 * @param $error
 * @param null $param
 */
function error_exit($error, $param = null)
{
    $param = !empty($param) ? $param : array(
        'font-size' => '5rem',
        'text-align' => 'center',
    );
    $style = '';
    foreach ($param as $key => $value) {
        $style .= $key . ': ' . $value . '; ';
    }
    exit("<p style='{$style}'>{$error}</p>");
}


/**
 * 转换文本格式
 * @param $text
 * @param $length
 * @param null $tip
 * @return string
 */
function build_text($text, $length, $tip = null)
{
    /* 去除多余空行 */
    $text = preg_replace('/(?:\n)(\s*\n)/', '\n', $text);
    $text = preg_replace('/\s+/', ' ', $text);
    /* 判断字符长度 */
    if (mb_strlen($text) > $length) {
        $text = htmlspecialchars($text);
        $text = mb_substr($text, 0, $length) . $tip;
    } else {
        /* 转义特殊字符 */
        $text = htmlspecialchars($text);
    }
    /* 以br标签代替\n */
    $text = str_replace('\n', '<br>', $text);
    return $text;
}