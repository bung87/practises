<?php 

list($width,$height)=getimagesize("poster.jpg");
$newwidth=640;
$newheight=427;
$background=imagecreatetruecolor($newwidth, $newheight);
$img=imagecreatefromjpeg("poster.jpg");
$thumb=imagecreatetruecolor($newwidth, $newheight);
imagecopyresized($thumb, $img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

imagecopyresampled($background, $thumb, 0, 0, 0, 0, $newwidth, $newheight, $newwidth, $newheight);

imagefilter($background, IMG_FILTER_GAUSSIAN_BLUR);
imagefilter($background, IMG_FILTER_GAUSSIAN_BLUR);
imagefilter($background, IMG_FILTER_GAUSSIAN_BLUR);
imagefilter($background, IMG_FILTER_GAUSSIAN_BLUR);
imagefilter($background, IMG_FILTER_GAUSSIAN_BLUR);

imagejpeg($background,"poster2_blur.jpg");

?>