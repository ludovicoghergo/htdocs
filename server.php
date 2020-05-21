<?php
session_start();
define ('SITE_ROOT', realpath(dirname(__FILE__)));
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

  $tmpFile = $_FILES['avatar']['tmp_name'];
  $newFile = $_FILES['avatar']['name'];
  move_uploaded_file($tmpFile, "./images/user/".$newFile);


  if (empty($fname)){
     alertBox("Insert your first name",0);
     return;
     }
  if (empty($lname)){
      alertBox("Insert your first name",0);
      return;
      }
  if (empty($username)){
      alertBox("Please insert your username",0);
      return;
      }
  if (empty($email)){
      alertBox("Please insert a email",0);
      return;
      }
  if (empty($password_1)){
      alertBox("Please insert the password",0);
      return;
      }
  if (empty($password_2)){
      alertBox("Please insert the verification password",0);
      return;
      }
  if($password_1 != $password_2){
    alertBox("Password doesnt match",0);
    return;
  }
  // first check the database to make sure
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);

  if ($user) { // if user exists
    if ($user['username'] === $username) {
      alertBox("Username already exist",0);
      return;
    }

    if ($user['email'] === $email) {
      alertBox("email already exist",0);
      return;
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (fname,lname,username, email, password,avatar)
  			  VALUES('$fname','$lname','$username', '$email', '$password','$newFile')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  //	header('location: index.php');
  }
}


// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
    alertBox("Insert your username",0);
    return;
  }
  if (empty($password)) {
    alertBox("Insert your password",0);
    return;
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	}else {
      alertBox("Login Failed",0);
      return;
  	}
  }
}
//DELETE USER FROM ADMIN
if (isset($_GET['del_user'])) {
  $username = mysqli_real_escape_string($db, $_GET['del_user']);
  $query = "DELETE FROM users WHERE username='$username'";
  if(mysqli_query($db, $query)){
    alertBox("Account Eliminated",1);
    return;
  }else{
    alertBox("The account couldnt be eliminated",0);
    return;
  }
}

if (isset($_GET['end_sell'])) {
  $title = mysqli_real_escape_string($db, $_GET['end_sell']);
  $date = date("Y-m-d");
  $query = "UPDATE sell as s,orders as o SET s.state = 'terminated', o.end_time = '$date', o.state ='terminated' WHERE title='$title' AND s.ID = o.id_sell";
  mysqli_query($db, $query);
  header('location: user_activities.php');
}


//UPDATE PERSONAL INFO
if (isset($_POST['update_personal'])) {
  $sess_user = $_SESSION['username'];
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $fname = mysqli_real_escape_string($db, $_POST['fname']);
  $lname = mysqli_real_escape_string($db, $_POST['lname']);

  if (empty($username)) {
    alertBox("Insert your username",0);
    return;
  }
  if (empty($fname)) {
    alertBox("Insert your first name",0);
    return;
  }
  if (empty($lname)) {
    alertBox("Insert your last name",0);
    return;
  }


  $query = "UPDATE users SET username ='$username', fname = '$fname', lname = '$lname' WHERE username = '$sess_user' ";
  $result = mysqli_query($db,$query);
  header('location: personalpage.php');
}

//UPDATE PERSONAL AVATAR
if(isset($_POST['update_avatar'])){
  $user = $_SESSION['username'];
  $tmpFile = $_FILES['new_avatar']['tmp_name'];
  $newFile = $_FILES['new_avatar']['name'];
  move_uploaded_file($tmpFile, "./images/user/".$newFile);
  $query = "UPDATE users SET avatar='$newFile' WHERE username ='$user'";
  $result = mysqli_query($db,$query);
  if ($result)
    alertBox("Avatar Updated",1);
  else
    alertBox("Avatar Not Updated",0);
}

//UPDATE PERSONAL PASSWORD
if (isset($_POST['update_passw'])) {
  $row = getUserInfo($_SESSION['username']);
  $user = $row['username'];
  $oldpassw = mysqli_real_escape_string($db, $_POST['old_passw']);
  $newpassw1 = mysqli_real_escape_string($db, $_POST['new_pass1']);
  $newpassw2 = mysqli_real_escape_string($db, $_POST['new_pass2']);

  if (empty($oldpassw)) {
    alertBox("Insert your old password",0);
    return;
  }
  if (empty($newpassw1)) {
    alertBox("Insert your password",0);
    return;
  }
  if (empty($newpassw2)) {
    alertBox("Insert your verification password",0);
    return;
  }


  $crypt_passw = md5($newpassw1);
  if ($row['password'] != md5($oldpassw)){
    alertBox("You old password is not valid",0);
    return;
  }
  else if($newpassw1 != $newpassw2){
    alertBox("Passowrd doesnt match",0);
    return;
  }
  else{
    $query = "UPDATE users SET password='$crypt_passw' WHERE username ='$user'";
    $result = mysqli_query($db,$query);
    alertBox("Update have been made",1);
    return;
  }
}

//UPDATE EMAIL
if (isset($_POST['update_email'])) {
  $row = getUserInfo();
  $user = $row['username'];
  $oldemail = mysqli_real_escape_string($db, $_POST['old_email']);
  $newemail = mysqli_real_escape_string($db, $_POST['new_email']);

  if (empty($oldemail)) {
    alertBox("Insert your username",0);
    return;
  }
  if (empty($newemail)) {
    alertBox("Insert your password",0);
    return;
  }

  if ($row['email'] == $oldemail){
    $query = "UPDATE users SET email='$newemail' WHERE username ='$user'";
    $result = mysqli_query($db,$query);
    header('location: personalpage.php');
  }
  else{
    if (empty($username)) {
      alertBox("Your old email is incorrect",0);
      return;
    }
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
    alertBox("Update have been made",1);
    return;
  }else{
    alertBox("Update have been not made",0);
    return;
  }
}

//UPDATE PASSWORD BY ADMIN
if(isset($_POST['admin_update_password'])){
  $pass1 = mysqli_real_escape_string($db, $_POST['new_pass1']);
  $pass2 = mysqli_real_escape_string($db, $_POST['new_pass2']);
  $target = mysqli_real_escape_string($db, $_POST['target']);
  $crypted = md5($pass1);
  if($pass1 != $pass2){
    alertBox("Password doesnt match",0);
    return;
                return;
  }
  $query = "UPDATE users SET password ='' WHERE username = '$target' ";
  $result = mysqli_query($db,$query);
  if($result){
    alertBox("Update have been made",1);
    return;
  }else{
    alertBox("Update have been not made",0);
    return;
  }
}

//UPDATE EMAIL BY ADMIN
if(isset($_POST['admin_update_email'])){
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $target = mysqli_real_escape_string($db, $_POST['target']);
  $query = "UPDATE users SET email='$email' WHERE username = '$target' ";
  $result = mysqli_query($db,$query);
  if($result){
    alertBox("Update have been made",1);
    return;
  }else{
    alertBox("Update have been not made",0);
    return;
  }
}

//New Lease
if(isset($_POST['buy_product'])){
  $us_id = mysqli_real_escape_string($db, $_POST['us_id']);
  $sell_id = mysqli_real_escape_string($db, $_POST['sell_id']);
  $date = date("Y-m-d H:i:s");
  $query = "INSERT INTO orders (id_sell,id_client,start_time,state)
        VALUES('$sell_id','$us_id','$date','active')";
  $result = mysqli_query($db, $query);
  if($result){
    alertBox("Item has been purchased",1);
  }else{
    alertBox("Something didn't work out",0);
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

  if (empty($username)){
     alertBox("Please Login",0);
     return;
     }
  if (empty($title)){
      alertBox("Please insert a title",0);
      return;
      }
  if (empty($category)){
      alertBox("Please insert a category",0);
      return;
      }
  if (empty($desc)){
      alertBox("Please insert a description",0);
      return;
      }
  if (empty($precio)){
      alertBox("Please insert a precio",0);
      return;
      }
  if (empty($location)){
      alertBox("Please insert a location",0);
      return;
      }


  $date = date("Y-m-d H:i:s");
  $query = "INSERT INTO sell (title,category,description, precio, id_user, location, creation_time)
        VALUES('$title','$category','$desc', '$precio', '$usID','$location','$date')";
  $result = mysqli_query($db, $query);
  if($result){
    alertBox("Sell inserted",1);
  }else{
    alertBox("Something didn't work out",0);
  }
}

function alertBox($message,$success){
  if($success){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> '.$message.'
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
  }else{
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Error!</strong> '.$message.'
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                </div>';
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

function getSell($sell_id){
  $db = mysqli_connect('localhost', 'root', '', 'mda');
  $id = mysqli_real_escape_string($db, $sell_id);
  $sql = "SELECT *, u.ID as us_id, s.ID as sell_id FROM sell as s,users as u WHERE u.ID = s.id_user AND s.ID = $id";
  $result = mysqli_query($db, $sql);
  return $result;
}

function getMySells($index){
  $db = mysqli_connect('localhost', 'root', '', 'mda');
  $username = $_SESSION['username'];
  $id_user = getUserInfo($username);
  $id_user = $id_user['ID'];
  $sql = "SELECT * FROM sell as s inner JOIN users as u ON u.ID = s.id_user WHERE u.ID =$id_user LIMIT 2 OFFSET $index ";
  $result = mysqli_query($db, $sql);
  return $result;
}

function getMyBuys($index){
  $db = mysqli_connect('localhost', 'root', '', 'mda');
  $username = $_SESSION['username'];
  $id_user = getUserInfo($username);
  $id_user = $id_user['ID'];
  $sql = "SELECT * FROM sell as s INNER JOIN orders as o ON s.id = o.id_sell WHERE o.id_client = $id_user LIMIT 2 OFFSET $index ";
  $result = mysqli_query($db, $sql);
  return $result;
}

function getTypeActivities(){
  if (isset($_GET['type'])){
    return $_GET['type'];
  }
  return 'orders';
}

function checkSession(){
  if (!isset($_SESSION['username'])){
     header('location: login_required.php');
  }
  return;
}

function pricesort(){
  $db = mysqli_connect('localhost', 'root', '', 'mda');
  $sql = "SELECT * FROM sell LIMIT 5 OFFSET $index ";
  $result = mysqli_query($db, $sql);
  return $result;
}

?>
