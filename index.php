<?php
require_once 'dbaccess.php';

// 初期化
try {
  // パラメータを iniフォーマットの構成ファイルから読み込む
  $params = parse_ini_file('conf/todo.ini', true);
  if ($params === false) {
    throw new \Exception("Error reading ini configuration file");
  }

  // DB接続
  $pdo = connect($params['database']);
  
} catch (\PDOException $e) {
  error_log( "\PDO::例外: " . $e->getMessage() );
  echo ("メンテナンス中です。");
  goto end;
}

// 現行時刻を記憶
$now = strftime('%F %T', time());

// 以下、本体ヘッダー部
?>
  <link rel="stylesheet" href="./css/todo.css">
  <div class="container">
  <h1>ToDo</h1>
  <font size="3">
  </font>
  <div class="left-column">
  <a href="<?php echo $_SERVER['SCRIPT_NAME'];?>"> [一覧] </a>
  <a href="<?php echo $_SERVER['SCRIPT_NAME'];?>?mode=edit"> [作成] </a>
  </div>
  <div class="right-column"><?php echo $now;?></div>
    <div>
    <blockquote>
    <hr size="1">
<?php
// URLパラメータの表示モードによりページ内容を切り替え
if (! empty($_GET['mode']) )
  $mode = $_GET['mode'];
else
  $mode = '';

// saveのときはオプションを確認
if ($mode == "save" && $_POST['SaveOpt'] != "保存"){
  // 保存でなければlistに変更
  echo "<center>キャンセルしました。</center>";
  $mode = "list";
}

switch ($mode) {
case 'edit':
  // 編集|作成
  include "edit.php";
  break;
case 'save':
  // 保存
  include "save.php";
  break;
default:
  // 一覧
  include "list.php";
  break;
}
// 以下、フッター部
?>
    </blockquote>
    </div>
  </div>
  <div class="left-column">
  <img src="/icons/back.gif"><a href="<?php echo $_SERVER['SCRIPT_NAME'];?>">戻る</a><br/>
  </div>
<?php end: ?>
  <div class="right-column">
  <img src="/icons/layout.gif"><a href="/">サイトトップへ</a>
  </div>
