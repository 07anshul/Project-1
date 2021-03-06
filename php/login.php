<?php
// Initialize the session
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
  header("location: profile.php");
  exit;
}

// Include config file
require_once "config.php";

// Assign empty values to variables
$username = $password = "";
$username_err =  $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Check Username
  if (empty(trim($_POST["username"]))) {
    $username_err = "Please enter Username";
  }
  else {
  $username = trim($_POST["username"]);
  }
  // Check Password
  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter Password";
  }
  else {
    $password = trim($_POST["password"]);
  }

  // Validate Username and Password
  if (empty($username_err) && empty($password_err)) {

    // Prepare a Select statement
    $select = "SELECT id, username, password FROM user_credentials WHERE username = ?";

    if ($stmt = $mysqli->prepare($select)) {

      $stmt->bind_param("s", $param_username);
      $param_username = $username;

      if ($stmt->execute()) {
        $stmt->store_result();

        // Check if Username exists
        if ($stmt->num_rows == 1) {
          $stmt->bind_result($id, $username, $hashed_password);

          if ($stmt->fetch()) {
            // Check if Password is correct
            if (password_verify($password, $hashed_password)) {
              // Start the Session
              session_start();

              $_SESSION["loggedin"] = true;
              $_SESSION["id"] = $id;
              $_SESSION["username"] = $username;

              // Redirect to Profile page
              header("location: profile.php");
            }
            else {
              $password_err = "Password is Invalid.";
            }
          }
        }
        else {
          $username_err = "Account with this Username does not exists";
        }
      }
      else {
        echo "<script>alert('Oops! Something went wrong. Please try again')</script>";
      }
      // Close statement
      $stmt->close();
    }
  }
  // Close Connection
  $mysqli->close();
}

?>

<!-- HTML FORM -->
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>log in or sign up </title>
    <link rel="stylesheet" type="text/css" href="/project/css/login.css">
  </head>
  <body>

    <!-- Login Form -->
    <div class="login_form">
      <h2>Log In</h2>
      <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <input type="text" name="username" placeholder="Username" value="<?php echo $username; ?>"style="padding:5px"><br>
        <span style="color:red"><?php echo $username_err; ?></span><br>
        <input type="password" placeholder="Password" name="password" style="padding:5px"><br>
        <span style="color:red"><?php echo $password_err; ?></span><br><br>
        <input type="submit" id="loginbtn" value="Log In"><br><br>
      </form>

      <!-- Create New Account Form -->
      <form class="signup_form" action="create_new_account.php" method="post">
        <input type="submit" id="newaccbtn" value="Create Account">
      </form>
    </div>
  </body>
</html>
