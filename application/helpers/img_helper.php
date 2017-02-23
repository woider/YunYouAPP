<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * BASE64编码转图片
 * @param $base64
 * @param $path
 * @return bool
 */
function base64_to_image($base64, $path)
{
    /* 校检图片类型 */
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)) {
        if (strtoupper($result[2]) !== 'JPEG') return FALSE;
    } else {
        return FALSE;// BASE64格式不正确
    }
    /* 创建文件夹 */
    $directory = dirname($path);
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }
    /* 解码写入文件 */
    $binary = base64_decode(str_replace($result[1], '', $base64));
    return boolval(file_put_contents($path, $binary));
}

/**
 * 居中裁剪图片
 * @param $file
 * @param $width
 * @param $height
 * @param $output
 * @return bool
 */
function image_center_crop($file, $width, $height, $output)
{
    if (!file_exists($file)) return false;
    /* 根据类型载入图像 */
    switch (exif_imagetype($file)) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($file);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($file);
            break;
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($file);
            break;
    }
    if (!isset($image)) return false;
    /* 计算裁剪宽度和高度 */
    $target_w = $width;
    $target_h = $height;
    $source_w = imagesx($image);
    $source_h = imagesy($image);
    $flag = boolval(($source_w / $source_h) > ($target_w / $target_h));
    $resize_w = $flag ? ($source_w * $target_h) / $source_h : $target_w;
    $resize_h = !$flag ? ($source_h * $target_w) / $source_w : $target_h;
    $start_x = $flag ? ($resize_w - $target_w) / 2 : 0;
    $start_y = !$flag ? ($resize_h - $target_h) / 2 : 0;
    /* 绘制居中图像 */
    $resize_img = imagecreatetruecolor($resize_w, $resize_h);
    $background = imagecolorallocate($resize_img, 255, 255, 255);
    imagefill($resize_img, 0, 0, $background);//填充背景色
    imagecopyresampled($resize_img, $image, 0, 0, 0, 0, $resize_w, $resize_h, $source_w, $source_h);
    $target_img = imagecreatetruecolor($target_w, $target_h);
    imagecopy($target_img, $resize_img, 0, 0, $start_x, $start_y, $resize_w, $resize_h);
    /* 将图像保存至文件 */
    if (!file_exists(dirname($output))) mkdir(dirname($output), 0777, true);
    imagejpeg($target_img, $output, 100);
    return file_exists($output);
}
