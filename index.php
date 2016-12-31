<?php
include 'lib.php';

header("Content-type: application/json; charset=utf8");
if(!isset($_POST['message'])){
	http_response_code(400);
	die();
}
$message = json_decode($_POST['message'], 1);
if(!isset($message['body'])){
	die("");
}
$global_responses = array(
		"who are you" => "我是".getBotName()."!",
		"*jump*" => array("sticker" => "144884852352448"),
		"/help" => "你好！我是".getBotName()."
我可以當你的好幫手 <3 以下是功能簡介

/add A B
你說 A 我說 B
/del A
刪除這條規則
/query
查詢現有規則
/help
顯示這份說明"
	);
$db = getDatabaseConnection();
$command = explode(" ", $message['body']);
$data = array(
  ':in_id' => $message['threadID'],
  ':in_type' => $message['threadID'] == $message['senderID'] ? 1 : 0,
  ':pattern' => isset($command[1]) ? $command[1] : ""
);
$response = array("threadID" => $message['threadID']);
switch($command[0]){
  case "/add":
    if(count($command) < 3){
      break;
    }
    $sql = "INSERT INTO `pusheen_pattern` (`in_id`, `in_type`, `pattern`, `out_type`, `out_body`) VALUES (:in_id, :in_type, :pattern, :out_type, :out_body)";
    $stmt = $db->prepare($sql);
    $stmt->execute(array(
      ':in_id' => $data[':in_id'],
      ':in_type' => $data[':in_type'],
      ':pattern' => $data[':pattern'],
      ':out_body' => $command[2],
      ':out_type' => filter_var($command[2], FILTER_VALIDATE_URL) ? 'url' : (isset($command[3]) && $command[3] == 'sticker') ? 'sticker' : 'body'
    ));
    $response['message'] = array(
      "body" => "我知道惹！你說 $command[1] 我說 $command[2]"
    );
    break;
  case "/del":
    if(count($command) < 2){
      break;
    }
/*
    $sql = "DELETE FROM `pusheen_pattern` WHERE `in_id` = :in_id AND `in_type` = :in_type AND `pattern` = :pattern)";
    $stmt = $db->prepare($sql);
    $stmt->execute(array(
      ':in_id' => $data[':in_id'],
      ':in_type' => $data[':in_type'],
      ':pattern' => $data[':pattern']
    ));
*/
    $response['message'] = array(
      "body" => "還沒 implement，幫 QQ"
    );
    break;
  case "/query":
    $sql = "SELECT `pattern`, `out_body` FROM `pusheen_pattern` WHERE `in_id` = :in_id AND `in_type` = :in_type";
    $stmt = $db->prepare($sql);
    $stmt->execute(array(
      ':in_id' => $data[':in_id'],
      ':in_type' => $data[':in_type']
    ));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(empty($result)){
      $response['message'] = array(
        "body" => "我知道惹！"
      );
    }else{
      $response['message'] = array(
        "body" => "以下是你的 pattern\n\n"
      );
      foreach($result as $row){
        $response['message']['body'] .= $row['pattern']." ".$row['out_body']."\n";
      }
    }
    break;
  case "/weather":
    $weather_api = 'http://weather.ntustudents.org/api';
    $weather = json_decode(file_get_contents($weather_api), 1);
    $response['message'] = array(
      "body" => sprintf(
        "現在氣溫 %s °C\n今日氣溫 %s ~ %s °C\n風向 %s m/s %s°\n氣壓 %s hPa\n濕度 %s%%\n降雨強度 %s mm/h\n本日降雨 %s mm\n\n%s\n%s",
        $weather['temperature'],
        $weather['temp_min'], $weather['temp_max'],
        $weather['wind_speed'], $weather['wind_direction'],
        $weather['pressure'],
        $weather['humidity'],
        $weather['rain'], $weather['rain_day'],
        $weather['date'], $weather['provider']
      )
    );
    break;
  default:
    if(isset($global_responses[$message['body']])){
      $response['message'] = $global_responses[$message['body']];
			break;
    }
    $sql = "SELECT `out_type`, `out_body` FROM `pusheen_pattern` WHERE `in_id` IN(:in_id, :sender_id) AND `pattern` = :pattern ORDER BY RAND() LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute(array(
      ':in_id' => $data[':in_id'],
      ':pattern' => $message['body'],
      ':sender_id' => $message['senderID']
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if(empty($result)){
      die("");
    }
    $response['message'] = array(
      $result['out_type'] => $result['out_body']
    );
}
exit(json_encode($response, JSON_UNESCAPED_UNICODE));
?>
