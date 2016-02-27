<?php
include 'lib.php';

header("Content-type: application/json; charset=utf8");
if(!isset($_POST["message"])){
	http_response_code(400);
	die();
}
$message = json_decode($_POST["message"], 1);
if(!empty($message["attachments"]) && $message["attachments"][0]["type"] == "sticker"){
	$result = array(
		"message" => array(
			"sticker" => $message["attachments"][0]["stickerID"]
		),
		"threadID" => $message["threadID"]
	);
	exit(json_encode($result));
}
$global_responses = array(
		"who are you" => "我是 Pusheen!",
		"*jump*" => array("sticker" => "144884852352448")
	);
$db = getDatabaseConnection();
$command = explode(" ", $message["body"]);
$data = array(
  ':in_id' => $message["threadID"],
  ':in_type' => $message["threadID"] == $message["senderID"] ? 1 : 0
);
isset($command[1]){
  $data[':pattern'] = $command[1];
}
$response = array();
switch($command[0]){
  case "/add":
    if(count($command) < 3){
      break;
    }
    $sql = "INSERT INTO `pusheen_pattern` (`in_id`, `in_type`, `pattern`, `out_type`, `out_body`) VALUES (:in_id, :in_type, :pattern, 'body', :out_body)";
    $stmt = $db->prepare($sql);
    $data[':out_body'] = $command[2];
    $stmt->execute($data);
    $response["threadID"] = $message["threadID"];
    $response["message"] = array(
      "body" => "我知道惹！你說 $command[1] 我說 $command[2]";
    )
    break;
  case "/del":
    if(count($command) < 2){
      break;
    }
    $sql = "DELETE FROM `pusheen_pattern` WHERE `in_id` = :in_id AND `in_type` = :in_type AND `pattern` = :pattern)";
    $stmt = $db->prepare($sql);
    $stmt->execute($data);
    $response["threadID"] = $message["threadID"];
    $response["message"] = array(
      "body" => "我知道惹！";
    )
    break;
  case "/query":
    $sql = "SELECT `pattern`, `out_body` FROM `pusheen_pattern` WHERE `id_id` = :id_in AND `in_type` = :in_type";
    $stmt = $db->prepare($sql);
    $stmt->execute($data);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(empty($result)){
      $response["threadID"] = $message["threadID"];
      $response["message"] = array(
        "body" => "我知道惹！";
      );
    }else{
        // list all
    }
    break;
  default:
    if(isset($global_responses[$message["body"]])){
      $response["message"] = array(
        "body" => $global_responses[$message["body"]]
      ),
      $response["threadID"] = $message["threadID"];
    }
    $sql = "SELECT "
    die("");
}
exit(json_encode($response));
?>
