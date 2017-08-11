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
            欢迎，
          <?php
              $stmt = $stmt = $pdo->prepare('SELECT * FROM users WHERE id=?');
              $stmt->execute([$_SESSION['id']]);
              $result = $stmt->fetch();
              echo $result['name'];
          ?>
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
    <a class="btn btn-success navbar-btn" href="upload.php" role="button">
      上传素材
    </a>
    <a class="btn btn-primary navbar-btn" href="export.php" role="button">
      导出
    </a>
  </body>
</html>
