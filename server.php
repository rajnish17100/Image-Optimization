<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$conn = mysqli_connect('localhost', 'root', '', 'test');

// REGISTER student

//upload image first in uploads folder
if(isset($_POST["reg_user"])) {
	
	function resizeImage($resourceType,$image_width,$image_height) {
		$fn = $_FILES['upload_image']['tmp_name'];
        $size = getimagesize($fn);
        $ratio = $size[0]/$size[1]; // width/height
		if( $ratio > 1) {
			$resizeWidth = 500;
			$resizeHeight = 500/$ratio;
		}
		else {
			$resizeWidth = 500*$ratio;
			$resizeHeight = 500;
		}
		//$resizeWidth = 500;
		//$resizeHeight = 500;
		
		$imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
		imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
		return $imageLayer;
    }


	
	$imageProcess = 0;
    if(is_array($_FILES)) {
        $fileName = $_FILES['upload_image']['tmp_name']; 
		
		//$name=explode('.',$_FILES['upload_image']['name']);
		//echo($name[0]); getting the exact name of the image 
		
        $sourceProperties = getimagesize($fileName);
        $resizeFileName = basename($_FILES['upload_image']['name']);
        $uploadPath = "./uploads/";
        $fileExt = pathinfo($_FILES['upload_image']['name'], PATHINFO_EXTENSION);
        $uploadImageType = $sourceProperties[2];
        $sourceImageWidth = $sourceProperties[0];
        $sourceImageHeight = $sourceProperties[1];
        switch ($uploadImageType) {
            case IMAGETYPE_JPEG:
                $resourceType = imagecreatefromjpeg($fileName); 
                $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                imagejpeg($imageLayer,$uploadPath."".$resizeFileName);
				$sql="insert into picture(image) values('$resizeFileName')";
				$result=mysqli_query($conn,$sql);
				$imageProcess = 1;
                break;
 
            case IMAGETYPE_GIF:
                $resourceType = imagecreatefromgif($fileName); 
                $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                imagegif($imageLayer,$uploadPath."".$resizeFileName);
				$sql="insert into picture(image) values('$resizeFileName')";
				$result=mysqli_query($conn,$sql);
				$imageProcess = 1;
                break;
 
            case IMAGETYPE_PNG:
                $resourceType = imagecreatefrompng($fileName); 
                $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                imagepng($imageLayer,$uploadPath."".$resizeFileName);
			    $sql="insert into picture(image) values('$resizeFileName')";
				$result=mysqli_query($conn,$sql);
				$imageProcess = 1;
                break;
 
            default:
                $imageProcess = 0;
                break;
        }
       //move_uploaded_file($fileName, $uploadPath. $resizeFileName. ".". $fileExt);
        //$imageProcess = 1;
    }
 
	if($imageProcess == 0){
		array_push($errors,"image not uploaded");
	}
	
}


if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password_1 = mysqli_real_escape_string($conn, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($conn, $_POST['password_2']);

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
  $user_check_query = "SELECT * FROM register_user WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($conn, $user_check_query);
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

  	$query = "INSERT INTO register_user (username, email,image,password) 
  			  VALUES('$username', '$email','$resizeFileName', '$password')";
  	mysqli_query($conn, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: login.php');
  }

}


// ... 
// LOGIN USER

if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM register_user WHERE username='$username' AND password='$password'";
	
  	$results = mysqli_query($conn, $query);
	$row=mysqli_fetch_assoc($results);
	
	
	
	$email=$row['email'];
	$image=$row['image'];
	
  	if (mysqli_num_rows($results) == 1) {
	  
  	  $_SESSION['username'] = $username;
	  $_SESSION['email']=$email;
	  $_SESSION['image']=$image;
  	  $_SESSION['success'] = "You are now logged in";
	  header('location: home.php');
	}
	
	else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}


