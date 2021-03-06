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
  $select = "SELECT id FROM user_details WHERE id = ?";

  if ($stmt->prepare($select)) {
    $stmt->bind_param("i", $param_id);
    $param_id = $_SESSION["id"];

    if ($stmt->execute()) {
      $stmt->store_result();

      // Check if the user_details already exists
      if ($stmt->num_rows == 1) {
        // Close statement
        $stmt->close();

        // Prepare update statement

      }
      else {
        // Close statement
        $stmt->close();

        // Prepare insert statement
        $insert = "INSERT INTO user_details (id, day, month, year, gender, mobile_no, email_id, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $mysqli->prepare($insert)) {
          $stmt->bind_param("iisisiss", $param_id, $param_day, $param_month, $param_year, $param_gender, $param_mobile_no, $param_email_id, $param_description);
          $param_id = $_SESSION["id"];
          $param_day = $_POST["day"];
          $param_month = $_POST["month"];
          $param_year = $_POST["year"];
          $param_gender = $_POST["gender"];
          $param_mobile_no = $_POST["mobile_no"];
          $param_email_id = $_POST["email_id"];
          $param_description = $_POST["description"];

          if ($stmt->execute()) {
            $stmt->store_result();
          }
        }
      }
    }
  }
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
    <!-- USER DETAILS -->
    <div class="detail_form">
      <h2>Tell us something about you- </h2>
      <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <label for="DoB">DoB: </label><br>
        <input type="number" name="day" placeholder="1" min="1" max="31" maxlength="6">
        <select name="month">
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
        <input type="number" name="year" placeholder="2021" min="1980" max="2021"><br><br>
        <label for="gender">Gender: </label><br>
        <input type="radio" name="gender" value="Male">
        <label for="male">Male &nbsp&nbsp</label>
        <input type="radio" name="gender" value="Female">
        <label for="female">Female &nbsp&nbsp</label>
        <input type="radio" name="gender" value="Other">
        <label for="other">Others </label><br><br>
        <label for="mobile_no">Mobile Number: </label><br>
        <input type="number" name="mobile_no" placeholder="Mobile Number"><br><br>
        <label for="Email">Email Address: </label><br>
        <input type="text" name="email_id" placeholder="E-mail"><br><br>
        <label for="description">Description: </label><br>
        <input type="text" name="description" placeholder="Write something here"><br><br>
        <input type="submit" name="update" value="Update Info" id="Update_btn">
      </form>
    </div>
  </body>
</html>
