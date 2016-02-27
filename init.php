<?php
include 'lib.php';

$db = getDatabaseConnection();
$db->query('CREATE TABLE IF NOT EXISTS `pusheen_pattern` (
  `in_id` bigint(20) NOT NULL,
  `in_type` tinyint(4) NOT NULL,
  `pattern` varchar(30) NOT NULL,
  `out_type` varchar(30) NOT NULL,
  `out_body` varchar(255) NOT NULL,
  CONSTRAINT pk_pattern PRIMARY KEY (`in_id`,`in_type`,`pattern`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

echo 'ok';
