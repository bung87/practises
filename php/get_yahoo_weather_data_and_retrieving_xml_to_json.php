<?php

date_default_timezone_set("Asia/Chongqing");

$weatherinfo[0]="龙卷风";
$weatherinfo[1]="热带风暴";
$weatherinfo[2]="飓风";
$weatherinfo[3]="severe thunderstorms Severe Thunderstorms";
$weatherinfo[4]="雷雨";
$weatherinfo[5]="雨夹雪";
$weatherinfo[6]="雨和雨夹雪";
$weatherinfo[7]="混合雨雪";
$weatherinfo[8]="冻毛雨";
$weatherinfo[9]="drizzle";
$weatherinfo[10]="冻雨";
$weatherinfo[11]="阵雨";
$weatherinfo[12]="阵雨";
$weatherinfo[13]="阵雪";
$weatherinfo[14]="小阵雪";
$weatherinfo[15]="高吹雪";
$weatherinfo[16]="雪";
$weatherinfo[17]="冰雹";
$weatherinfo[18]="雨夹雪";
$weatherinfo[19]="灰尘";
$weatherinfo[20]="有雾";
$weatherinfo[21]="薄雾";
$weatherinfo[22]="大雾";
$weatherinfo[23]="大风";
$weatherinfo[24]="有风";
$weatherinfo[25]="寒冷";
$weatherinfo[26]="多云";
$weatherinfo[27]="大部多云";
$weatherinfo[28]="大部多云";
$weatherinfo[29]="局部多云";
$weatherinfo[30]="局部多云";
$weatherinfo[31]="晴朗";
$weatherinfo[32]="晴朗";
$weatherinfo[33]="晴";
$weatherinfo[34]="晴";
$weatherinfo[35]="雨夹冰雹";
$weatherinfo[36]="热";
$weatherinfo[37]="局部雷雨";
$weatherinfo[38]="偶有雷雨";
$weatherinfo[39]="偶有雷雨";
$weatherinfo[40]="零星阵雨";
$weatherinfo[41]="大雪";
$weatherinfo[42]="零星阵雪";
$weatherinfo[43]="大雪";
$weatherinfo[44]="局部多云";
$weatherinfo[45]="雷阵雨";
$weatherinfo[46]="阵雪";
$weatherinfo[47]="局部雷阵雨";
$weatherinfo[3200]="not available";

$api="http://weather.yahooapis.com/forecastrss?w=2151330&u=c";
function saveData(){
	global $api;
	$ch2 = curl_init();
	curl_setopt($ch2, CURLOPT_URL, $api);
	curl_setopt($ch2, CURLOPT_HEADER, false);
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
	$r = curl_exec($ch2);
	$xml=simplexml_load_string($r);
	$xml->asXML('weather.xml');
	return $xml;
}
$now=time();


if(file_exists('weather.xml') && intval(filesize('weather.xml'))){
		$xml=simplexml_load_file('weather.xml');
		if($xml ===false) $xml=saveData();
		$buildDate=str_replace('CST','',array_shift($xml->xpath('//lastBuildDate')));
		if($now-strtotime($buildDate) >=3600) $xml=saveData();
}else{
	$xml=saveData();
}


$r_location=array();
$location=current($xml->xpath("//yweather:location"));
$r_units=array();
$units=current($xml->xpath('//yweather:units'));
foreach ($units->attributes() as $key => $value) {
	$r_units[$key]=(string)$value;
}
foreach ($location->attributes() as $key => $value) {
	$r_location[$key]=(string)$value;
}

$r_wind=array();
$wind=current($xml->xpath("//yweather:wind"));
foreach ($wind->attributes() as $key => $value) {
	$r_wind[$key]=(string)$value;
}
$r_atmosphere=array();
$atmosphere=current($xml->xpath("//yweather:atmosphere"));
foreach ($atmosphere->attributes() as $key => $value) {
	$r_atmosphere[$key]=(string)$value;
}
$r_astronomy=array();
$astronomy=current($xml->xpath("//yweather:astronomy"));
foreach ($astronomy->attributes() as $key => $value) {
	$r_astronomy[$key]=(string)$value;
}
$r_condition=array();
$condition=current($xml->xpath("//yweather:condition"));
foreach ($condition->attributes() as $key => $value) {
	$r_condition[$key]=(string)$value;
}

$timestamp = strtotime(str_replace('CST', '', $condition->attributes()->date));
$pubdate = date("Y-m-d H:i:s",$timestamp);

$forecast=$xml->xpath('//yweather:forecast');
$forecasts=array();
foreach ($forecast as $key => $value) {
	$forecasts[$key]['day'] = (string)$value->attributes()->day;
	$forecasts[$key]['date'] = (string)$value->attributes()->date;
	$forecasts[$key]['low'] = (string)$value->attributes()->low;
	$forecasts[$key]['high'] = (string)$value->attributes()->high;
	$forecasts[$key]['text'] = current(array_slice($weatherinfo, (int)$value->attributes()->code,1));
	$forecasts[$key]['code'] = (string)$value->attributes()->code;

}
$r_wind['speed'].=$r_units['speed'];
$r_atmosphere['pressure'].=$r_units['pressure'];
$result=array(
	'pub'=>$pubdate,
	'name'=>$r_location['city'],
	'location'=>$r_location,
		'wind'=>$r_wind,
		'atmosphere'=>$r_atmosphere,
		'astronomy'=>$r_astronomy,
		'condition'=>$r_condition,
		'forecasts'=>$forecasts
	);
echo json_encode($result);

?>