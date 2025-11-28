<?php
require __DIR__.'/../db_connect.php';
$pdo=db_connect();
if(!$pdo){echo 'Connection failed';exit;}
$sql='CREATE TABLE IF NOT EXISTS students (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  fullname VARCHAR(255) NOT NULL,
  matricule VARCHAR(64) NOT NULL,
  group_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_matricule (matricule)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
$pdo->exec($sql);
echo 'Table students is ready';
