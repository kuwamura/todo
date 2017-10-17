<?php
$id=NULL;
if (! empty($_POST['id']) ) {
  $id = intval($_POST['id']);
  $task = "更新";
} else {
  $task = "保存";
}

// 確認用表示
$DateTime = htmlspecialchars($_POST['DateTime']);
$Subject = htmlspecialchars($_POST['Subject']);
$Detail = htmlspecialchars($_POST['Detail']);
echo<<<EOT
	<table borderwith='1'>
	<tr><th align="left">DateTime</th><td>$DateTime</td></tr>
	<tr><th align="left">Subject</th><td>$Subject</td>
	<tr><th colspan="2" align="left">Detail</th></tr>
	<tr><td></td><td>$Detail</td></tr>
	</table>
EOT;
//var_dump($_POST);

$datetime = ($_POST['DateTime']);
$subject = ($_POST['Subject']);
$detail = ($_POST['Detail']);
if (isset($id)) {
  try {
  
    $num = updateTodo($pdo, $id, $datetime, $subject, $detail);

  } catch (\PDOException $e) {
    error_log( "\PDO::例外: " . $e->getMessage() );
    return;
  }
  error_log("UPDATE: affected lins = $num");
} else {
  try {

    $id = insertTodo($pdo, $datetime, $subject, $detail);

  } catch (\PDOException $e) {
    error_log( "\PDO::例外: " . $e->getMessage() );
    return;
  }
  error_log("INSERT: new id = $id");
}
?>
<center>
<table borderwith='1'>
  <tr>
  <td>[<a href="<?php {echo $_SERVER['SCRIPT_NAME'];}?>?mode=list">一覧</a>]</td>
  <td>[<a href="<?php {echo $_SERVER['SCRIPT_NAME'];}?>?mode=edit&id=<?php {echo $id;}?>">再編集</a>]</td>
  </tr>
</table>
</center>
