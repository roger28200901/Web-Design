<?php

header('content-type:image/jpeg');

$character = $_GET['character'];

$image = imagecreate(50, 50);

$background_color = imagecolorallocate($image, 0, 0, 0);
$font_color = imagecolorallocate($image, 155, 155, 155);

imagechar($image, 20, 20, 15, $character, $font_color);

imagejpeg($image);
imagedestroy($image);

