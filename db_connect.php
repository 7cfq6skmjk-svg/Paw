<?php
function db_connect(){
  $config=require __DIR__.'/config.php';
  $dsn='mysql:host='.$config['host'].';dbname='.$config['database'].';charset='.$config['charset'];
  try{
    $pdo=new PDO($dsn,$config['username'],$config['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
    return $pdo;
  }catch(Throwable $e){
    $logDir=__DIR__.'/logs';
    if(!is_dir($logDir)){@mkdir($logDir,0777,true);} 
    $log=$logDir.'/db_errors.log';
    $msg=date('c').' '.$e->getMessage()."\n";
    try{file_put_contents($log,$msg,FILE_APPEND);}catch(Throwable $x){}
    $m=strtolower($e->getMessage());
    if(strpos($m,'unknown database')!==false || strpos($m,'[1049]')!==false || strpos($m,'inconnue')!==false){
      try{
        $dsn2='mysql:host='.$config['host'].';charset='.$config['charset'];
        $pdo2=new PDO($dsn2,$config['username'],$config['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        $pdo2->exec('CREATE DATABASE IF NOT EXISTS `'.$config['database'].'` CHARACTER SET '.$config['charset']);
        $pdo=new PDO($dsn,$config['username'],$config['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
        return $pdo;
      }catch(Throwable $e2){
        $msg2=date('c').' '.$e2->getMessage()."\n";
        try{file_put_contents($log,$msg2,FILE_APPEND);}catch(Throwable $x){}
      }
    }
    return null;
  }
}

function db_last_error(){
  $log=__DIR__.'/logs/db_errors.log';
  if(!file_exists($log)) return '';
  $lines=@file($log);
  if(!$lines||!is_array($lines)||count($lines)===0) return '';
  return trim($lines[count($lines)-1]);
}
