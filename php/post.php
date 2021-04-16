<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $dir_path = 'users_directory/'.$_SESSION["id"].'/';
  if (!file_exists($dir_path)) {
      mkdir($dir_path);
  }

  $current_date_time = $dir_path.date("y-m-d").' | '.date("H:i:s");
if (!empty($_POST["post_text"])) {
  if (isset($_POST["insert_post"])) {
    $user_post = fopen("$current_date_time", "w");
    $text = trim($_POST["post_text"]);
    fwrite($user_post, $text);
    header("location: profile.php");
  }
}
else {
  header("location: profile.php");
}

  if (isset($_POST["delete_post"])) {

    $post_address = $_POST["post_address"];

    echo "<script>alert('Do You Really Want To Delete The Post PERMANENTLY!')</script>";

    unlink("$dir_path$post_address");
    header("location: profile.php");

  }
fclose($user_post);
}


?>
