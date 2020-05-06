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
  $fname = mysqli_real_escape_string($db, $_POST['fname']);
 $lname = mysqli_real_escape_string($db, $_POST['lname']);
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

  	$query = "INSERT INTO users (fname,lname,username, email, password)
  			  VALUES('$fname','$lname','$username', '$email', '$password')";
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
  	}else {
        	  echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Login Failed!</strong> Please insert your data correctly.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        </div>';
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
  $result = mysqli_query($db,$query);
  header('location: personalpage.php');
}


//UPDATE PERSONAL PASSWORD
if (isset($_POST['update_passw'])) {
  $row = getUserInfo($_SESSION['username']);
  $user = $row['username'];
  $oldpassw = mysqli_real_escape_string($db, $_POST['old_passw']);
  $newpassw1 = mysqli_real_escape_string($db, $_POST['new_pass1']);
  $newpassw2 = mysqli_real_escape_string($db, $_POST['new_pass2']);
  $crypt_passw = md5($newpassw1);
  if ($row['password'] != md5($oldpassw)){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Update Failed!</strong> your old password is incorrect.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                </div>';
  }
  else if($newpassw1 != $newpassw2){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Update Failed!</strong> passwords dont match.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                </div>';
  }
  else{
    $query = "UPDATE users SET password='$crypt_passw' WHERE username ='$user'";
    $result = mysqli_query($db,$query);
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Update Succesfull!</strong> your data have been updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                </div>';
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

//UPDATE USERINFO BY ADMIN
if(isset($_POST['admin_update_personal'])){
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $fname = mysqli_real_escape_string($db, $_POST['fname']);
  $lname = mysqli_real_escape_string($db, $_POST['lname']);
  $target = mysqli_real_escape_string($db, $_POST['target']);
  $query = "UPDATE users SET username ='$username', fname = '$fname', lname = '$lname' WHERE username = '$target' ";
  $result = mysqli_query($db,$query);
  if($result){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Updated succesfully!</strong> data have been updated.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
  }else{
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Update Failed!</strong> please try again.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                </div>';
  }
}

//UPDATE PASSWORD BY ADMIN
if(isset($_POST['admin_update_password'])){
  $pass1 = mysqli_real_escape_string($db, $_POST['new_pass1']);
  $pass2 = mysqli_real_escape_string($db, $_POST['new_pass2']);
  $target = mysqli_real_escape_string($db, $_POST['target']);
  $crypted = md5($pass1);
  if($pass1 != $pass2){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Update Failed!</strong> passwords dont match.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                </div>';
                return;
  }
  $query = "UPDATE users SET password ='' WHERE username = '$target' ";
  $result = mysqli_query($db,$query);
  if($result){
              //  header('location: admin.php');
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Updated succesfully!</strong> data have been updated.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
  }else{
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Update Failed!</strong> please try again.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                </div>';
  }
}

//UPDATE EMAIL BY ADMIN
if(isset($_POST['admin_update_email'])){
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $target = mysqli_real_escape_string($db, $_POST['target']);
  $query = "UPDATE users SET email='$email' WHERE username = '$target' ";
  $result = mysqli_query($db,$query);
  if($result){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Updated succesfully!</strong> data have been updated.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
  }else{
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Update Failed!</strong> please try again.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                </div>';
  }
}

//INSERT NEW SELL
if(isset($_POST['new_sell'])){
  $username = $_SESSION['username'];
  $user = getUserInfo($username);
  $usID = $user['ID'];
  $title = mysqli_real_escape_string($db, $_POST['title']);
  $category = mysqli_real_escape_string($db, $_POST['category']);
  $desc = mysqli_real_escape_string($db, $_POST['desc']);
  $precio = mysqli_real_escape_string($db, $_POST['precio']);
  $location = mysqli_real_escape_string($db, $_POST['location']);
  $date = date("Y-m-d H:i:s");
  $query = "INSERT INTO sell (title,category,description, precio, id_user, location, creation_time)
        VALUES('$title','$category','$desc', '$precio', '$usID','$location','$date')";
  mysqli_query($db, $query);
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

function getAllSells($index){
  $db = mysqli_connect('localhost', 'root', '', 'mda');
  $sql = "SELECT * FROM sell LIMIT 9 OFFSET $index ";
  $result = mysqli_query($db, $sql);
  return $result;
}

function getAllCategories(){
  $db = mysqli_connect('localhost', 'root', '', 'mda');
  $sql = "SELECT DISTINCT category FROM sell LIMIT 5";
  $result = mysqli_query($db, $sql);
  return $result;
}

function getAllLocation(){
  $db = mysqli_connect('localhost', 'root', '', 'mda');
  $sql = "SELECT DISTINCT location FROM sell LIMIT 5";
  $result = mysqli_query($db, $sql);
  return $result;
}

function checkSession(){
  if (!isset($_SESSION['username'])){
     header('location: login_required.php');
  }
  return;
}

?>
