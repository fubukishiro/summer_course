<?php
  $API_URL = "https://api.zjubtv.com/Passport/userLogin";
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['username']) && !empty($_POST['password']))
  {
    $post_data = array
    (
      'username' => $_POST['username'],
      'password' => $_POST['password']
    );
    $postdata = http_build_query($post_data);
    $options = array
    (
      'http' => array
      (
        'method' => 'POST',
        'header' => 'Content-type:application/x-www-form-urlencoded',
        'content' => $postdata,
        'timeout' => 15 * 60
      )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($API_URL, false, $context);
    if ($result['status_code'] != 200) echo "error";
    else echo "ok";
  }
?>
