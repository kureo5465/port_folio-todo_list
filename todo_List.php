<?php
require_once("functions.php");

$message = get_message();

$lock_handle = lock_file(LOCK_SH);

$todo_list = read_todo_list(false);

//ロックを解除
unlock_file($lock_handle);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width,initial-scale=1">
 <title>ToDo List</title>
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
 integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
<nav class="navbar navbar-dark bg-primary">
    <div class="container"> 
         <span class="navbar-brand">ToDo List</span>
    </div>
</nav>

<div class="container mt-4">

<!-- エラーメッセージが表示 -->
 <?php if ($message !== "") { ?>
 <div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($message); ?>
        </div>
    </div>
    </div>
 <?php } ?>


 <div class="row">
    <div class="col-md-12">
        <!-- todo_add.phpにリクエストを送信 -->
        <form action="todo_add.php" method="post" class="form">
                <div class="input-group mb-2">
                <input type="text" name="task" id="task" class="formcontrol">
                <div class="input-group-append">
                <input type="submit" class="btn btn-primary"value="Add">
                </div>
            </div>
        </form>
    </div>
 </div>

 <div class="row">
    todo_web.md 5/17/2020
    15 / 30
 <div class="col-md-12">
 <hr>
 </div>
 </div>
    <div class="row">
    <?php foreach ($todo_list as $todo) { ?>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <p class="card-text"><?php echo htmlspecialchars($todo[1]); ?></p>
                <p class="small text-muted"><?php echo htmlspecialchars($todo[2]); ?></p>
                <a href="todo_finish.php?id=<?php echo htmlspecialchars($todo[0]); ?>"
                class="btn btn-primary btn-sm">Finish</a>
            </div>
        </div>
    </div>
 <?php } ?>
 </div>
</div>
</body>
</html>