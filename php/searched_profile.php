<?php
session_start();

// Include config file
require_once "config.php";

      if (isset($_POST["Follow"])) {

        // Prepare an insert statement
        $insert = "INSERT INTO user_followers (user_id, follower_id) VALUES (?, ?)";

        if ($stmt = $mysqli->prepare($insert)) {
          $stmt->bind_param("ii", $param_user_id, $param_follower_id);

          $param_user_id = $_SESSION["searched_id"];
          $param_follower_id = $_SESSION["id"];

          if ($stmt->execute()) {
            $_SESSION["status"] = "Following";
          }
          else {
            $_SESSION["status"] = "Follow";
          }
          // Close statement
          $stmt->close();
        }
        // Close connection
        $mysqli->close();
      }

 ?>







<!-- Html - Searched Profile -->
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_SESSION["searched_profile"]; ?>/Profile</title>
    <link rel="stylesheet" type="text/css" href="/project/css/searched_profile.css">
  </head>
  <body>
    <form class="" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
      <input type="submit" name="<?php echo $_SESSION["status"]; ?>" value="<?php echo $_SESSION["status"]; ?>">
    </form>
  </body>
</html>
