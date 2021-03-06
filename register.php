<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
    }

use Respect\Validation\Validator as v;
$username_validator = v::alnum()->noWhitespace();
$password_length = v::alnum()->noWhitespace()->between(6, 18);


if (isset($_POST['submit'])) {

    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {

        $errors[] = 'All fields are required.';

    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        if ($users->user_exists($username) === true) {
            $errors[] = 'That username already exists';
        }
        if ($username_validator->validate($username) === false) {
            $errors[] = 'A username may only contain alphanumeric characters';
        }
        if ($password_length->validate(strlen($password)) === false) {
            $errors[] = 'Your password must be at least 6 characters and at most 18 characters';
        }
        if (v::email()->validate($email) === false) {
            $errors[] = 'Please enter a valid email address';
        } elseif ($users->email_exists($email) === true) {
            $errors[] = 'That email already exists.';
        }
    }

    if (empty($errors) === true) {
        $users->register($username, $password, $email, $settings->production->site->url, $settings->production->site->name, $settings->production->site->email);
        header('Location: index.php?page=register.php&success');
        exit();
    }
}
?>
<div class="wrapper">
    <section class="content">
        <article>
		<div id="form-header">Register</div>

		<?php
        if (isset($_GET['success']) && empty($_GET['success'])) {
          echo 'Thank you for registering. Please check your email. <br />It should be instant, so please check your spam folder!';
        }
        ?>
		<form method="post" action="">
			<h4>Username:</h4>
			<input type="text" name="username" value="<?php if(isset($username)) echo htmlentities($username); ?>" >
			<h4>Password:</h4>
			<input type="password" name="password" />
			<h4>Email:</h4>
			<input type="text" name="email" value="<?php if(isset($email)) echo htmlentities($email); ?>"/>
			<br /><br />
            <input type="submit" name="submit" value="Register" />
		</form>
		<?php
        if (empty($errors) === false) {
            echo '<p>' . implode('</p><p>', $errors) . '</p>';
        }

        ?>
            </article>
        </section>
</div>
