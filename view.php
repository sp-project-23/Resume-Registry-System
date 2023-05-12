<?php
require_once "pdo.php";
session_start();
$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for user_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
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
	<title>Profile View</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>	
	<form method="post" class="container">
		<div class="container-fluid col-sm-6">
			<div class="row p-4">
				<div class="col"><h3>Profile</h3></div>
			</div>
			<div class="row m-2">
				<div class="col">First Name :</div>
				<div class="col"><?php echo $fn; ?></div>
			</div>
			<div class="row m-2">
				<div class="col">Last Name :</div>
				<div class="col"><?php echo $ln; ?></div>
			</div>
			<div class="row m-2">
				<div class="col">Email :</div>
				<div class="col"><?php echo $e; ?></div>
			</div>
			<div class="row m-2">
				<div class="col">Headline :</div>
				<div class="col"><?php echo $h; ?></div>
			</div>
			<div class="row m-2">
				<div class="col">Summary :</div>
				<div class="col"><?php echo $s; ?></div>
			</div>
			<!--<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">-->
			<!--<input type="hidden" name="profile_id" value="<?php echo $profile_id; ?>">-->
			<div class="row m-2">
				<div class="col-sm-6" align="left"><input type="button" value="Done" id="done" onclick="location.replace('index.php')" class="btn btn-warning">
			</div>
		</div>
	</form>
</body>
</html>
