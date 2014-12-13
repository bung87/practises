<?php
$form='';
foreach ($_GET as $key => $value) {
	$label='<p><label>'.$key.'<input type="text" name="'.$key.'" value="'.$value.'"></label></p>';
	$form.=$label;
}
echo <<<EOT
<html>
<head>
	<title></title>
</head>
<body>
<form action="http://localhost/catchyou.php" method="get">
$form
<input type="submit" value="提交">
</form>
</body>
</html>
EOT;


?>