<?php
require __DIR__.'/../db_connect.php';
$pdo=db_connect();
if(!$pdo){echo 'Connection failed';exit;}
$sql='CREATE TABLE IF NOT EXISTS attendance_sessions (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  course_id INT UNSIGNED NOT NULL,
  group_id INT UNSIGNED NOT NULL,
  date DATE NOT NULL,
  opened_by INT UNSIGNED NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT "open",
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
$pdo->exec($sql);
echo 'Table attendance_sessions is ready';
