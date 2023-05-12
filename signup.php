<!doctype html>
<html>
<head>
	<title>Signup Page</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
	<div class="container-fluid col-sm-6">
	<?php
		session_start();
		require_once "pdo.php";
		
		if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['re_pass'])){
			
			if ( strlen($_POST['name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 || strlen($_POST['re_pass'])<1) {
				$_SESSION['error'] = "All fields are required";
				header("Location: signup.php");
				return;
			}   
			
			//validating email
			$stmt = $pdo->prepare('SELECT email FROM users WHERE email = :em');
			$stmt->execute(array( ':em' => $_POST['email']));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$salt = 'XyZzy12*_';	
			
			if ( $row === false && $_POST['pass']==$_POST['re_pass']){
				$pw = hash('md5', $salt.$_POST['pass']);
				//inserted into users table
				$sql = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array( 
					':name' => $_POST['name'],
					':email' => $_POST['email'],
					':password' => $pw,
				));
				
				$_SESSION['success'] = 'You are registered now';
				header( 'Location: login.php' ) ;
				return;
			}
			else if($_POST['pass']!=$_POST['re_pass']){
				$_SESSION['error'] = "Re-entered password is incorrect";
				header("Location: signup.php");
				return;
			}
			else{
				$_SESSION['error'] = "Email already registered";
				header("Location: signup.php");
				return;
			}
		} 
	?>

	<form method="POST" class="contaner">
		<?php
			if(!empty($_SESSION['success'])){
				echo "<div class=col align=right><font color=red>".$_SESSION['success']."</font></div>";
				session_destroy();
			}
		?>
		<div class="row p-4">
			<div class="col"><h3>Sign Up Yourself</h3></div>
		</div>
		<div class="row m-2">
			<div class="col">Name :</div>
			<div class="col"><input type = "text" name="name" class="form-control"></div>
		</div>
		<div class="row m-2">
			<div class="col">Email :</div>
			<div class="col"><input type = "email" name="email" class="form-control"></div>
		</div>
		<div class="row m-2">
			<div class="col">Password :</div>
			<div class="col"><input type="password" name="pass" id="id_1723" class="form-control"></div>
		</div>
		<div class="row m-2">
			<div class="col">Re-enter Password :</div>
			<div class="col"><input type="password" name="re_pass" class="form-control"></div>
		</div>
		<?php
			if(!empty($_SESSION['error'])){
				echo "<div class=col align=right><font color=red>".$_SESSION['error']."</font></div>";
				session_destroy();
			}
		?>
		<div class="row m-2">
			<div class="col"><input type="submit" value="Sign Up" name="register" class="btn btn-success"></div>
			<div class="col" align="right"><input type="button" value="Cancel" onclick="location.replace('index.php')" class="btn btn-danger"></div>
		</div>
	</form>
	</div>

	<!--This is a partial implementation of the doValidate() function that only checks the password field.-->
</body>
</html>