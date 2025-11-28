<?php
require __DIR__.'/../db_connect.php';
$errors=[];$fullname='';$matricule='';$group_id='';$success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $fullname=trim($_POST['fullname']??'');
  $matricule=trim($_POST['matricule']??'');
  $group_id=trim($_POST['group_id']??'');
  if($fullname===''||!preg_match('/^[A-Za-z ]+$/',$fullname)){$errors['fullname']='Full name must contain only letters and spaces';}
  if($matricule===''||!preg_match('/^[A-Za-z0-9-]+$/',$matricule)){$errors['matricule']='Matricule must be letters/numbers';}
  if($group_id===''||!preg_match('/^\d+$/',$group_id)){$errors['group_id']='Group ID must be numbers only';}
  if(!$errors){
    $pdo=db_connect();
    if($pdo){
      try{
        $st=$pdo->prepare('INSERT INTO students(fullname,matricule,group_id) VALUES(?,?,?)');
        $st->execute([$fullname,$matricule,(int)$group_id]);
        $success='Student added';
        $fullname='';$matricule='';$group_id='';
      }catch(Throwable $e){
        $errors['global']='Failed to add student';
      }
    }else{$errors['global']='Database connection failed';}
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Add Student</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="../public/styles.css" />
</head>
<body>
<div class="navbar"><div class="inner"><div class="brand">Attendance Manager</div><div class="navlinks"><a href="../public/index.html">Home</a><a href="list_students.php">Students</a></div></div></div>
<div class="container">
<h1>Add Student</h1>
<div class="card" style="max-width:700px">
  <?php if($success){echo '<div id="addConfirm" class="summary">'.htmlspecialchars($success,ENT_QUOTES).'</div>'; } ?>
  <form method="post" action="">
    <div class="row">
      <div>
        <label for="fullname">Full Name</label>
        <input id="fullname" name="fullname" type="text" value="<?php echo htmlspecialchars($fullname,ENT_QUOTES); ?>" />
        <div class="error"><?php echo htmlspecialchars($errors['fullname']??'',ENT_QUOTES); ?></div>
      </div>
      <div>
        <label for="matricule">Matricule</label>
        <input id="matricule" name="matricule" type="text" value="<?php echo htmlspecialchars($matricule,ENT_QUOTES); ?>" />
        <div class="error"><?php echo htmlspecialchars($errors['matricule']??'',ENT_QUOTES); ?></div>
      </div>
      <div>
        <label for="group_id">Group ID</label>
        <input id="group_id" name="group_id" type="text" value="<?php echo htmlspecialchars($group_id,ENT_QUOTES); ?>" />
        <div class="error"><?php echo htmlspecialchars($errors['group_id']??'',ENT_QUOTES); ?></div>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Add Student</button>
  </form>
  <?php if(!empty($errors['global'])){echo '<div class="error" style="margin-top:8px">'.htmlspecialchars($errors['global'],ENT_QUOTES).'</div>'; } ?>
</div>
</div>
</body>
</html>
