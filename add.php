<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

    // Data validation
    if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || 
		 strlen($_POST['summary']) < 1) {
        $_SESSION['error'] = "All fields are required";
        header("Location: add.php");
        return;
    }    
	if ( strpos($_POST['email'],'@') === false ) {
		$_SESSION['error'] = 'Email must contain @';
		header("Location: add.php");
		return;
	}
	
	//inserted into profile table
    $sql = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) VALUES (:u_id, :fname, :lname, :email, :headline, :summary)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':u_id' => $_SESSION['user_id'],
						 ':fname' => $_POST['first_name'],
						 ':lname' => $_POST['last_name'],
						 ':email' => $_POST['email'],
						 ':headline' => $_POST['headline'],
						 ':summary' => $_POST['summary']
						 )
					);
    $_SESSION['success'] = 'Profile added';
    header( 'Location: index.php' ) ;
    return;
}
?>
<!doctype html>
<html>
<head>
	<title>Adding Profile</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
<div class="container-fluid col-sm-6">
	<form method="post" class="container">
	<div class="row p-4">
	<div class="col"><h3>Adding New Profile</h3></div>
	</div>
	<div class="row m-2">
	<div class="col">First Name :</div>
	<div class="col"><input type="text" name="first_name" class="form-control"></div>
	</div>
	<div class="row m-2">
	<div class="col">Last Name :</div>
	<div class="col"><input type="text" name="last_name" class="form-control"></div>
	</div>
	<div class="row m-2">
	<div class="col">Email :</div>
	<div class="col"><input type="text" name="email" class="form-control"></div>
	</div>
	<div class="row m-2">
	<div class="col">Headline :</div>
	<div class="col"><input type="text" name="headline" class="form-control"></div>
	</div>
	<div class="row m-2">
	<div class="col">Summary :</div>
	<div class="col"><textarea name="summary" class="form-control"></textarea></div>
	</div>
	<?php
		// Flash pattern
		if ( isset($_SESSION['error']) ) {
		echo '<div class=col align=right><font color=red>'.$_SESSION['error']."</font></div>";
		unset($_SESSION['error']);
		}
	?>
	<div class="row m-2">
	<div class="col-sm-6"><input type="submit" value="Add" class="btn btn-info"></div>
	<div class="col-sm-6" align="right"><input type="button" value="Cancel" class="btn btn-info" onclick="location.replace('index.php')"></div>
	</div>
	</form>
</div>
</body>
</html>
