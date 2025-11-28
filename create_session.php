<?php
require __DIR__.'/../db_connect.php';
$pdo=db_connect();
$success='';$session_id=null;$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $course_id=trim($_POST['course_id']??'');
  $group_id=trim($_POST['group_id']??'');
  $opened_by=trim($_POST['opened_by']??'');
  if($course_id===''||!preg_match('/^\d+$/',$course_id)){$errors['course_id']='Course ID must be numbers only';}
  if($group_id===''||!preg_match('/^\d+$/',$group_id)){$errors['group_id']='Group ID must be numbers only';}
  if($opened_by===''||!preg_match('/^\d+$/',$opened_by)){$errors['opened_by']='Professor ID must be numbers only';}
  if(!$errors){
    if($pdo){
      try{
        $st=$pdo->prepare('INSERT INTO attendance_sessions(course_id,group_id,date,opened_by,status) VALUES(?,?,?,?,?)');
        $st->execute([(int)$course_id,(int)$group_id,date('Y-m-d'),(int)$opened_by,'open']);
        $session_id=(int)$pdo->lastInsertId();
        $success='Session created';
      }catch(Throwable $e){$errors['global']='Failed to create session';}
    }else{$errors['global']='Database connection failed';}
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Create Session</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="../public/styles.css" />
</head>
<body>
<div class="navbar"><div class="inner"><div class="brand">Attendance Manager</div><div class="navlinks"><a href="../students/list_students.php">Students</a></div></div></div>
<div class="container">
<h1>Create Session</h1>
<div class="card" style="max-width:700px">
  <?php if($success){echo '<div class="summary">'.htmlspecialchars($success,ENT_QUOTES).'</div>';
        if($session_id){echo '<div class="summary">Session ID: '.(int)$session_id.'</div>';}} ?>
  <form method="post" action="">
    <div class="row">
      <div>
        <label for="course_id">Course ID</label>
        <input id="course_id" name="course_id" type="text" />
        <div class="error"><?php echo htmlspecialchars($errors['course_id']??'',ENT_QUOTES); ?></div>
      </div>
      <div>
        <label for="group_id">Group ID</label>
        <input id="group_id" name="group_id" type="text" />
        <div class="error"><?php echo htmlspecialchars($errors['group_id']??'',ENT_QUOTES); ?></div>
      </div>
      <div>
        <label for="opened_by">Professor ID</label>
        <input id="opened_by" name="opened_by" type="text" />
        <div class="error"><?php echo htmlspecialchars($errors['opened_by']??'',ENT_QUOTES); ?></div>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Create Session</button>
  </form>
  <?php if(!empty($errors['global'])){echo '<div class="error" style="margin-top:8px">'.htmlspecialchars($errors['global'],ENT_QUOTES).'</div>'; } ?>
</div>
</div>
</body>
</html>
