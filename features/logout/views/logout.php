<?php
Configuration::set_current_username('Not Signed In');
$_SESSION['username'] = "Not Signed In";
//            $app = \Slim\Slim::getInstance();
//            $env = $app->environment;
//            unset($env['admin']['username']);
echo "<script> document.location.href='index.php';</script>"; 

// header("Location: index.php");

?> 
