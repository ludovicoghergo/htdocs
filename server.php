<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array();

// connect to the databases
$db = mysqli_connect('localhost', 'root', '', 'mda');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);

  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (username, email, password)
  			  VALUES('$username', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: index.php');
  }
}


// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: index.php');
  	}else {
      header('location: idfd.php');
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}

if (isset($_GET['del_user'])) {
  $username = mysqli_real_escape_string($db, $_GET['del_user']);
  $query = "DELETE FROM users WHERE username='$username'";
  if(mysqli_query($db, $query)){
    echo '<div class="alert alert-success"> Account Eliminated </div>';
  }else{
    echo '<div class="alert alert-danger"> Something didnt workout </div>';
  }
}

//UPDATE PERSONAL INFO
if (isset($_POST['update_personal'])) {
  $sess_user = $_SESSION['username'];
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $fname = mysqli_real_escape_string($db, $_POST['fname']);
  $lname = mysqli_real_escape_string($db, $_POST['lname']);
  $query = "UPDATE users SET username ='$username', fname = '$fname', lname = '$lname' WHERE username = '$sess_user' ";
  $result = mysqli_query($db,$query);;
  header('location: personalpage.php');
}


//UPDATE PERSONAL PASSWORD
if (isset($_POST['update_passw'])) {
  $row = getUserInfo();
  $user = $row['username'];
  $oldpassw = mysqli_real_escape_string($db, $_POST['old_passw']);
  $newpassw1 = mysqli_real_escape_string($db, $_POST['new_pass1']);
  $newpassw2 = mysqli_real_escape_string($db, $_POST['new_pass2']);
  $crypt_passw = md5($newpassw1);
  if ($row['password'] == md5($oldpassw) && $newpassw1 == $newpassw2){
    $query = "UPDATE users SET password='$crypt_passw' WHERE username ='$user'";
    $result = mysqli_query($db,$query);
    header('location: personalpage.php');
  }
}

//UPDATE EMAIL
if (isset($_POST['update_email'])) {
  $row = getUserInfo();
  $user = $row['username'];
  $oldemail = mysqli_real_escape_string($db, $_POST['old_email']);
  $newemail = mysqli_real_escape_string($db, $_POST['new_email']);
  if ($row['email'] == $oldemail){
    $query = "UPDATE users SET email='$newemail' WHERE username ='$user'";
    $result = mysqli_query($db,$query);
    header('location: personalpage.php');
  }
}

function checkAdmin(){
  $db = mysqli_connect('localhost', 'root', '', 'mda');
  $username = $_SESSION['username'];
  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = mysqli_query($db, $sql);
  if (mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    return $row['username'] == 1;
 }
 header('location: admin_required.php');
}

function getUserInfo($username){
  $db = mysqli_connect('localhost', 'root', '', 'mda');
  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = mysqli_query($db, $sql);
  if (mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    return $row;
 }
 return 0;
}

function checkSession(){
  if (!isset($_SESSION['username'])){
     header('location: login_required.php');
  }
  return;
}

?>
