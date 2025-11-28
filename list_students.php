<?php
require __DIR__.'/../db_connect.php';
$pdo=db_connect();
$rows=[];$err='';
if($pdo){
  try{$rows=$pdo->query('SELECT id,fullname,matricule,group_id FROM students ORDER BY id DESC')->fetchAll();}
  catch(Throwable $e){$err='Failed to load students';}
}else{$err='Database connection failed';}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Students</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="../public/styles.css" />
</head>
<body>
<div class="navbar"><div class="inner"><div class="brand">Attendance Manager</div><div class="navlinks"><a href="../public/index.html">Home</a><a href="add_student.php">Add Student</a></div></div></div>
<div class="container">
<h1>Students</h1>
<div class="table-wrap">
  <table>
    <thead><tr><th>ID</th><th>Fullname</th><th>Matricule</th><th>Group</th><th>Actions</th></tr></thead>
    <tbody>
    <?php if($err){echo '<tr><td colspan="5" style="color:#b91c1c">'.htmlspecialchars($err,ENT_QUOTES).'</td></tr>';} ?>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?php echo (int)$r['id']; ?></td>
        <td class="name"><?php echo htmlspecialchars($r['fullname'],ENT_QUOTES); ?></td>
        <td><?php echo htmlspecialchars($r['matricule'],ENT_QUOTES); ?></td>
        <td><?php echo (int)$r['group_id']; ?></td>
        <td>
          <a class="btn btn-secondary" href="update_student.php?id=<?php echo (int)$r['id']; ?>">Edit</a>
          <a class="btn btn-warning" href="delete_student.php?id=<?php echo (int)$r['id']; ?>" onclick="return confirm('Delete this student?')">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
</div>
</body>
</html>
