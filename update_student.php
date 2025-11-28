<?php
require __DIR__.'/../db_connect.php';
$pdo=db_connect();
if(!$pdo){echo 'Database connection failed';exit;}
$id=isset($_GET['id'])?(int)$_GET['id']:0;
if($id<=0){echo 'Invalid ID';exit;}
$errors=[];$success='';
$fullname='';$matricule='';$group_id='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $fullname=trim($_POST['fullname']??'');
  $matricule=trim($_POST['matricule']??'');
  $group_id=trim($_POST['group_id']??'');
  if($fullname===''||!preg_match('/^[A-Za-z ]+$/',$fullname)){$errors['fullname']='Full name must contain only letters and spaces';}
  if($matricule===''||!preg_match('/^[A-Za-z0-9-]+$/',$matricule)){$errors['matricule']='Matricule must be letters/numbers';}
  if($group_id===''||!preg_match('/^\d+$/',$group_id)){$errors['group_id']='Group ID must be numbers only';}
  if(!$errors){
    try{
      $st=$pdo->prepare('UPDATE students SET fullname=?, matricule=?, group_id=? WHERE id=?');
      $st->execute([$fullname,$matricule,(int)$group_id,$id]);
      $success='Student updated';
    }catch(Throwable $e){$errors['global']='Failed to update student';}
  }
}else{
  $st=$pdo->prepare('SELECT fullname,matricule,group_id FROM students WHERE id=?');
  $st->execute([$id]);
  $row=$st->fetch();
  if(!$row){echo 'Student not found';exit;}
  $fullname=$row['fullname'];
  $matricule=$row['matricule'];
  $group_id=(string)$row['group_id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edit Student</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="../public/styles.css" />
</head>
<body>
<div class="navbar"><div class="inner"><div class="brand">Attendance Manager</div><div class="navlinks"><a href="list_students.php">Students</a></div></div></div>
<div class="container">
<h1>Edit Student</h1>
<div class="card" style="max-width:700px">
  <?php if($success){echo '<div class="summary">'.htmlspecialchars($success,ENT_QUOTES).'</div>'; } ?>
  <form method="post" action="?id=<?php echo $id; ?>">
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
    <button type="submit" class="btn btn-primary">Update</button>
  </form>
  <?php if(!empty($errors['global'])){echo '<div class="error" style="margin-top:8px">'.htmlspecialchars($errors['global'],ENT_QUOTES).'</div>'; } ?>
</div>
</div>
</body>
</html>
