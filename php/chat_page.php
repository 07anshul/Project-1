<?php

session_start();
if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == false) {
  header("location: login.php");
  exit;
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="/project/css/chat_page.css">
  </head>
  <body>

    <header>
      <div class="chat_header">
        <!-- Task bar-->
        <nav>
            <div id="chat_menu">
              <ul>
                <li><a href="general.php">General</a></li>
                <li><a href="forum.php">Forum</a></li>
                <li><a href="profile.php">My Profile</a></li>
                <li><a href="suggestions.php">Suggestions</a></li>
                <li><a href="/project/index.html">Home</a></li>
              </ul>
            </div>
        </nav>
      </div>
    </header>


    <form action="global_chat_msg.php" method="post">
      <!--<input id="img_bttn" type="image" name="share_img" value="+">-->
      <textarea id="chatbar" name="chat_file" rows="3" cols="80" placeholder="Type Here!"></textarea>
      <input id="send_bttn" type="submit" name="send" value="Send">
    </form>

        <?php

        require_once "config.php";

        $dir = 'messages/';

        $date_array = scandir($dir, 1);

      foreach ($date_array as $key => $date) {
        if (!in_array($date, array(".",".."))) {
          echo '<div class="date_pos">';
          echo 'Y - M - D<br>';
          echo $date;
          echo '</div>';
          $real_time_msg_updation = [];
            $user_array = scandir("$dir$date", 1);

          foreach ($user_array as $key => $user) {
            if (!in_array($user, array(".",".."))) {

                $msg_time_array = scandir($dir.$date.'/'.$user, 1);

              foreach ($msg_time_array as $key => $msg_time) {
                  if (!in_array($msg_time, array(".",".."))) {

                     $real_time_msg_updation["$msg_time"] = $user;

                  }

              }
            }
          }
// Date wise msg display
          krsort($real_time_msg_updation);

          foreach ($real_time_msg_updation as $msg_time => $user) {

            if ($user == $_SESSION["id"]) {
              $user_msg = fopen($dir.$date.'/'.$user.'/'.$msg_time, "r");

              echo '<div class="user_msg">';
              while (!feof($user_msg)) {
                echo fgets($user_msg).'<br>';
              }
              echo '<div id="time">';
              echo '<br>'.$msg_time;
              echo '</div>';
              echo '</div>';
              echo '<br><br>';
            }
            else {
              $select = "SELECT username FROM user_credentials WHERE id = ?";

              if ($stmt = $mysqli->prepare($select)) {
                $stmt->bind_param("i", $param_user_id);
                $param_user_id = $user;

                if ($stmt->execute()) {
                  $stmt->store_result();
                  $stmt->bind_result($msg_sender);
                  $stmt->fetch();
                }
              }

              $user_msg = fopen($dir.$date.'/'.$user.'/'.$msg_time, "r");

              echo '<div class="sender_msg">';
              echo '|| '.$msg_sender.'<br><br>';
              while (!feof($user_msg)) {
                echo fgets($user_msg).'<br>';
              }
              echo '<div id="time">';
              echo '<br>'.$msg_time;
              echo '</div>';
              echo '</div>';
              echo '<br><br>';
            }
          }

        }
      }

$stmt->close();
$mysqli->close();
         ?>

  </body>
</html>
