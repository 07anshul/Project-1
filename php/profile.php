
<?php
// Session start
session_start();
if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == false) {
  header("location: login.php");
  exit;
}

// Include config file
require_once "config.php";

// Searching profile
$user_err = "";

if (isset($_GET["search"])) {

  if (!empty(trim($_GET["profile"]))) {

    // Search Username
    $select = "SELECT id, username FROM user_credentials WHERE username = ?";

    if ($stmt = $mysqli->prepare($select)) {

      $stmt->bind_param("s", $param_username);
      $param_username = trim($_GET["profile"]);;

      if ($stmt->execute()) {
        $stmt->store_result();

        // Check if searched profile exists
        if ($stmt->num_rows == 1) {
          $stmt->bind_result($searched_id, $searched_profile);

          if ($stmt->fetch()) {

            session_start();
            $_SESSION["searched_profile"] = $searched_profile;
            $_SESSION["searched_id"] = $searched_id;

            // Close statement
            $stmt->close();

            // Check if the user is searching its own profile
            if ($_SESSION["id"] != $_SESSION["searched_id"]) {

              $select = "SELECT user_id, follower_id FROM user_followers WHERE user_id = ? AND follower_id = ?";
              if ($stmt = $mysqli->prepare($select)) {
                $stmt->bind_param("ii", $param_user_id, $param_follower_id);

                $param_user_id = $_SESSION["searched_id"];
                $param_follower_id = $_SESSION["id"];

                if ($stmt->execute()) {
                  $stmt->store_result();

                  // Check if the user is already following the handle
                  if ($stmt->num_rows() == 1) {
                    $_SESSION["status"] = "Following";
                  }
                  else {
                    $_SESSION["status"] = "Follow";
                  }
                  // Close statement
                  $stmt->close();
                }
              }
              // Redirect to searched profile
              header("location: searched_profile.php");
            }
            else {
              // Redirect to same(home) page
              header("location: profile.php");
            }
          }
        }
        else {
          $user_err = "User does not exists";
        }
      }
      else {
        echo "<script>alert('Sorry for inconvenience. PLease try again')</script>";
      }
    }
  }
  // Close connection
  $mysqli->close();
}
 ?>

<!-- Html - Username Profile -->
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_SESSION["username"]; ?> - Home</title>
    <link rel="stylesheet" type="text/css" href="/project/css/profile.css">
  </head>
  <body>
    <header>
      <div class="profile_header">
        <nav>
            <div id="profile_menu">
              <ul>
                <li><a href="followers.php">Followers</a></li>
                <li><a href="following.php">Following</a></li>
                <li><a href="/project/index.html">Home</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
              </ul>
            </div>
        </nav>
        <div id="searched_profile">
          <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get">
            <input type="text" name="profile" placeholder="Enter Username">
            <input type="submit" name="search" value="Search"><br>
            <span><?php echo $user_err; ?></span><br><br>
          </form>
        </div>
        <div id="profile_picture">
          <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <input type="submit" name="profile_pic" value="Add Your Picture"><br><br>
          </form>
        </div>
        <div id="user_details">
          <a href="user_details.php">Tell Us Something About You</a>
        </div>
      </div>
    </header>
  </body>
</html>
