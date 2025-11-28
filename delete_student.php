<?php
require __DIR__.'/../db_connect.php';
$pdo=db_connect();
if(!$pdo){echo 'Database connection failed';exit;}
$id=isset($_GET['id'])?(int)$_GET['id']:0;
if($id>0){
  try{$st=$pdo->prepare('DELETE FROM students WHERE id=?');$st->execute([$id]);}catch(Throwable $e){}
}
header('Location: list_students.php');
exit;
