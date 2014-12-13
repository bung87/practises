<?php 
include_once(dirname(__file__).DIRECTORY_SEPARATOR ."DooRestClient.php");
function profile($url){
		$xml='';
		//status=listened
		
		$timeout=5;
		$expire=60*60*24*15;
		//strtotime('2 weeks');
	/*	if($this->cache()->get('music@douban')){
			$str=$this->cache()->get('music@douban');
			$xml=simplexml_load_string($str);
			
		}else{*/
			// $client = $this->load()->helper('DooRestClient', true);
			$client = new DooRestClient();
 			$client->connect_to($url)->get();
 
			 if($client->isSuccess()){
			      $xml= $client->xml_result();
			 }
			$albums = $xml->xpath('//db:subject');
			$dom = new DOMDocument("1.0",'utf-8');
			$root=$dom->createElement("albums");
			$dom->appendChild($root);
		foreach ($albums as $k=>$v) {
			$album=$dom->createElement("album");
			$root->appendChild($album);
			$title=$dom->createElement("title");
			$album->appendChild($title);
			$titleval=$dom->createTextNode((string)$v->title);
			$title->appendChild($titleval);
		
			$link=$dom->createElement("link");
			$album->appendChild($link);
			$linkval=$dom->createTextNode((string)$v->link[1]['href']);
			$link->appendChild($linkval);

			$img=$dom->createElement("img");
			$album->appendChild($img);
			$imgval=$dom->createTextNode((string)$v->link[2]['href']);
			$img->appendChild($imgval);
		}
		$content=$dom->saveXML();
			$xml=simplexml_load_string($content);
			// $this->cache()->set('music@douban',$content,$expire);
		// }
			$i=0;
			$albums = $xml->xpath('//album');
			foreach ($albums as $v) {
				$arr[$i]['title']=(string)$v->title;
				$arr[$i]['link']=(string)$v->link;
				$arr[$i]['img']=(string)$v->img;
				$i++;
			}
		$data['albums']=$arr;

		return $data;
	}
	header("Content-type: application/json");
$data1=profile('http://api.douban.com/people/bung/collection?cat=music&status=listening&max-results=50');
$data2=profile('http://api.douban.com/people/bung/collection?cat=music&status=listened&max-results=50');
echo json_encode(array('albums'=>array_merge($data1['albums'],$data2['albums'])));
?>