<?php
	if (!isset($_GET['character'])) {
		die();
	}
	header('content-type: image/jpeg');
	$character = $_GET['character'];
	$image = imagecreate(50, 50);
	$background_color = imagecolorallocate($image, rand(0, 127), rand(0, 127), rand(0, 127));
	$font_color = imagecolorallocate($img, rand(128, 255), rand(128, 255), rand(128, 255));
	imagejpeg($image);
	imagedestroy($image);

