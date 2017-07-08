<?php 

// Declaring variables to prevent errors
$fname = ""; // First Name
$lname = ""; // Last Name
$em = ""; // Email Address
$em2 = ""; // Confirmation Email
$password = ""; // Password
$password2 = ""; // Confirmation Password
$date = ""; // Sign up date
$error_array = array(); // Holds error messages

if(isset($_POST['register_button'])) {

  // Registration form values

  // First Name
  $fname = strip_tags($_POST['reg_fname']); //Remove html tags
  $fname = str_replace(' ', '', $fname); // Remove spaces
  $fname = ucfirst(strtolower($fname)); // Uppercase first
  $_SESSION['reg_fname'] = $fname; // This stores first name into session variable

  // Last Name
  $lname = strip_tags($_POST['reg_lname']); //Remove html tags
  $lname = str_replace(' ', '', $lname); // Remove spaces
  $lname = ucfirst(strtolower($lname)); // Uppercase first
  $_SESSION['reg_lname'] = $lname; // This stores last name into session variable

  // email
  $em = strip_tags($_POST['reg_email']); //Remove html tags
  $em = str_replace(' ', '', $em); // Remove spaces
  $em = ucfirst(strtolower($em)); // Uppercase first
  $_SESSION['reg_email'] = $em; // This stores email into session variable

  // email Confirmation
  $em2 = strip_tags($_POST['reg_email2']); //Remove html tags
  $em2 = str_replace(' ', '', $em2); // Remove spaces
  $em2 = ucfirst(strtolower($em2)); // Uppercase first
  $_SESSION['reg_email2'] = $em2; // This stores email2 into session variable

  // password
  $password = strip_tags($_POST['reg_password']); //Remove html tags
  $password2 = strip_tags($_POST['reg_password2']); //Remove html tags

  $date = date("Y-m-d"); // Current Date

  if($em == $em2) {
    // Check if email is in valid format
    if(filter_var($em, FILTER_VALIDATE_EMAIL)) {

      $em = filter_var($em, FILTER_VALIDATE_EMAIL);

      // Check if email already exists
      $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

      // Count the number of rows returned
      $num_rows = mysqli_num_rows($e_check);

      if($num_rows >0) {
        array_push($error_array, "Email already in use<br>");
      }

    }
    else {
      array_push($error_array, "Invalid email format<br>");
    }


  }
  else {
    array_push($error_array, "Emails don't match<br>");
  }


  if(strlen($fname) > 25 || strlen($fname) < 2) {
    array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
  }

  if(strlen($lname) > 25 || strlen($lname) < 2) {
    array_push($error_array, "Your last name must be between 2 and 25 characters<br>");
  }

  if($password != $password2) {
    array_push($error_array, "Passwords do not match<br>");
  }
  else {
    if (preg_match('/[^A-Za-z0-9]/', $password)) {
      array_push($error_array, "Your password can only contain letters or numbers<br>");
    }
  }

  if (strlen($password > 30 || strlen($password) < 5)) {
    array_push($error_array, "Password must be between 5 and 30 characters<br>");
  }

  
  if (empty($error_array)) {
    $password = md5($password); // Encrypt password before sending to database

    // Generate username by concatinating first name and last name
    $username = strtolower($fname . "_" . $lname);
    $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

    
    $i = 0;
    // If username exists add number to username
    while (mysqli_num_rows($check_username_query) != 0) {
      $i++;
      $username = $username . "_" . $i;
      $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
    }
    // Profile picture assignment
    $rand = rand(1, 2); // Random number between 1 and 2

    if($rand == 1)
      $profile_pic = "assets/images/profile_pics/default/head_deep_blue.png";
    else if($rand == 2)
      $profile_pic = "assets/images/profile_pics/default/head_amethyst.png";

    $query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");

    array_push($error_array, "<span style='color: #14C800;'>You're all set! Go ahead and login</span><br>");

    // Clear session variables
    $_SESSION['reg_fname'] = "";
    $_SESSION['reg_lname'] = "";
    $_SESSION['reg_email'] = "";
    $_SESSION['reg_email2'] = "";
  }
}

?>