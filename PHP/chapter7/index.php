<?php
session_start();
//if (!isset($_SESSION['user_id'])) {
//    if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
//        $_SESSION['user_id'] = $_COOKIE['user_id'];
//        $_SESSION['username'] = $_COOKIE['username'];
//    }
//}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mismatch - Where opposites attract!</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h3>Mismatch - Where opposites attract!</h3>

<?php

require_once('appvars.php');
require_once('connectvars.php');

echo '$_SESSION[\'user_id\'] = ' . $_SESSION['user_id'] . '<br />';

if (isset($_SESSION['username'])) {
    echo '&#10084; <a href="viewprofile.php">View Profile</a><br />';
    echo '&#10084; <a href="editprofile.php">Edit Profile</a><br />';
    echo '&#10084; <a href="logout.php">Log Out (' . $_SESSION['username'] . ')</a>';
} else {
    echo '&#10084; <a href="login.php">Log In</a><br />';
    echo '&#10084; <a href="signup.php">Sign Up</a>';
}


// Connect to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Retrieve the user data from MySQL
$query = "SELECT user_id, first_name, picture FROM mismatch_user WHERE first_name IS NOT NULL ORDER BY join_date DESC LIMIT 5";
$data = mysqli_query($dbc, $query);

echo '<h4>Latest members:</h4>';

echo '<table>';

while ($row = mysqli_fetch_array($data)) {
    if (is_file(MM_UPLOADPATH . $row['picture']) && filesize(MM_UPLOADPATH . $row['picture']) > 0) {
        echo '<tr><td><img src="' . MM_UPLOADPATH . $row['picture'] . '" alt="' . $row['first_name'] . '" /></td>';
    } else {
        echo '<tr><td><img src="' . MM_UPLOADPATH . 'nopic.jpg' . '" alt="' . $row['first_name'] . '" /></td>';
    }


    if(isset($_SESSION['user_id'])){
        echo '<td><a href="viewprofile.php?user_id=' . $row['user_id'] . '">' . $row['first_name'] .'</a></td></tr>';
    }
    else{
        echo '<td>' . $row['first_name'] . '</td></tr>';
    }

}
echo '</table>';

mysqli_close($dbc);

?>

</body>
</html>