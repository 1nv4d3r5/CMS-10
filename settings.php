<?php if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400); 
    exit('400: Bad Request'); 
    } ?>
<body>
	<div id="container">
		<?php
	    if (isset($_GET['success']) && empty($_GET['success'])) {
	        echo '<h3>Your details have been updated!</h3>';	        
	    } else{

            if(empty($_POST) === false) {		
			
				if (isset($_POST['username']) && !empty ($_POST['username'])){
					if (ctype_alpha($_POST['username']) === false) {
					$errors[] = 'Please enter your First Name with only letters!';
					}	
				}
				if (isset($_POST['full_name']) && !empty ($_POST['full_name'])){
					if (ctype_alpha($_POST['full_name']) === false) {
					$errors[] = 'Please enter your Last Name with only letters!';
					}	
				}
				
				if (isset($_POST['gender']) && !empty($_POST['gender'])) {
					
					$allowed_gender = array('undisclosed', 'Male', 'Female');

					if (in_array($_POST['gender'], $allowed_gender) === false) {
						$errors[] = 'Please choose a Gender from the list';	
					}

				} 

				if (isset($_FILES['myfile']) && !empty($_FILES['myfile']['name'])) {
					
					$name 			= $_FILES['myfile']['name'];
					$tmp_name 		= $_FILES['myfile']['tmp_name'];
					$allowed_ext 	= array('jpg', 'jpeg', 'png', 'gif' );
					$a 				= explode('.', $name);
					$file_ext 		= strtolower(end($a)); unset($a);
					$file_size 		= $_FILES['myfile']['size'];		
					$path 			= "avatars";
					
					if (in_array($file_ext, $allowed_ext) === false) {
						$errors[] = 'Image file type not allowed';	
					}
					
					if ($file_size > 2097152) {
						$errors[] = 'File size must be under 2mb';
					}
					
				} else {
					$newpath = $user['image_location'];
				}

				if(empty($errors) === true) {
					
					if (isset($_FILES['myfile']) && !empty($_FILES['myfile']['name']) && $_POST['use_default'] != 'on') {
				
						$newpath = $general->file_newpath($path, $name);

						move_uploaded_file($tmp_name, $newpath);

					}else if(isset($_POST['use_default']) && $_POST['use_default'] === 'on'){
                        $newpath = 'avatars/default_avatar.png';
                    }
							
					$username 	= htmlentities(trim($_POST['username']));
					$full_name 		= htmlentities(trim($_POST['full_name']));	
					$gender 		= htmlentities(trim($_POST['gender']));
					$bio 			= htmlentities(trim($_POST['bio']));
					$image_location	= htmlentities(trim($newpath));
					
					$users->update_user($username, $full_name, $gender, $bio, $image_location, $user_id);
					header('Location: settings.php?success');
					exit();
				
				} else if (empty($errors) === false) {
					echo '<p>' . implode('</p><p>', $errors) . '</p>';	
				}	
            }
    		?>
         
    		<h2>Settings.</h2> <p><b>Note: Information you post here is made viewable to others.</b></p>
            <hr />

            <form action="" method="post" enctype="multipart/form-data">
                <div id="profile_picture">
                 
               		<h3>Change Profile Picture</h3>
                    <ul>
                        
        				<?php
                        if(!empty ($user['image_location'])) {
                            $image = $user['image_location'];
                            echo "<img src='$image'>";
                        }
                        ?>
                        
                        <li>
                        <input type="file" name="myfile" />
                        </li>
                        <?php if($image != 'avatars/default_avatar.png'){ ?>
	                        <li>
	                            <input type="checkbox" name="use_default" id="use_default" /> <label for="use_default">Use default picture</label>
	                        </li>
	                        <?php 
                        }
                        ?>
                    </ul>
                </div>
            
            	<div id="personal_info">
	            	<h3 >Change Profile Information </h3>
	                <ul>
	                    <li>
	                        <h4>Username:</h4>
	                        <input type="text" name="username" value="<?php if (isset($_POST['username']) ){echo htmlentities(strip_tags($_POST['username']));} else { echo $user['username']; }?>">
	                    </li>     
	                    <li>
	                        <h4>Full name: </h4>
	                        <input type="text" name="full_name" value="<?php if (isset($_POST['full_name']) ){echo htmlentities(strip_tags($_POST['full_name']));} else { echo $user['full_name']; }?>">
	                    </li>
	                    <li>
	                        <h4>Gender:</h4>
	                        <?php
	                       	 	$gender 	= $user['gender'];
	                        	$options 	= array("undisclosed", "Male", "Female");
	                            echo '<select name="gender">';
	                            foreach($options as $option){
	                               	if($gender == $option){
	                               		$sel = 'selected="selected"';
	                               	}else{
	                               		$sel='';
	                               	}
	                                echo '<option '. $sel .'>' . $option . '</option>';
	                            }
	                        ?>
	                        </select>
	                    </li>
	                    <li>
	                        <h4>Bio:</h4>
	                        <textarea name="bio"><?php if (isset($_POST['bio']) ){echo htmlentities(strip_tags($_POST['bio']));} else { echo $user['bio']; }?></textarea>
	                    </li>
	            	</ul>    
            	</div>
            	<div class="clear"></div>
            	<hr />
            		<span>Update Changes:</span>
                    <input type="submit" value="Update">
               
            </form>
    </div>
</body>
</html>
<?php
}