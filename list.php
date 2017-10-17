<?php
/*
 * ToDoデータベースから予定を読み出すし一覧表示する
 */

// 表示日数設定、POSTデータ変数があれば引数を上書きする
if (isset($_POST['FORDAYS']))
  $days = (int)$_POST['FORDAYS'];
else
  $days = 30;  // デフォルトは30日

// 指定日数のToDoを取得
try {

  $todos = allTodo($pdo, $days, 'datetime');

} catch (\PDOException $e) {
  error_log( "\PDO::例外: " . $e->getMessage() );
  echo ("メンテナンス中です。");
  return;
}

// 一覧表示
?>
<center>
<form action="<?php echo $_SERVER['SCRIPT_NAME'];?>?mode=list" method="post">
<font size="5">一覧</font>
  <input type="text" size=4 maxlength=4 name="FORDAYS" value="<?php echo $days;?>">日分。 (0 = 全記録)
</form>

<table class="table-bordered">
  <thead>
  <tr>
  <th width="20" class="start-line">ID</th>
  <th width="180" class="start-line">日時</th>
  <th class="start-line">件名</th>
  <th width="40" class="start-line">★</th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($todos as $todo) : ?>
  <tr>
  <td class="dash-line"><?php echo htmlspecialchars($todo['id']);?></td>
  <td class="dash-line"><?php echo htmlspecialchars($todo['datetime']);?></td>
  <td class="dash-line"><?php echo htmlspecialchars($todo['subject']);?></td>
  <td class="dash-line"><a href="<?php echo $_SERVER['SCRIPT_NAME'];?>?mode=edit&id=<?php printf("%d", (int)$todo['id']);?>">編集</a></td>
  </tr>
  <?php endforeach;?>
  <tr>  <td colspan="4" class="last-line"></td>  </tr>
  </tbody>
</table>
</center>
