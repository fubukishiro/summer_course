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
    <script>
      var count = 1;
      function add()
      {
        count++;
        var tag_id = "tag" + count;
        var target = document.getElementById(tag_id);
        target.style.display = "block";
        target.value = "";
        if (count > 1)
        {
          target = document.getElementById("decrease");
          target.disabled = false;
        }
        if (count == 5)
        {
          target = document.getElementById("increase");
          target.disabled = "disabled";
        }
      }
      function deduct()
      {
        var tag_id = "tag" + count;
        var target = document.getElementById(tag_id);
        target.style.display = "none";
        target.value = "";
        if (count == 2)
        {
          target = document.getElementById("decrease");
          target.disabled = "disabled";
        }
        if (count == 5)
        {
          target = document.getElementById("increase");
          target.disabled = false;
        }
        count--;
      }
    </script>
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
      <form class="form-horizontal" method="post">
        <div class="form-group">
          <label class="col-xs-2 col-xs-offset-1 control-label">素材名称</label>
          <div class="col-sm-7">
            <input class="form-control" type="text" name="name" required />
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 col-xs-offset-1 control-label">标签</label>
          <div class="col-xs-7">
            <input id="tag1" class="form-control" type="text" name="tag1"/>
          </div>
          <div class="col-xs-offset-3 col-xs-7">
            <input id="tag2" class="form-control" type="text" name="tag2" style="display:none;margin-top:15px"/>
          </div>
          <div class="col-xs-offset-3 col-xs-7">
            <input id="tag3" class="form-control" type="text" name="tag3" style="display:none;margin-top:15px"/>
          </div>
          <div class="col-xs-offset-3 col-xs-7">
            <input id="tag4" class="form-control" type="text" name="tag4" style="display:none;margin-top:15px"/>
          </div>
          <div class="col-xs-offset-3 col-xs-7">
            <input id="tag5" class="form-control" type="text" name="tag5" style="display:none;margin-top:15px"/>
          </div>
          <div class="col-xs-6 col-xs-offset-3" style="margin-top:15px">
            <button id="increase" class="btn btn-success" type="button" onclick="add()">
              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
              新增一个标签
            </button>
            <button id="decrease" class="btn btn-warning" type="button" onclick="deduct()" disabled="disabled">
              <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
              删除一个标签
            </button>
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
          <a class="btn btn-primary navbar-btn" href="index.php" role="button">
            返回
          </a>
        </div>
      </form>
    </div>
  </body>
</html>
<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['name']) && !empty($_POST['date']) && !empty($_POST['contributor']))
  {
    if (empty($_SESSION['id']))
    {
      http_response_code(401);
      session_destroy();
?>
      <script>
        window.location.href='login.php';
      </script>
<?php
    }
    else
    {
      $stmt = $pdo->prepare('INSERT INTO materials(name, time, uploader_id, comments, file) VALUES(?, ?, ?, ?, ?)');
      $stmt->execute([$_POST['name'], $_POST['date'], $_SESSION['id'], $_POST['comments'], $_POST['file']]);
      $stmt = $pdo->prepare('SELECT * FROM materials WHERE name=? AND time=? AND uploader_id=? AND comments=? AND file=?');
      $stmt->execute([$_POST['name'], $_POST['date'], $_SESSION['id'], $_POST['comments'], $_POST['file']]);
      $result = $stmt->fetch();
      $material_id = $result['id'];
      if (!empty($_POST['tag1']))
      {
        $stmt = $pdo->prepare('SELECT * FROM tags WHERE name=?');
        $stmt->execute([$_POST['tag1']]);
        $result = $stmt->fetch();
        if ($result == false)
        {
          $stmt = $pdo->prepare('INSERT INTO tags(name) VALUES(?)');
          $stmt->execute([$_POST['tag1']]);
        }
        $stmt = $pdo->prepare('SELECT * FROM tags WHERE name=?');
        $stmt->execute([$_POST['tag1']]);
        $result = $stmt->fetch();
        $tag_id = $result['id'];
        $stmt = $pdo->prepare('INSERT INTO material_tag(material_id, tag_id) VALUES(?, ?)');
        $stmt->execute([$material_id, $tag_id]);
      }
      if (!empty($_POST['tag2']))
      {
        $stmt = $pdo->prepare('SELECT * FROM tags WHERE name=?');
        $stmt->execute([$_POST['tag2']]);
        $result = $stmt->fetch();
        if ($result == false)
        {
          $stmt = $pdo->prepare('INSERT INTO tags(name) VALUES(?)');
          $stmt->execute([$_POST['tag2']]);
        }
        $stmt = $pdo->prepare('SELECT * FROM tags WHERE name=?');
        $stmt->execute([$_POST['tag2']]);
        $result = $stmt->fetch();
        $tag_id = $result['id'];
        $stmt = $pdo->prepare('INSERT INTO material_tag(material_id, tag_id) VALUES(?, ?)');
        $stmt->execute([$material_id, $tag_id]);
      }
      if (!empty($_POST['tag3']))
      {
        $stmt = $pdo->prepare('SELECT * FROM tags WHERE name=?');
        $stmt->execute([$_POST['tag3']]);
        $result = $stmt->fetch();
        if ($result == false)
        {
          $stmt = $pdo->prepare('INSERT INTO tags(name) VALUES(?)');
          $stmt->execute([$_POST['tag3']]);
        }
        $stmt = $pdo->prepare('SELECT * FROM tags WHERE name=?');
        $stmt->execute([$_POST['tag3']]);
        $result = $stmt->fetch();
        $tag_id = $result['id'];
        $stmt = $pdo->prepare('INSERT INTO material_tag(material_id, tag_id) VALUES(?, ?)');
        $stmt->execute([$material_id, $tag_id]);
      }
      if (!empty($_POST['tag4']))
      {
        $stmt = $pdo->prepare('SELECT * FROM tags WHERE name=?');
        $stmt->execute([$_POST['tag4']]);
        $result = $stmt->fetch();
        if ($result == false)
        {
          $stmt = $pdo->prepare('INSERT INTO tags(name) VALUES(?)');
          $stmt->execute([$_POST['tag4']]);
        }
        $stmt = $pdo->prepare('SELECT * FROM tags WHERE name=?');
        $stmt->execute([$_POST['tag4']]);
        $result = $stmt->fetch();
        $tag_id = $result['id'];
        $stmt = $pdo->prepare('INSERT INTO material_tag(material_id, tag_id) VALUES(?, ?)');
        $stmt->execute([$material_id, $tag_id]);
      }
      if (!empty($_POST['tag5']))
      {
        $stmt = $pdo->prepare('SELECT * FROM tags WHERE name=?');
        $stmt->execute([$_POST['tag5']]);
        $result = $stmt->fetch();
        if ($result == false)
        {
          $stmt = $pdo->prepare('INSERT INTO tags(name) VALUES(?)');
          $stmt->execute([$_POST['tag5']]);
        }
        $stmt = $pdo->prepare('SELECT * FROM tags WHERE name=?');
        $stmt->execute([$_POST['tag5']]);
        $result = $stmt->fetch();
        $tag_id = $result['id'];
        $stmt = $pdo->prepare('INSERT INTO material_tag(material_id, tag_id) VALUES(?, ?)');
        $stmt->execute([$material_id, $tag_id]);
      }
      http_response_code(302);
?>
      <script>
        window.location.href='index.php';
      </script>
<?php
    }
  }
?>
