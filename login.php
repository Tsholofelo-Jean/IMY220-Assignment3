<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	// Your database details might be different
	$mysqli = mysqli_connect("localhost", "root", "", "dbUser");

	$email = isset($_POST["email"]) ? $_POST["email"] : false;
	$pass = isset($_POST["pass"]) ? $_POST["pass"] : false;	
	//file upload
	function uploadImages()
	{
		
		//allowed extensions
		$imageExtensions = ['jpeg', 'jpg'];
		$errors = []; //will store errors;
		$directory = 'gallery/'; //directory where file will be stored
		$file = $_FILES['picToUpload'];
		$numFiles = count($file['name']);
		
		for($i = 0; $i < $numFiles; $i++)
		{
			$fileImUploading = $file['name'][$i]; //actual file i am uploading
			$fileType = $_FILES['picToUpload']['type'][$i];//img size
			$fileSize = $_FILES['picToUpload']['size'][$i];//img size
			$tempFileName =$_FILES['picToUpload']['tmp_name'][$i];
			$fileExtension = explode('.' ,$fileImUploading);
			$fileExtension = end($fileExtension);
			$fileExtension = strtolower($fileExtension);
			$filePath = $directory . basename($fileImUploading); //path of file im uploading
			
			if(isset($_POST['submit']))
			{
				if($fileSize > 1000000) //check file size
				{
					$errors[] = 'File too large. Please select another file';
				}
				
				if(!in_array($fileExtension, $imageExtensions)) //check file extension
				{
					$errors[] = 'Invalid file extension. Only jpg and jpeg may be used';
				}
				
				//upload if no errors exist
				if(empty($errors))
				{
					$fineToUpload = move_uploaded_file($tempFileName, $filePath);
					
					if($fineToUpload){
						$user_id = $_POST["user_id"]; 
						$mysqli = mysqli_connect("localhost", "root", "", "dbUser");
						$query = "INSERT INTO tbgallery (user_id, filename) VALUES ('$user_id', '$fileImUploading')";
						$mysqli->query($query);
					}
					else
					{
						echo 'File upload error';
					}
				}
				else
				{
					foreach($errors as $error)
					{
						echo $error. ' <----\n';
					}
				}
			}
		}
	}
	
	if(isset($_POST["submit"]))
	{
		uploadImages();
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 3</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Tsholofelo Gomba">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form enctype='multipart/form-data' method='POST' action='login.php'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload[]' multiple='multiple' id='picToUpload' /><br/>
									<input type='hidden' name='pass' value='" .$row["password"]. "'>
									<input type='hidden' name='email' value='" .$row["email"]. "'>
									<input type='hidden' name='user_id' value='" .$row["user_id"]. "'>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
						  	</form>";
					echo "<h2>Image Gallery</h2><div class='row imageGallery'>";
					$user_id = $row["user_id"];
					$picturesQuery = "SELECT * FROM tbgallery WHERE user_id = '$user_id'";
					$res = $mysqli->query($picturesQuery);
					$counter = 0;
					while($row = $res->fetch_assoc()){
						if($counter == 3)
							echo "</div><div class='row imageGallery'>";
						echo "<div class='col-3' style='background-image: url(gallery/" . $row["filename"] .")'></div>";
					}
					echo "</div>";
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
	</div>
</body>
</html>