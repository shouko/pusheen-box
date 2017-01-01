<?php
include 'lib.php';

$db = getDatabaseConnection();
$db->query('CREATE TABLE `pusheen_pattern` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `in_id` bigint(20) NOT NULL,
  `in_type` tinyint(4) NOT NULL,
  `pattern` varchar(30) NOT NULL,
  `out_type` varchar(30) NOT NULL,
  `out_body` varchar(255) NOT NULL,
  `disabled` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;');

echo 'ok';
