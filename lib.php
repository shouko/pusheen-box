<?php

function utf8_strrev($str) {
 preg_match_all('/./us', $str, $ar);
 return implode(array_reverse($ar[0]));
}

function getDatabaseConnection(){
  $dbconf = parse_url(getenv("DATABASE_URL"));
  return new PDO("mysql:host=".$dbconf['host'].";dbname=".explode('/', $dbconf['path'])[1].";charset=utf8", $dbconf['user'], $dbconf['pass']);
}
