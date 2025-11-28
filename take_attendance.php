<?php
$students=[];$success='';$error_today='';
$path=__DIR__.'/students.json';
if(file_exists($path)){$j=file_get_contents($path);$a=json_decode($j,true);if(is_array($a)){$students=$a;}}
if($_SERVER['REQUEST_METHOD']==='POST'){
  $date=date('Y-m-d');
  $out=__DIR__.'/attendance_'.$date.'.json';
  if(file_exists($out)){$error_today='Attendance for today has already been taken.';}else{
    $data=[];
    foreach($students as $s){
      $sid='';
      if(isset($s['student_id'])){$sid=(string)$s['student_id'];}
      elseif(isset($s['id'])){$sid=(string)$s['id'];}
      if($sid==='')continue;
      $key='status_'.$sid;
      $val=$_POST[$key]??'absent';
      $status=($val==='present')?'present':'absent';
      $data[]=['student_id'=>$sid,'status'=>$status];
    }
    file_put_contents($out,json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    $success='Attendance saved for '.$date;
  }
}
function displayName($s){
  if(isset($s['name']))return $s['name'];
  $f=isset($s['first'])?$s['first']:''; $l=isset($s['last'])?$s['last']:'';
  $n=trim($f.' '.$l); return $n!==''?$n:'Student';
}
function studentId($s){
  if(isset($s['student_id']))return (string)$s['student_id'];
  if(isset($s['id']))return (string)$s['id'];
  return '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Take Attendance (PHP)</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="../public/styles.css" />
</head>
<body>
<div class="navbar"><div class="inner"><div class="brand">Attendance Manager</div><div class="navlinks"><a href="../public/index.html">Home</a><a href="../public/attendance.html">Absences</a><a href="../public/add.html">Add Student</a><a href="../public/report.html">Report</a></div></div></div>
<div class="container">
<h1>Take Attendance (PHP)</h1>
<div class="card" style="max-width:900px">
  <?php if($error_today){echo '<div class="error">'.htmlspecialchars($error_today,ENT_QUOTES).'</div>';} if($success){echo '<div class="summary">'.htmlspecialchars($success,ENT_QUOTES).'</div>';} ?>
  <form method="post" action="">
    <table style="width:100%;border-collapse:collapse">
      <thead>
        <tr>
          <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb">Student</th>
          <th style="padding:10px;border-bottom:1px solid #e5e7eb">Present</th>
          <th style="padding:10px;border-bottom:1px solid #e5e7eb">Absent</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($students as $s): $sid=studentId($s); if($sid==='') continue; ?>
        <tr>
          <td style="padding:10px;border-bottom:1px solid #f1f5f9;text-align:left;font-weight:600">
            <?php echo htmlspecialchars(displayName($s),ENT_QUOTES); ?>
            <span style="color:#6b7280;margin-left:8px">ID: <?php echo htmlspecialchars($sid,ENT_QUOTES); ?></span>
          </td>
          <td style="padding:10px;border-bottom:1px solid #f1f5f9;text-align:center">
            <input type="radio" name="<?php echo 'status_'.htmlspecialchars($sid,ENT_QUOTES); ?>" value="present" checked />
          </td>
          <td style="padding:10px;border-bottom:1px solid #f1f5f9;text-align:center">
            <input type="radio" name="<?php echo 'status_'.htmlspecialchars($sid,ENT_QUOTES); ?>" value="absent" />
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <button type="submit" class="btn btn-primary" style="margin-top:12px">Save Attendance</button>
  </form>
</div>
<footer>Attendance is saved to attendance_YYYY-MM-DD.json on the server.</footer>
</div>
</body>
</html>
