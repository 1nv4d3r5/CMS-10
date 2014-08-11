<?php if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400); 
    exit('400: Bad Request'); 
    } ?>
<?php 
//$general->logged_in_protect();
$text = '';
if (isset($_POST['submit'])) {

	if(empty($_POST['title']) || empty($_POST['url']) || empty($_POST['editPage'])){

		$errors[] = 'All fields are required.';

	}

	if(empty($errors) === true){
		
		$title 	= htmlentities($_POST['title']);
		$url 	= htmlentities($_POST['url']);
		$editPage 	= htmlentities($_POST['editPage']);
        $permission = htmlentities($_POST['permission']);
        $position = htmlentities($_POST['position']);

		$pages->create_Post($title, $url, $editPage);
		$pageArray = $pages->fetch_Page("title, editPage", "url", $url);
		//print_r($pageArray);
		$pages->generate_page($pageArray['title'], $url ,$pageArray['editPage']);
		$pages->create_nav($title, $url, $permission, $position);
        $permissions->add_usergroup($permission, $url);
	}
}
	if(isset($_POST['editPage'])) $text = htmlentities($_POST['editPage']);
?>
<script src="//cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>    
<body>	
<div id="content">
  <div class="box">
    <div class="box-header">Admin Panel</div>
    <div class="box-body">
		<h1>Create Page</h1>
		
		<?php
		if (isset($_GET['success']) && empty($_GET['success'])) {
		  echo 'Page created.';
		}
		?>

		<form method="post" action="">
			<h4>Title:</h4>
			<input type="text" name="title" value="<?php if(isset($_POST['title'])) echo htmlentities($_POST['title']); ?>" >
			<h4>URL:</h4>
			<input type="text" name="url" value="<?php if(isset($_POST['url'])) echo htmlentities($_POST['url']); ?>"/>	
            <h4>Position:</h4>
			<input type="text" name="position" value="<?php if(isset($_POST['position'])) echo htmlentities($_POST['position']); ?>"/>	
            <h4>Usergroups that have access:</h4>
			<input type="text" name="permission" value="<?php if(isset($_POST['permission'])) { echo htmlentities($_POST['permission']); } else { echo ("guest, user, administrator"); } ?>"/>	
			<h4>Content:</h4>
            <textarea name="text" id="editPage"><?php echo htmlspecialchars($text) ?></textarea>
		
			<br />
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
    <script type="text/javascript">
    	CKEDITOR.replace( 'editPage' );
    </script>
</body>
</html>

