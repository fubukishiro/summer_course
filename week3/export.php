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
    <div class="container">
      <div class="panel panel-default">
        <div class="panel-heading">
          <form class="form-horizontal" method="get">
            <div class="form-group">
              <label class="col-xs-2 control-label" style="text-align:center">请选择起止日期：</label>
              <div class="col-sm-3">
                <input class="form-control" type="date" name="startdate" required />
              </div>
              <label class="col-xs-1 control-label" style="text-align:center">至</label>
              <div class="col-sm-3">
                <input class="form-control" type="date" name="enddate" required />
              </div>
              <button type="submit" class="btn btn-primary">
                导出
              </button>
            </div>
          </form>
        </div>
        <table class="table table-hover">
          <tr>
            <th>素材名称</th>
            <th>标签</th>
            <th>拍摄日期</th>
          </tr>
          <?php
            $stmt = $pdo->prepare('SELECT * FROM materials');
            $stmt->execute();
            if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['startdate']) && !empty($_GET['enddate']))
            {
              $stmt = $pdo->prepare('SELECT * FROM materials WHERE time>=? AND time <=?');
              $stmt->execute([$_GET['startdate'], $_GET['enddate']]);
            }
            while ($material = $stmt->fetch())
            {
              $name = $material['name'];
              echo "<tr>";
              echo "<td>$name</td>";
              echo "<td>";
              $stmt1 = $pdo->prepare('SELECT * FROM material_tag WHERE material_id=?');
              $stmt1->execute([$material['id']]);
              while ($result = $stmt1->fetch())
              {
                $stmt2 = $pdo->prepare('SELECT name FROM tags WHERE id=?');
                $stmt2->execute([$result['tag_id']]);
                $tag = $stmt2->fetch();
                $name = $tag['name'];
                echo "<span class=\"badge\">$name</span>&nbsp";
              }
              echo "</td>";
              echo "<td>";
              echo $material['time'];
              echo "</td>";
              echo "</tr>";
            }
          ?>
        </table>
      </div>
      <div class="col-xs-2 col-xs-offset-10">
        <a class="btn btn-primary navbar-btn" href="index.php" role="button">
          返回
        </a>
      </div>
    </div>
  </body>
</html>
