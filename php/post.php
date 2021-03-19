<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $current_date_time = 'users_directory/'.$_SESSION["username"].'/'.date("d-m-y").' '.date("h:i:s a");
  $user_post = fopen("$current_date_time", "w");
  $text = trim($_POST["post_text"]);
  fwrite($user_post, $text);

}




?>
