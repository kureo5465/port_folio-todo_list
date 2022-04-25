<?php
define("STATUS_OPENED", "0");
define("STATUS_CLOSED", "1");
define("TODO_LIST_CSV", "todo_list.csv");
define("TODO_LIST_CSV_LOCK", "todo_list.csv.lock");


//todoリストを表示させる関数。
function read_todo_list($include_closed = true) {
    $handle = fopen(TODO_LIST_CSV, "r");
    $todo_list = [];

    //csvファイルを読み込み配列として出力。
    while ($todo = fgetcsv($handle)) {
        if (!$include_closed && $todo[3] === STATUS_CLOSED) {
            continue;
        }

        // 配列変数$todoを$todo_list配列に追加する
        $todo_list[] = $todo;
    }

    fclose($handle);
    return $todo_list;
}

//エラーメッセージ取得
function get_message() {
    $message = (string)filter_input(INPUT_GET, "message");
    
    if ($message === MESSAGE_TASK_EMPTY
    || $message === MESSAGE_TASK_MAX_LENGTH
    || $message === MESSAGE_ID_INVALID) {
    
    return $message;
 }


    return "";
}

//ユーザー同士が同時に参照しないように排他ロックを行う。
function lock_file($operation = LOCK_EX) {
    $handle = fopen(TODO_LIST_CSV_LOCK, "a");

    //ファイルのロックを取得。
    flock($handle, $operation);

    //ファイルポインタを返却。
    return $handle;
}

//引数にlocl_file関数を渡している。排他ロックを解除する。
function unlock_file($handle) {
    flock($handle, LOCK_UN);
    fclose($handle);
}



////todo_add.php
define("TASK_MAX_LENGTH", 140);
define("MESSAGE_TASK_EMPTY", "タスクが未⼊⼒です。");
define("MESSAGE_TASK_MAX_LENGTH", "タスクが140⽂字を超えています。");
define("MESSAGE_ID_INVALID", "⼊⼒されたIDは不正です。");

//ToDoの新規IDを返却する。
function get_new_todo_id(){
    return count(read_todo_list()) + 1;
}


//新規ToDoを既存のToDoリストに追記する
function add_todo_list($todo){
    $handle = fopen(TODO_LIST_CSV, "a");

    //csvファイルに書き込み。
    fputcsv($handle, $todo);
    fclose($handle);
}

function redirect($page) {
    header("Location: " . $page);
    exit();
}


//redirect_with_message関数は、引数で受け取った$messageをクエリパラメータに設定して$pageにリダイレクト。
function redirect_with_message($page, $message){

    //引数に値や変数が存在しない場合、TRUEを返す。
    if (empty($message)) {
        redirect($page);
    }
    
    //urlencodeでURLエンコードする。
    $message = urlencode($message);

    //エラーメッセージをクエリーパラメータに設定。
    header("Location: " . $page. "?message=${message}");
    exit();
}



////todo_finish.php
//todoリストのステータスを完了する関数。
//ToDoリストをtodo_list.csvファイルに書き込む
function write_todo_list($todo_list) {

    //書き込みモード。
    $handle = fopen(TODO_LIST_CSV, "w");
    foreach ($todo_list as $todo) {

        //csvファイルに書き込む。
    fputcsv($handle, $todo);
  }
 fclose($handle);
}




