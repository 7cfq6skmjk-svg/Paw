<?php
require __DIR__.'/db_connect.php';
$pdo=db_connect();
if($pdo instanceof PDO){
  echo 'Connection successful';
}else{
  echo 'Connection failed';
}
