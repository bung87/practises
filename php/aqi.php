<?php 
/*
{"StationName":"\u5929\u575b",
"Time":"05\/08\/2013 16:00:00",
"AQIName":"PM2.5",
"AQIValue":"210",
"QLevel":"\u4e94\u7ea7",
"Quality":"\u91cd\u5ea6\u6c61\u67d3",
"RGB":"#99004C"}
*/
$pmapi="http://zx.bjmemc.com.cn/ashx/Data.ashx?Action=GetAQIClose1h";
function saveData(){
	global $pmapi;
	$ch2 = curl_init();
	curl_setopt($ch2, CURLOPT_URL, $pmapi);
	curl_setopt($ch2, CURLOPT_HEADER, false);
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
	$r = curl_exec($ch2);
	$tiantan=current(array_slice(json_decode($r,true),21,1));
	file_put_contents('aqi.json', $tiantan);
	return $tiantan;
}
	if(file_exists('aqi.json') && intval(filesize('aqi.json'))){
			$json=file_get_contents('aqi.json');
			if($json ===false) $json=saveData();
			$buildDate=json_decode($json,true);
			$buildDate=$buildDate['Time'];
			$now=time();
			if($now-strtotime($buildDate) >=3600) $json=saveData();
		
	}else{
		$json=saveData();
	}



echo json_encode($json);
/*extract($tiantan);
echo $StationName."\n";
echo date("Y-m-d H:i:s",strtotime($Time))."\n";
echo $AQIName."\n";
echo $AQIValue."\n";
echo $QLevel."\n";
echo $Quality."\n";
echo $RGB."\n";*/

?>