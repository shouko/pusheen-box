<?php
function utf8_strrev($str){
 preg_match_all('/./us', $str, $ar);
 return implode(array_reverse($ar[0]));
}

header("Content-type: application/json; charset=utf8");
if(!isset($_POST["message"])){
	http_response_code(400);
	die();
}
$message = json_decode($_POST["message"], 1);
if(!empty($message["attachments"]) && $message["attachments"][0]["type"] == "sticker"){
	echo json_encode(array("message" => array("sticker" => $message["attachments"][0]["stickerID"]), "threadID" => $message["threadID"]));
}else if($message["body"] == "who are you"){
	echo json_encode(array("message" => "我是 Pusheen!", "threadID" => $message["threadID"]));
}else{
	echo json_encode(array("message" => utf8_strrev($message["body"]), "threadID" => $message["threadID"]));	
}
?>
