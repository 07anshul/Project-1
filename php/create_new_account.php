<?php

// Include config file
require_once "config.php";

// Assign empty values to variables
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST["signup"])) {
    // Validate $username
    if (empty(trim($_POST["username"]))) {
      $username_err = "Choose a Username";
    }
    else {
      // Preapare a Select statement
        $select = "SELECT id FROM user_credentials WHERE username = ?";

        if ($stmt = $mysqli->prepare($select)) {
          $stmt->bind_param("s", $param_username);
          $param_username = trim($_POST["username"]);

          // Execute statement
          if ($stmt->execute()) {
            $stmt->store_result();

            // Check if Username already exists
            if ($stmt->num_rows() == 1) {
              $username_err = "Username already exists. Choose another";
            }
            elseif (strlen($_POST["username"]) > 20) {
              $username_err  = "Username is too long. Choose another";
            }
            else {
              // Assign Username
              $username = trim($_POST["username"]);
            }
          }
        }

        // Close statement
        $stmt->close();
    }
    // Validate password
    if (empty(trim($_POST["password"]))) {
      $password_err = "Choose a Password";
    }
    elseif (strlen($_POST["password"]) < 8) {
      $password_err = "Password is too short. Must have 8 characters";
    }
    else {
      $password = trim($_POST["password"]);
    }
    // Validate Confirm Password
    if (empty(trim($_POST["confirm_password"]))) {
      $confirm_password_err = "Password Not Confirmed";
    }
    else {
      $confirm_password = trim($_POST["confirm_password"]);

      if (empty($password_err) && ($password != $confirm_password)) {
        $confirm_password_err = "Password Not Matched. Confirm Again";
      }
    }
    // Checking error before Insertinng data into Database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
      // Preapare an Insert statement
      $insert = "INSERT INTO user_credentials (username, password) VALUES (?, ?)";

      if ($stmt = $mysqli->prepare($insert)) {
        $stmt->bind_param("ss", $param_username, $param_password);

        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        if ($stmt->execute()) {

          // Redirect to login page
          header("location: login.php");
        }
        else {
          echo "<script>alert('Sorry for inconvenience. PLease try again')</script>";
        }

        // Close statement
        $stmt->close();
      }

    }

    // Close connection
    $mysqli->close();
  }
}

?>

<!-- HTML FORM FOR SIGN UP -->
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="/project/css/create_new_account.css">
  </head>
  <body>
    <div class="signup_form">
      <h2>Sign Up</h2>
      <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <input type="text" placeholder="Username" name="username" value="<?php echo $username; ?>" style="padding:5px"><br>
        <span style="color:red"><?php echo $username_err; ?></span><br>
        <input type="password" placeholder="Password" name="password" value="<?php echo $password; ?>" style="padding:5px"><br>
        <span style="color:red"><?php echo $password_err; ?></span><br>
        <input type="password" placeholder="Confirm Password" name="confirm_password" style="padding:5px"><br>
        <span style="color:red"><?php echo $confirm_password_err; ?></span><br>
        <input type="submit" id="signup" name="signup" value="Sign Up">

      </form>
    </div>
  </body>
</html>
