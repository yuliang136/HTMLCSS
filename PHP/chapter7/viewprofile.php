<?php
session_start();

// If the session vars aren't set, try to set them with a cookie
if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
        $_SESSION['user_id'] = $_COOKIE['user_id'];
        $_SESSION['username'] = $_COOKIE['username'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mismatch - View Profile</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<h3>Mismatch - View Profile</h3>

<?php
require_once('appvars.php');
require_once('connectvars.php');

// Make sure the user is logged in before going any further.
if (!isset($_SESSION['user_id'])) {
    echo '<p class="login">Please <a href="login.php">log in</a> to access this page.</p>';
    exit();
} else {
    echo('<p class="login">You are logged in as ' . $_SESSION['username'] . '. <a href="logout.php">Log out</a>.</p>');
}

// Connect to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!isset($_GET['user_id'])) {
    $query = "SELECT username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '" . $_SESSION['user_id'] . "'";
} else {
    $query = "SELECT username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '" . $_GET['user_id'] . "'";
}

//echo '$query = ' . $query;

$data = mysqli_query($dbc, $query);

//

if (mysqli_num_rows($data) == 1) {
    $row = mysqli_fetch_array($data);

    // 每行数据都进行了判空.

    echo '<table>';

    if (!empty($row['username'])) {
        echo '<tr><td class="label">Username:</td><td>' . $row['username'] . '</td></tr>';
    }
    if (!empty($row['first_name'])) {
        echo '<tr><td class="label">First name:</td><td>' . $row['first_name'] . '</td></tr>';
    }
    if (!empty($row['last_name'])) {
        echo '<tr><td class="label">Last name:</td><td>' . $row['last_name'] . '</td></tr>';
    }

    if (!empty($row['gender'])) {
        echo '<tr>';
        echo '<td class="label">Gender:</td>';
        echo '<td>';

        if ($row['gender'] == 'M') {
            echo 'Male';
        } else if ($row['gender'] == 'F') {
            echo 'Female';
        } else {
            echo '?';
        }
        echo '</td></tr>';
    }

    if (!empty($row['birthdate'])) {
        if(!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])){
            echo '<tr><td class="label">Birthdate:</td><td>' . $row['birthdate'] . '</td></tr>';
        }
        else{
//            echo '<tr><td class="label">Birthdate:</td><td>' . $row['birthdate'] . '</td></tr>';
            list($year, $month, $day) = explode('-', $row['birthdate']);
            echo '<tr><td class="label">Year born:</td><td>' . $year . '</td></tr>';
        }
    }

    if (!empty($row['city']) || !empty($row['state'])) {
        echo '<tr><td class="label">Location:</td><td>' . $row['city'] . ', ' . $row['state'] . '</td></tr>';
    }
    if (!empty($row['picture'])) {
        echo '<tr><td class="label">Picture:</td><td><img src="' . MM_UPLOADPATH . $row['picture'] .
            '" alt="Profile Picture" /></td></tr>';
    }



    echo '</table>';
    if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
        echo '<p>Would you like to <a href="editprofile.php">edit your profile</a>?</p>';
    }
} else {
    echo '<p class="error">There was a problem accessing your profile.</p>';
}


mysqli_close($dbc);

?>

</body>
</html>