<?php
$url="http://movie.douban.com/subject/19973815/";

$ch2 = curl_init();
		curl_setopt($ch2, CURLOPT_URL, $url);
		curl_setopt($ch2, CURLOPT_HEADER, false);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
		$r = curl_exec($ch2);
		if($r){
			$matches=array();

		$matched=preg_match('|<strong class="ll rating_num" property="v:average">(.*?)</strong>|s', $r,$matches);
			if($matched){
				var_dump($matches[1]);
			}
		
		}	
?>