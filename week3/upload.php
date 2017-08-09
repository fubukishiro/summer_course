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
            if (!empty($_SESSION['id']))
            {
          ?>
          <p class="navbar-text">
            您好！请先
          </p>
          <button type="button" class="btn btn-primary navbar-btn">
            登录
          </button>
          <?php
            }
            else
            {
          ?>
          <p class="navbar-text">
            欢迎，
          <?php
            $stmt = $stmt = $pdo->prepare('SELECT * FROM users WHERE id=?');
            $stmt->execute($_SESSION['id']);
            $resule = $stmt->fetch();
            echo $result['name'];
          ?>
          </p>
          <button type="button" class="btn btn-danger navbar-btn">
            登出
          </button>
        </div>
      </div>
    </nav>
    <div class="container">
      <form class="form-horizontal">
        <div class="form-group">
          <label class="col-xs-2 col-xs-offset-1 control-label">素材名称</label>
          <div class="col-sm-7">
            <input class="form-control" type="text" name="name" required />
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 col-xs-offset-1 control-label">标签</label>
          <div class="col-sm-7">
            <input class="form-control" type="text" name="tag"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 col-xs-offset-1 control-label">参与人员</label>
          <div class="col-sm-7">
            <input class="form-control" type="text" name="contributor" required />
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 col-xs-offset-1 control-label">拍摄日期</label>
          <div class="col-sm-7">
            <input class="form-control" type="date" name="date" required />
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 col-xs-offset-1 control-label">备注</label>
          <div class="col-sm-7">
            <textarea class="form-control" name="comments" rows="2"></textarea>
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 col-xs-offset-1 control-label">选择文件</label>
          <div class="col-sm-7">
            <input type="file" name="file" required />
          </div>
        </div>
        <div style="text-align:center">
          <button type="submit" class="btn btn-primary" style="margin:0 7px 0 0">
            提交
          </button>
          <button type="button" class="btn btn-primary" style="margin:0 0 0 7px">
            返回
          </button>
        </div>
      </form>
    </div>
  </body>
</html>
<?php
  if (empty($_SESSION['id']))
  {
    http_response_code(401);
    session_destroy();
    header("Location:login.php");
  }
  else
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['name']) && !empty($_POST['date']) && !empty($_POST['contributor']))
    {
      $stmt = $pdo->prepare('INSERT INTO materials(name, time, uploader_id, comments) VALUES(?, ?, ?, ?)');
      $stmt->execute([$_POST['name'], $_POST['date'], $_SESSION['id'], $_SESSION['comments']]);
      http_response_code(302);
      header("Location:upload.php");
    }
  }
?>
