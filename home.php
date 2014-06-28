<?php 
require 'core/init.php';
$general->logged_out_protect();

$username 	= htmlentities($user['username']); 
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css" >
	<title>Home</title>
</head>
<body>	
	<div id="container">
		<?php include 'includes/menu.php'; ?>
		<h1>Hello <?php echo $username, '!'; ?></h1>
		<?php 
		require_once('includes/application.php');

$app=new Application();
?>
	</div>
</body>
</html>
