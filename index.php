<?php
/**
 * 版权所有 (c) 2024 luoli233
 * 保留所有权利
 * 
 * 本代码受版权法保护，未经授权，禁止复制、分发或修改。
 * 
 * @作者: luoli233
 * @创建日期: 2024-05-31
 * @版本: 1.0
 */

// 获取文件的 MIME 类型
function get_mime_type($filename) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $filename);
    finfo_close($finfo);
    return $mime_type;
}

// 图片文件夹的相对路径
$imageFolder = 'images'; // 图片文件夹和 PHP 文件在同一个目录下

// 遍历图片文件夹，获取所有图片路径
$imagePaths = array();
if (is_dir($imageFolder)) {
    $files = scandir($imageFolder);
    foreach ($files as $file) {
        $filePath = $imageFolder . '/' . $file;
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        if (is_file($filePath) && in_array($extension, array('jpg', 'jpeg', 'png', 'gif'))) { // 检查文件扩展名
            $imagePaths[] = $filePath;
        }
    }
}

// 获取返回类型
$returnType = isset($_GET['return']) ? $_GET['return'] : '';

// 从数组中随机选择一张图片
$randomImage = null;
if (!empty($imagePaths)) {
    $randomIndex = array_rand($imagePaths);
    $randomImage = $imagePaths[$randomIndex];
}

// 如果有随机图片，则处理图片内容
if ($randomImage) {
    $imageData = file_get_contents($randomImage);
    $imageType = get_mime_type($randomImage);

    if ($returnType === 'json') {
        // 返回 JSON 格式
        $result = array(
            "code" => 200,
            "imgurl" => $randomImage,
            "mime_type" => $imageType
        );
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        // 直接返回图片
        header('Content-Type: ' . $imageType);
        echo $imageData;
    }
} else {
    // 如果没有随机图片，则输出一个默认的图片
    $defaultImage = 'path/to/default/image.jpg'; // 替换为你的默认图片路径
    $imageData = file_get_contents($defaultImage);
    $imageType = get_mime_type($defaultImage);
    header('Content-Type: ' . $imageType);
    echo $imageData;
}

?>
