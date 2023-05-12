<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
    $sql = "DELETE FROM profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = 'Profiles deleted';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT first_name, last_name, profile_id FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}
$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
?>
<!doctype html>
<html>
<head>
	<title>Deleting Profile</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
<form method="post" class="container">
	<input type="hidden" name="profile_id" value="<?php echo $_GET['profile_id']; ?>">
	<div class="container-fluid col-sm-6">
		<div class="row p-4">
			<div class="col"><h3>Confirm! Deleting the profile of <?php echo $fn," ",$ln; ?></h3></div>
		</div>		
		<div class="row m-2">
			<div class="col-sm-6"><input type="submit" value="Yes" name="delete" class="btn btn-danger"></div>
			<div class="col-sm-6" align="right"><input type="button" value="No" id="ind" onclick="location.replace('index.php')" class="btn btn-danger"></div>
		</div>
	</div>
</form>
</body>
</html>
