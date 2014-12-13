<?php
set_time_limit(0);

function get_photo($photoname,$path="")
{
	$url = 'http://img3.douban.com/view/photo/photo/public/'.$photoname;
	$hadle = fopen($url,'r');

	$newPhoto = './images'. DIRECTORY_SEPARATOR .$photoname;
	if($hadle){
		$np = fopen($newPhoto,'a');
		while(!feof($hadle)){
			fwrite($np,fread($hadle,1024*1024),1024*1024);
		}
		fclose($np);
		fclose($hadle);
	}
}
$start = microtime(true);
$name = file_get_contents("http://www.3ovie.com/newmb.php/mbv2/urlname");
file_put_contents("./data.json",$name);

$names = json_decode($name);

foreach ($names as $key => $value) {
	$filename = './images'. DIRECTORY_SEPARATOR .$value;
	if(is_file($filename)) continue;
	sleep(1);
	get_photo($value);
}
$end = microtime(true);
echo $end-$start;


