<!doctype html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<form action="index1.php" method="post" enctype="multipart/form-data">
		
		<div class="form-group">
			<label class="required">Choose Image</label>
			<input type="file" name="upload_image" required accept="image/jpeg, image/png" />
		</div>
		
		<input type="submit" name="form_submit" class="btn btn-primary" value="Submit" />
</form>

</body>
</html>
 

<?php
$conn=mysqli_connect("localhost","root","","test");
if(isset($_POST["form_submit"])) {
	
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
 
	if($imageProcess == 1){
	?>
		<div class="alert icon-alert with-arrow alert-success form-alter" role="alert">
			<i class="fa fa-fw fa-check-circle"></i>
			<strong> Success ! </strong> <span class="success-message"> Image Resize Successfully </span>
		</div>
	<?php
	}else{
	?>
		<div class="alert icon-alert with-arrow alert-danger form-alter" role="alert">
			<i class="fa fa-fw fa-times-circle"></i>
			<strong> Note !</strong> <span class="warning-message">Invalid Image </span>
		</div>
	<?php
	}
	$imageProcess = 0;
}
?>



 
