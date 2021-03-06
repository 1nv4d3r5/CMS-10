<?php
ob_start();
//session_start();
require 'openid.php';

function steamlogin($domain)
{
try {
    // Change 'localhost' to your domain name.
    $openid = new LightOpenID($domain);
    if (!$openid->mode) {
        if (isset($_GET['login'])) {
            $openid->identity = 'http://steamcommunity.com/openid';
            header('Location: ' . $openid->authUrl());
        }
    echo "<form action=\"index.php?page=tournament&login\" method=\"post\"> <input type=\"image\" src=\"http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_large_border.png\"></form>";
} elseif ($openid->mode == 'cancel') {
        echo 'User has canceled authentication!';
    } else {
        if ($openid->validate()) {
                $id = $openid->identity;
                $ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
                preg_match($ptn, $id, $matches);

                session_start();
                $_SESSION['steamid'] = $matches[1];

                 header('Location: '.$_SERVER['REQUEST_URI']);

        } else {
                echo "User is not logged in.\n";
        }

    }
} catch (ErrorException $e) {
    echo $e->getMessage();
}
}
