<!doctype html>
<html>
<head>
<title>S P 's Resume Registry</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
<div class="container col-6">
	<div class="row p-4">
		<div class="col" align="center"><h2>S P 's Resume Registry</h2></div>
	</div>
	<?php
	require_once "pdo.php";
	session_start();
	if( empty($_SESSION['name'])){
		echo "<div class=row m-2><div class=col><a href='login.php'>Log In</a></div>";
		echo "<div class=col align=right><a href='signup.php'>Sign Up</a></div></div>";
	}
	echo "<div class=row m-2><div align=left>";
	if ( isset($_SESSION['error']) ) {
		echo '<div class=col><font color=red>'.$_SESSION['error']."</font></div>";
		unset($_SESSION['error']);
	}
	if ( isset($_SESSION['success']) ) {
		echo '<div class=col><font color=green>'.$_SESSION['success']."</font></div>";
		unset($_SESSION['success']);
	}
	echo "</div></div>";
	/*echo('<table border="1">'."\n");
	echo "<thead><th>Name</th><th>Headline</th>";
	if(!empty($_SESSION['name']))
		echo "<th>Action</th>";
	echo "</thead>";*/
	
	$stmt = $pdo->query("SELECT first_name, headline, profile_id FROM profile");
	if($stmt->rowCount()>0)
	{
		echo('<table class="table table-bordered">'."\n");
		echo "<thead><th>Name</th><th>Headline</th>";
		if(!empty($_SESSION['name']))
			echo "<th>Action</th>";
		while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
			echo "<tr><td>";
			echo "<a href='view.php?profile_id=".$row['profile_id']."'>".$row['first_name']."</a>";
			echo("</td><td>");
			echo(htmlentities($row['headline']));
			echo("</td>");
			if(!empty($_SESSION['name']))
			{
			echo('<td>');
			echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
			echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
			echo("</td>");
			}
			echo("</tr>");
		}
	}
	else
		echo "<h4>No data</h4>";

	?>
	</table>
	<?php
	if(!empty($_SESSION['name']))
		{
	?>
	<div class="row m-2">
		<div class="col"><a href="add.php">Add New Entry</a></div>
		<div class="col" align="right"><a href= "logout.php">Log Out</a></div>
	</div>
	<?php
		}
	?>
</div>
</body>
</html>