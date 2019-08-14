<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	
	$mysqli = mysqli_connect("localhost", "root", "", "dbUser");

	$name = $_POST["fname"];
	$surname = $_POST["lname"];
	$email = $_POST["email"];
	$date = $_POST["date"];
	$pass = $_POST["pass"];

	$query = "INSERT INTO tbusers (name, surname, email, birthday, password) VALUES ('$name', '$surname', '$email', '$date', '$pass');";

	$res = mysqli_query($mysqli, $query) == TRUE;
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>IMY 220 - Assignment 3</title>
	<meta name="author" content="Name Surname">
	<!-- Replace Name Surname with your name and surname -->
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<?php 
			if($res)
				echo '<div class="alert alert-primary mt-3" role="alert">
  						The account has been created
  					</div>';
  			else
  				echo '<div class="alert alert-danger mt-3" role="alert">
  						The account could not be created
  					</div>';
		?>
	</div>
</body>
</html>