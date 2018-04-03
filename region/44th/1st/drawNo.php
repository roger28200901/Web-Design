<?php
header('content-type: image/jpeg');
$no = $_GET['no'];
$img = imagecreate(50, 50);
$gray = imagecolorallocate($img, 150, 150, 150);
$black = imagecolorallocate($img, 0, 0, 0);
imagechar($img, 20, 20, 15, $no, $black);
imagejpeg($img);
imagedestroy($img);