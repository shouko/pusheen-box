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
	exit(json_encode(array("message" => array("sticker" => $message["attachments"][0]["stickerID"]), "threadID" => $message["threadID"])));
}
$stickers = array(
		"jump" => "144884852352448"
	);
if($message["body"][0] == "*"){
	$sticker_key = explode("*", $message["body"])[1];
	if(isset($stickers[$sticker_key])){
		exit(json_encode(array("sticker" => $stickers[$sticker_key], "threadID" => $message["threadID"])));
	}
}
$responses = array(
		"who are you" => "我是 Pusheen!"
	);
if(isset($responses[$message["body"]])){
	exit(json_encode(array("message" => $responses[$message["body"]], "threadID" => $message["threadID"])));
}
exit(json_encode(array("message" => utf8_strrev($message["body"]), "threadID" => $message["threadID"])));
?>
