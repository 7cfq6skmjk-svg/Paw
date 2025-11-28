<?php
require __DIR__.'/../db_connect.php';
$pdo=db_connect();
$success='';$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $session_id=trim($_POST['session_id']??'');
  if($session_id===''||!preg_match('/^\d+$/',$session_id)){$errors['session_id']='Session ID must be numbers only';}
  if(!$errors){
    if($pdo){
      try{
        $st=$pdo->prepare('UPDATE attendance_sessions SET status=? WHERE id=?');
        $st->execute(['closed',(int)$session_id]);
        $success='Session closed';
      }catch(Throwable $e){$errors['global']='Failed to close session';}
    }else{$errors['global']='Database connection failed';}
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Close Session</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="../public/styles.css" />
</head>
<body>
<div class="navbar"><div class="inner"><div class="brand">Attendance Manager</div><div class="navlinks"><a href="create_session.php">Create Session</a></div></div></div>
<div class="container">
<h1>Close Session</h1>
<div class="card" style="max-width:700px">
  <?php if($success){echo '<div class="summary">'.htmlspecialchars($success,ENT_QUOTES).'</div>'; } ?>
  <form method="post" action="">
    <div class="row">
      <div>
        <label for="session_id">Session ID</label>
        <input id="session_id" name="session_id" type="text" />
        <div class="error"><?php echo htmlspecialchars($errors['session_id']??'',ENT_QUOTES); ?></div>
      </div>
    </div>
    <button type="submit" class="btn btn-warning">Close Session</button>
  </form>
  <?php if(!empty($errors['global'])){echo '<div class="error" style="margin-top:8px">'.htmlspecialchars($errors['global'],ENT_QUOTES).'</div>'; } ?>
</div>
</div>
</body>
</html>
