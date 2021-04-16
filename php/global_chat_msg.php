<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $dir_path = 'messages/'.date("y-m-d").'/';
  if (!file_exists($dir_path)) {
      mkdir($dir_path);
  }

  $dir_path = 'messages/'.date("y-m-d").'/'.$_SESSION["id"].'/';
  if (!file_exists($dir_path)) {
      mkdir($dir_path);
  }

  $current_date_time = $dir_path.date("H:i:s");
if (!empty($_POST["chat_file"])) {
  if (isset($_POST["send"])) {
    $user_msg = fopen("$current_date_time", "w");
    $msg = trim($_POST["chat_file"]);
    fwrite($user_msg, $msg);
    header("location: chat_page.php");
    exit;

  }
}
else {
  header("location: chat_page.php");
}
}

 ?>
