<!doctype html>
<html>
<head>
	<title>Login Page</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
	<div class="container-fluid col-sm-4">
	<?php
		session_start();
		require("pdo.php");
		
		if(isset($_POST['submit'])){			
			if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
				$_SESSION['error'] = "Both fields are required";
				header("Location: login.php");
				return;
			}   
			$salt = 'XyZzy12*_';
			$check = hash('md5', $salt.$_POST['pass']);
			$stmt = $pdo->prepare('SELECT user_id, name, password FROM users WHERE email = :em');
			$stmt->execute(array( ':em' => $_POST['email']));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if ( $row !== false ){
				if($row['password']==hash('md5',$salt.$_POST['pass'])){
					session_start();
					$_SESSION['name'] = $row['name'];
					$_SESSION['user_id'] = $row['user_id'];
					
					$_SESSION['success'] = 'You are Logged in successfully';
					// Redirect the browser to index.php
					header("Location: index.php");
					return;
				}
				else{
					$_SESSION['error']="Incorrect Password";
					header("Location: login.php");
					return;
				}
			}
		} 
		if ( isset($_SESSION['success']) ) {
			echo '<div class=col><font color=green>'.$_SESSION['success']."</font></div>";
			unset($_SESSION['success']);
		}
	?>

	<form method="POST" class="contaner">
	
		<div class="row p-4">
			<div class="col"><h3>Log In here...</h3></div>
		</div>
		<div class="row m-2">
			<div class="col">Email :</div>
			<div class="col"><input type = "email" name="email" class="form-control"></div>
		</div>
		<div class="row m-2">
			<div class="col">Password :</div>
			<div class="col"><input type="password" name="pass" id="id_1723" class="form-control"></div>
		</div>
		<?php
			if(!empty($_SESSION['error'])){
				echo "<div class=col align=right><font color=red>".$_SESSION['error']."</font></div>";
				session_destroy();
			}
		?>
		<div class="row m-2">
			<div class="col"><input type="submit" onclick="return doValidate();" value="Log In" name="submit" class="btn btn-success"></div>
			<div class="col" align="right"><input type="button" value="Cancel" id="ind" onclick="location.replace('index.php')" class="btn btn-danger"></div>
		</div>
	</form>
	</div>

	<!--This is a partial implementation of the doValidate() function that only checks the password field.-->
	<!--<script>
		function doValidate() {
		console.log('Validating...');
		try {
		pw = document.getElementById('id_1723').value;
		console.log("Validating pw="+pw);
		if (pw == null || pw == "") {
		alert("Both fields must be filled out");
		return false;
		}
		return true;
		} catch(e) {
		return false;
		}
		return false;
		}
	</script>-->
</body>
</html>