<?php

// If the user is logged in, delete the cookie to log them out.
if(isset($_COOKIE['user_id'])){

    // Delete
    setcookie('user_id', '', time()-3600);
    setcookie('username', '', time()-3600);
}

// Redirect to the home page.
$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';

//echo $home_url;

header('Location: ' . $home_url);

?>
