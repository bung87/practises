<?php
$res=array(
	'name'=>'peng zhou',
	'birthday'=>"1988-2-10"
	);
if(isset($_GET['callback']) && !empty($_GET['callback'])){
	echo $_GET['callback'].'('.json_encode($res).');';
}
?>