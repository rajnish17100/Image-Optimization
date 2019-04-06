<?php
//user home page
 session_start(); 
 
 $db = mysqli_connect('localhost', 'root', '', 'test');
 
 if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
	unset($_SESSION['image']);
	unset($_SESSION['email']);
	
  	header("location: login.php");
  }
  
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  
    if (!isset($_SESSION['email'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  
   
  
  
    if (!isset($_SESSION['image'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  

  
 

	

if(isset($_SESSION['username'])){
	$username=$_SESSION['username'];
	$email=$_SESSION['email'];
	$image=$_SESSION['image'];
	
}

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>


.avatar {
  vertical-align: middle;
  width: 50px;
  height: 50px;
  border-radius: 50%;
}
.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 300px;
  margin: auto;
  text-align: center;
  font-family: arial;
}

.title {
  color: grey;
  font-size: 18px;
}

button {
  border: none;
  outline: 0;
  display: inline-block;
  padding: 8px;
  color: white;
  background-color: #000;
  text-align: center;
  cursor: pointer;
  width: 100%;
  font-size: 18px;
}

a {
  text-decoration: none;
  font-size: 22px;
  color: white;
}

button:hover, a:hover {
  opacity: 0.7;
}

</style>
</head>
<body>
<h2 style="text-align:center">User Profile Card</h2>

<div class="card">
  <img src="uploads/<?php echo($image);?>" alt="Avatar" style="width:200px">
  <img src="uploads/<?php echo($image);?>" alt="Avatar" class="avatar">
  <h1><?php echo($username);?></h1>
  <p class="title"><?php echo($email);?></p>
  
  <div style="margin: 24px 0;">
    <a href="#"><i class="fa fa-dribbble"></i></a> 
    <a href="#"><i class="fa fa-twitter"></i></a>  
    <a href="#"><i class="fa fa-linkedin"></i></a>  
    <a href="#"><i class="fa fa-facebook"></i></a> 
  </div>
  <p><button><a href="login.php">Go Back</a></button></p>
</div>





</body>
</html>