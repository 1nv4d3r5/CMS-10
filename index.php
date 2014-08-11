<?php 
require 'core/init.php';
include("templates/default/head.php"); 
include("templates/default/header.php"); 
    
    $general->logged_in_protect();
    $basePath      = "";
    $userID         ="";
    if(isset($user['id'])) $userID = $user['id'];
        
        if (isset($_GET['page'])) {
            $page        = $_GET['page'];
            $page       = preg_replace('/[^\da-z]/i', '', $page);
            if (substr($page, -4) == ".php") {
                $page = substr($page, 0, -4);
            }
                if ($permissions->has_access("", $page, "guest") or $permissions->user_access($userID, $page) or $permissions->has_access($userID, $page, "")) {
                    $dir=getcwd();
                    $files = scandir($dir);
                    if (in_array($page.".php", $files)) {
                        include $page.".php";
                    } else {
                        header("HTTP/1.0 400 Bad Request", true, 400); 
                        exit('page cannot be found'); 
                    }
            } else {
                echo "ACCESS DENIED";   
            }
        } else {
               include "home.php";
        } 
        
?>
<?php include("templates/default/footer.php"); ?>
