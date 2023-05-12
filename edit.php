<?php
	require_once "pdo.php";
	session_start();

	if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['headline']) && isset($_POST['summary']) && 
		 isset($_POST['email']) && isset($_POST['user_id']) ) {

		// Data validation
		if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || 
			 strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
			$_SESSION['error'] = 'All fields are required';
			header("Location: edit.php?profile_id=".$_POST['profile_id']);
			return;
		}

		if ( strpos($_POST['email'],'@') === false ) {
			$_SESSION['error'] = 'Email must contain @';
			header("Location: edit.php?profile_id=".$_POST['profile_id']);
			return;
		}
		
		$stmt = $pdo->prepare('SELECT  first_name, last_name, email, headline, summary FROM profile WHERE profile_id = :profile_id');
		$stmt->execute(array( ':profile_id' => $_POST['profile_id']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if($row['first_name']!=$_POST['first_name'] || $row['last_name']!=$_POST['last_name'] || $row['email']!=$_POST['email'] ||
		   $row['headline']!=$_POST['headline'] || $row['summary']!=$_POST['summary'])	{
			
			$sql = "UPDATE profile SET user_id = :uid, first_name = :fname,last_name = :lname, email = :email, headline = :headline, 
					summary = :summary WHERE profile_id = :profile_id";
			
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':uid' => $_SESSION['user_id'],
				':fname' => $_POST['first_name'],
				':lname' => $_POST['last_name'],
				':email' => $_POST['email'],
				':headline' => $_POST['headline'],
				':summary' => $_POST['summary'],
				':profile_id' => $_POST['profile_id']));
			
			$_SESSION['success'] = 'Record updated';
			header( 'Location: index.php' ) ;
			return;
		}
		else{
			$sql = "UPDATE profile SET first_name = :fname,last_name = :lname, email = :email, headline = :headline, 
					summary = :summary WHERE profile_id = :profile_id";
			
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':fname' => $_POST['first_name'],
				':lname' => $_POST['last_name'],
				':email' => $_POST['email'],
				':headline' => $_POST['headline'],
				':summary' => $_POST['summary'],
				':profile_id' => $_POST['profile_id']));
				
			$_SESSION['success'] = 'You doesn'.'t make update';	
			header( 'Location: index.php' ) ;
			return;
		}	
	}

	// Guardian: Make sure that user_id is present
	/*if ( ! isset($_GET['profile_id']) ) {
	  $_SESSION['error'] = "Missing user id";
	  header('Location: index.php');
	  return;
	}*/

	$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
	$stmt->execute(array(":xyz" => $_GET['profile_id']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ( $row === false ) {
		$_SESSION['error'] = 'Bad value for user id';
		header( 'Location: index.php' ) ;
		return;
	}


	$fn = htmlentities($row['first_name']);
	$ln = htmlentities($row['last_name']);
	$e = htmlentities($row['email']);
	$h = htmlentities($row['headline']);
	$s = htmlentities($row['summary']);
	$user_id = $row['user_id'];
	$profile_id = $row['profile_id'];

?>
<!doctype html>
<html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>	
	<form method="post" class="container">
		<div class="container-fluid col-sm-6">
			<div class="row p-4">
				<div class="col"><h3>Editing Profile</h3></div>
			</div>
			<div class="row m-2">
				<div class="col">First Name:</div>
				<div class="col"><input type="text" class="form-control" name="first_name" value="<?php echo $fn; ?>"></div>
			</div>
			<div class="row m-2">
				<div class="col">Last Name:</div>
				<div class="col"><input type="text" class="form-control" name="last_name" value="<?php echo $ln; ?>"></div>
			</div>
			<div class="row m-2">
				<div class="col">Email:</div>
				<div class="col"><input type="text" class="form-control" name="email" value="<?php echo $e; ?>"></div>
			</div>
			<div class="row m-2">
				<div class="col">Headline:</div>
				<div class="col"><input type="text" class="form-control" name="headline" value="<?php echo $h; ?>"></div>
			</div>
			<div class="row m-2">
				<div class="col">Summary:</div>
				<div class="col"><textarea class="form-control" name="summary"><?php echo $s; ?></textarea></div>
			</div>
			<?php
				// Flash pattern
				if ( isset($_SESSION['error']) ) {
					echo "<div class=col align=right><font color=red>".$_SESSION['error']."</font></div>";
					unset($_SESSION['error']);
				}
			?>
			<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
			<input type="hidden" name="profile_id" value="<?php echo $profile_id; ?>">
			<div class="row m-2">
				<div class="col-sm-6"><input type="submit" value="Save" class="btn btn-primary"></div>
				<div class="col-sm-6" align="right"><input type="button" value="Cancel" id="ind" onclick="location.replace('index.php')" class="btn btn-primary">
			</div>
		</div>
	</form>
</body>
</html>