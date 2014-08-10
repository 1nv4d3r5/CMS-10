<?php if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400); 
    exit('400: Bad Request'); 
    } ?>
<?php 
if (isset($_POST['submit'])) {

	if(empty($_POST['title']) || empty($_POST['url']) || empty($_POST['content'])){

		$errors[] = 'All fields are required.';
	}
	if(empty($errors) === true){
		
		$username 	= htmlentities($_POST['username']);
		$email	= htmlentities($_POST['email']);
		$password = htmlentities($_POST['password']);

		$users->register($username, $password, $email);
		
		header('Location: new_user.php?success');
		exit();
	}
}
?>
<body>	
<div id="content">
  <div class="box">
    <div class="box-header">Admin Panel</div>
    <div class="box-body">
		<h1>Add User</h1>
		
		<?php
		if (isset($_GET['success']) && empty($_GET['success'])) {
		  echo 'User created.';
		}
		?>

		<form method="post" action="">
			<h4>Username:</h4>
			<input type="text" name="username" value="<?php if(isset($_POST['username'])) echo htmlentities($_POST['username']); ?>" >
			<h4>email:</h4>
			<input type="text" name="email" value="<?php if(isset($_POST['email'])) echo htmlentities($_POST['email']); ?>"/>	
			<h4>password:</h4>
			<input type="text" name="password" value="<?php if(isset($_POST['password'])) echo htmlentities($_POST['password']); ?>"/>	
			<br>
			<input type="submit" name="submit" />
		</form>

		<?php 
		if(empty($errors) === false){
			echo '<p>' . implode('</p><p>', $errors) . '</p>';	
		}

		?>
    </div>
    </div>
	</div>
</body>
</html>

