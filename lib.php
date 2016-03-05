<?php

function utf8_strrev($str) {
 preg_match_all('/./us', $str, $ar);
 return implode(array_reverse($ar[0]));
}

function getDatabaseConnection(){
  $dburl = getenv("DATABASE_URL") == "" ? 'mysql://pusheen:@localhost/pusheen' : getenv("DATABASE_URL");
  $dbconf = parse_url($dburl);
  return new PDO("mysql:host=".$dbconf['host'].";dbname=".explode('/', $dbconf['path'])[1].";charset=utf8", $dbconf['user'], $dbconf['pass']);
}

function getBotName(){
  $list = array(" Pusheen", "學代會本體");
  if(isset($list[(int)getenv("BOT_ID")])){
    return $list[(int)getenv("BOT_ID")];
  }else{
    return " Pusheen";
  }
}
