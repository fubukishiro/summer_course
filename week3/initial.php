<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
  <body>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand">
            <img src="img/upload-icon.jpg" style="max-width:100%;max-height:100%">
          </a>
          <?php
            session_start();
            require "db.inc.php";
            if (empty($_SESSION['id']))
            {
          ?>
          <p class="navbar-text">
            您好！请先
          </p>
          <a class="btn btn-primary navbar-btn" href="login.php" role="button">
            登录
          </a>
          <?php
            }
            else
            {
          ?>
          <p class="navbar-text">
            欢迎!由于您是初次登录，请填写您的信息
          </p>
          <a class="btn btn-danger navbar-btn" href="logout.php" role="button">
            登出
          </a>
          <?php
            }
          ?>
        </div>
      </div>
    </nav>
    <div class="container">
      <form class="form-horizontal" method="post">
        <div class="form-group">
          <label class="col-xs-2 col-xs-offset-1 control-label">姓名</label>
          <div class="col-sm-7">
            <input class="form-control" type="text" name="name" required />
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 col-xs-offset-1 control-label">学号</label>
          <div class="col-sm-7">
            <input class="form-control" type="text" name="student_number" required />
          </div>
        </div>
        <div style="text-align:center">
          <button type="submit" class="btn btn-primary" style="margin:0 7px 0 0">
            提交
          </button>
        </div>
      </form>
    </div>
  </body>
</html>
<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['name']) && !empty($_POST['student_number']))
  {
    if (empty($_SESSION['id']))
    {
      http_response_code(401);
      session_destroy();
      header("Location:login.php");
    }
    else
    {
      $stmt = $pdo->prepare('INSERT INTO users(id, student_number, name) VALUES(?, ?, ?)');
      $stmt->execute([$_SESSION['id'], $_POST['student_number'], $_POST['name']]);
      http_response_code(302);
      header("Location:index.php");
    }
  }
?>
