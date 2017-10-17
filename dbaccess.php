<?php

  function connect($params) {
    // 接続パラメータのチェック
    if (! isset($params['host']) ) $params['host'] = '';
    if (! isset($params['port']) ) $params['port'] = '';
    if (! isset($params['database']) ) $params['database'] = '';
    if (! isset($params['user']) ) $params['user'] = '';
    if (! isset($params['password']) ) $params['password'] = '';

    // postgresqlのデータベースに接続
    $conStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s", 
		      $params['host'], 
		      $params['port'], 
		      $params['database'], 
		      $params['user'], 
		      $params['password']);
    $pdo = new \PDO($conStr);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $pdo->query("SET client_encoding TO 'UTF-8'");

    return $pdo;
  }

  function insertTodo($pdo, $datetime, $subject='', $detail='') {
    // INSERTステートメントを準備
    $sql = 'INSERT INTO todo_tbl1'
      . ' (todo_datetime, todo_subject, todo_detail)'
      . ' VALUES'
      . '(:datetime, :subject, :detail)';
    //error_log($sql);
    $stmt = $pdo->prepare($sql);
        
    // ステートメントに値を渡す
    $stmt->bindValue(':datetime', strftime("%F %T", strtotime($datetime)));
    $stmt->bindValue(':subject', pg_escape_string($subject));
    $stmt->bindValue(':detail', pg_escape_string($detail));
        
    // INSERTステートメントを実行
    $stmt->execute();
        
    // 符番されたIDを返す
    return $pdo->lastInsertId('todo_tbl1_id_seq');
  }

  function updateTodo($pdo, $id, $datetime, $subject, $detail) {
    // UPDATEステートメントを準備
    $sql = 'UPDATE todo_tbl1'
      . ' SET todo_datetime = :datetime'
      . ', todo_subject = :subject'
      . ', todo_detail = :detail'
      . ' WHERE id = :id';
    $stmt = $pdo->prepare($sql);
 
    // ステートメントに値を渡す
    $stmt->bindValue(':datetime', strftime("%F %T", strtotime($datetime)));
    $stmt->bindValue(':subject', pg_escape_string($subject));
    $stmt->bindValue(':detail', pg_escape_string($detail));
    $stmt->bindValue(':id', (int)$id);

    // UPDATEステートメントを実行
    $stmt->execute();
 
    // 更新した行数を返す
    return $stmt->rowCount();
  }

  function getTodoById($pdo, $id) {
    // SELECTステートメントを準備・実行
    $id = (int)$id;
    $condition = " WHERE id = $id";
    $stmt = $pdo->query('SELECT *'
			      . ' FROM todo_tbl1'
			      . $condition
			      );

    // SELECT実行結果の取り出し
    $todos = array();
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
      $todo = array(
		    'id' => $row['id'],
		    'datetime' => $row['todo_datetime'],
		    'subject' => $row['todo_subject'],
		    'detail' => $row['todo_detail'],
		    );
	  break;
    }
    
    return $todo;
  }

  function allTodo($pdo, $days="") {
    // SELECTステートメントを準備
    // 日数による絞り込み条件
    $days = (int)$days;
    if ( $days > 0 ) {
      $today = time();
      $todate = $today + $days*3600*24;
      // 日付変換、unixタイムスタンプからISOへ
      $ftodate = strftime("'%F %T'", $todate);
      $condition = "todo_datetime <= $ftodate";
    }else{
      $condition = "(true)";
    }

    // SELECTステートメントを実行
    $stmt = $pdo->query('SELECT *'
			      . ' FROM todo_tbl1'
			      . ' WHERE ' . $condition
			      . ' ORDER BY todo_datetime'
			      );

    // SELECT実行結果の取り出し
    $todos = array();
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
      $todos[] = array(
		       'id' => $row['id'],
		       'datetime' => $row['todo_datetime'],
		       'subject' => $row['todo_subject'],
		       'detail' => $row['todo_detail'],
		       );
    }
    return $todos;
  }
