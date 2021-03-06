<?php
session_start();
if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == false) {
  header("location: login.php");
  exit;
}
// Include config file
require_once "config.php"
 ?>

<!-- Html - Searched Profile -->
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_SESSION["username"]; ?>/Following</title>
    <link rel="stylesheet" type="text/css" href="/project/css/searched_profile.css">
  </head>
  <body>
    <?php
    // Prepare join statement
    $join = "SELECT user_credentials.username FROM user_followers INNER JOIN user_credentials ON user_followers.user_id = user_credentials.id WHERE user_followers.follower_id = ?";

    if ($stmt = $mysqli->prepare($join)) {
      $stmt->bind_param("i", $param_follower_id);
      $param_follower_id = $_SESSION["id"];

      if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($following = $result->fetch_assoc()) {
          echo $following["username"].'<br><br>';
        }
      }
    }

     ?>
  </body>
</html>
