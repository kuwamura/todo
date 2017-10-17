<?php
$id=NULL;
if (isset($_GET['id']) ) {
  $id = intval($_GET['id']);
  // 指定idのToDoを取得
  try {

    $todo = getTodoById($pdo, $id);

  } catch (\PDOException $e) {
    error_log( "\PDO::例外: " . $e->getMessage() );
    return;
  }
  $title = "編集($id)";
  $datetime = htmlspecialchars($todo['datetime']);
  $subject = htmlspecialchars($todo['subject']);
  $detail = htmlspecialchars($todo['detail']);
} else {
  $title = "作成";
  $datetime = $now;		// ひな形としてデフォルト値に現行時刻
  $subject = '';
  $detail = '';
}

// 以下、フォーム表示
?>
<center>
<font size="5"><?php echo $title;?></font>
</center>
<table>
<tr><td>
<form action="<?php echo $_SERVER['SCRIPT_NAME'];?>?mode=save" method="post">
  <input type="hidden" name="id" value="<?php echo $id; ?>"/>
  <font size=-1><tt><b>日時</b></tt></font><br/>
  <input type="text" name="DateTime" size="19" value="<?php echo $datetime;?>"/><br/>
  <font size=-1><tt><b>件名</b></tt></font><br/>
  <input type="text" name="Subject" size="56" value="<?php echo $subject;?>"/><br/>
  <font size=-1><tt><b>詳細</b></tt></font><br/>
  <textarea name="Detail" rows="24" cols="72"><?php echo $detail;?></textarea><br><br>
  <center><input type="submit" name="SaveOpt" value="キャンセル"/>
	  <input type="submit" name="SaveOpt" value="保存"/></center>
</form>
</td></tr>
</table>
