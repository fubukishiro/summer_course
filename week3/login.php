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
    <div id="box" class="container-fluid">
      <div id="login-box" class="col-xs-3 col-xs-offset-8">
        <form class="form-horizontal" method="post">
          <div class="form-group">
            <label class="col-xs-4 control-label">用户名</label>
            <div class="col-xs-6">
              <input class="form-control" type="text" name="username" required />
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-4 control-label">密码</label>
            <div class="col-xs-6">
              <input class="form-control" type="password" name="password" required />
            </div>
          </div>
          <br>
          <div id="error" class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">
                &times;
              </span>
            </button>
            用户名或密码错误
          </div>
          <div style="text-align:center">
            <button type="submit" class="btn btn-primary">
                登录
            </button>
          </div>
        </form>
      </div>
    </div>
    <style>
      #box
      {
        padding-bottom:500px;
        background-image:url(img/login-background.jpg);
        background-repeat:no-repeat;
      }
      #login-box
      {
        background:#ffffff;
        border:1px solid #ccc;
        border-radius:4px;
        margin-top:6%;
        padding-top:35px;
        padding-bottom:20px;
      }
      #error
      {
        display:none;
      }
    </style>
    <?php
      session_start();
      require "db.inc.php";
      $API_URL = "https://api.zjubtv.com/Passport/userLogin";
      if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['username']) && !empty($_POST['password']))
      {
        $post_data = array
        (
          "identity" => $_POST['username'],
          "password" => $_POST['password']
        );
        if (filter_var($_POST['username'], FILTER_VALIDATE_EMAIL)) $post_data['type'] = 2;
        $postdata = http_build_query($post_data);
        $options = array
        (
          "http" => array
          (
            "method" => 'POST',
            "header" => 'Content-type:application/x-www-form-urlencoded',
            "content" => $postdata,
            "timeout" => 15 * 60
          )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($API_URL, false, $context);
        $data = json_decode($result, true);
        $data[0] = (int)$data[0];
        if ($data[0] <= 0)
        {
          http_response_code(400);
          session_destroy();
    ?>
    <style>
      #error
      {
        display:block;
      }
    </style>
    <?php
        }
        else
        {
          $_SESSION['id'] = $data[0];
          $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
          $stmt->execute([$data[0]]);
          $result = $stmt->fetch();
          http_response_code(400);
          if ($result == false)
          {
            header("Location:initial.php");
          }
          else
          {
            header("Location:index.php");
          }
        }
      }
    ?>
  </body>
</html>
