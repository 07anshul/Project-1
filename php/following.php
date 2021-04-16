<?php
session_start();
if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == false) {
  header("location: login.php");
  exit;
}
// Include config file
require_once "config.php";
 ?>

<!-- Html - Searched Profile -->
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_SESSION["username"]; ?>/Following</title>
    <link rel="stylesheet" type="text/css" href="/project/css/following.css">
  </head>
  <body>

    <header>
      <div class="following_header">
        <!-- Task bar-->
        <nav>
            <div id="following_menu">
              <ul>
                <li><a href="/project/index.html">Home</a></li>
                <li><a href="profile.php">My Profile</a></li>
                <li><a href="chat_page.php">Lets Chat!</a></li>
                <li><a href="forum.php">Forum</a></li>
              </ul>
            </div>
        </nav>
      </div>
    </header>

    <?php
    // Prepare join statement
    $join = "SELECT user_credentials.username FROM user_followers INNER JOIN user_credentials ON user_followers.user_id = user_credentials.id WHERE user_followers.follower_id = ?";

    if ($stmt = $mysqli->prepare($join)) {
      $stmt->bind_param("i", $param_follower_id);
      $param_follower_id = $_SESSION["id"];

      if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($following = $result->fetch_assoc()) {

          echo "<div id=followings> $following[username] </div>";
        }
      }
    }

     ?>
  </body>
</html>
