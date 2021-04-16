<?php
session_start();
if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == false) {
  header("location: login.php");
  exit;
}
// Include config Info
require_once "config.php";

if (isset($_POST["update"])) {

  // Prepare select statement
  $select = "SELECT* FROM user_details WHERE id = ?";

  if ($stmt = $mysqli->prepare($select)) {
    $stmt->bind_param("i", $param_id);
    $param_id = $_SESSION["id"];

    if ($stmt->execute()) {
      $stmt->store_result();

      // Check if the user_details already exists
      if ($stmt->num_rows() == 1) {
        // Close statement
        $stmt->close();

        // Prepare update statement
        $update = "UPDATE user_details SET day = ?, month = ?, year = ?, gender = ?, mobile_no = ?, email_id = ?, description = ? WHERE id = ?";

        if ($stmt = $mysqli->prepare($update)) {
          $stmt->bind_param("isisissi", $param_day, $param_month, $param_year, $param_gender, $param_mobile_no, $param_email_id, $param_description, $param_id);
          $param_day = trim($_POST["day"]);
          $param_month = trim($_POST["month"]);
          $param_year = trim($_POST["year"]);
          $param_gender = trim($_POST["gender"]);
          $param_mobile_no = trim($_POST["mobile_no"]);
          $param_email_id = trim($_POST["email_id"]);
          $param_description = trim($_POST["description"]);
          $param_id = $_SESSION["id"];

          if ($stmt->execute()) {
            echo "<script>alert('Information Updated')</script>";
          }
          else {
            echo "<script>alert('Error: '.$mysqli->error)</script>";
          }
          // Close statement
          $stmt->close();
        }
      }
      else {
        // Close statement
        $stmt->close();

        // Prepare insert statement
        $insert = "INSERT INTO user_details (id, day, month, year, gender, mobile_no, email_id, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $mysqli->prepare($insert)) {
          $stmt->bind_param("iisisiss", $param_id, $param_day, $param_month, $param_year, $param_gender, $param_mobile_no, $param_email_id, $param_description);
          $param_id = $_SESSION["id"];
          $param_day = trim($_POST["day"]);
          $param_month = trim($_POST["month"]);
          $param_year = trim($_POST["year"]);
          $param_gender = trim($_POST["gender"]);
          $param_mobile_no = trim($_POST["mobile_no"]);
          $param_email_id = trim($_POST["email_id"]);
          $param_description = trim($_POST["description"]);

          if ($stmt->execute()) {
            echo "<script>alert('Information Updated')</script>";
          }
          else {
            echo "<script>alert('Error: '.$mysqli->error)</script>";
          }
          // Close statement
          $stmt->close();
        }
      }
    }
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
    <title><?php echo $_SESSION["username"]; ?>/Info</title>
    <link rel="stylesheet" type="text/css" href="/project/css/user_details.css">
  </head>
  <body>
    <!-- User info from database -->
    <?php

    // Prepare join statement
    $select = "SELECT* FROM user_details WHERE id = ?";

    if ($stmt = $mysqli->prepare($select)) {
      $stmt->bind_param("i", $param_id);
      $param_id = $_SESSION["id"];

      if ($stmt->execute()) {
        $result = $stmt->get_result();
        $info = $result->fetch_assoc();
      }
    }

     ?>
    <!-- USER DETAILS -->
    <div class="detail_form">
      <h2>Tell us something about you- </h2>
      <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <label for="DoB">DoB: </label><br>
        <input type="number" name="day" placeholder="1" min="1" max="31" maxlength="6" value="<?php echo $info["day"]; ?>">
        <select name="month">
          <option value="<?php echo $info["month"]; ?>"><?php echo $info["month"]; ?></option>
          <option value="January">Jan</option>
          <option value="February">Feb</option>
          <option value="March">Mar</option>
          <option value="April">Apr</option>
          <option value="May">May</option>
          <option value="June">Jun</option>
          <option value="July">Jul</option>
          <option value="August">Aug</option>
          <option value="September">Sep</option>
          <option value="October">Oct</option>
          <option value="November">Nov</option>
          <option value="December">Dec</option>
        </select>
        <input type="number" name="year" placeholder="2021" min="1980" max="2021" value="<?php echo $info["year"]; ?>"><br><br>
        <label for="gender">Gender: </label><br>
        <input type="radio" name="gender" value="Male">
        <label for="male">Male &nbsp&nbsp</label>
        <input type="radio" name="gender" value="Female">
        <label for="female">Female &nbsp&nbsp</label>
        <input type="radio" name="gender" value="Other">
        <label for="other">Others </label><br><br>
        <label for="mobile_no">Mobile Number: </label><br>
        <input type="number" name="mobile_no" placeholder="Mobile Number" value="<?php echo $info["mobile_no"]; ?>"><br><br>
        <label for="Email">Email Address: </label><br>
        <input type="text" name="email_id" placeholder="E-mail" value="<?php echo $info["email_id"]; ?>"><br><br>
        <label for="description">Description: </label><br>
        <textarea name="description" rows="4" cols="40" placeholder="Write something here!" value="<?php echo $info["description"]; ?>"></textarea><br><br>
        <input type="submit" name="update" value="Update Info" id="Update_btn">
      </form>
    </div>
  </body>
</html>
