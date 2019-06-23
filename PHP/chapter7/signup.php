<?php

// Start the session.
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mismatch - Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h3>Mismatch - Sign Up</h3>

<?php

require_once('appvars.php');
require_once('connectvars.php');

// Connect to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or Die("Database connect Wrong...");

// 如果是进行提交的.
if(isset($_POST['submit'])){
    // Grab the profile data from the POST.
    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
    $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));

    if(!empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2)){
        // Make sure someone isn't already registered using this username.
        $query = "SELECT * FROM mismatch_user WHERE username = '$username'";
        $data = mysqli_query($dbc, $query);
        if(mysqli_num_rows($data) == 0){
            $query = "INSERT INTO mismatch_user (username, password, join_date) VALUES ('$username',SHA('$password1'),NOW())";
            mysqli_query($dbc, $query);

            // Confirm success with the user.
            echo '<p>Your new account has been successfully created. You\'re now ready to log in and' .
                '<a href="editprofile.php">edit your profile</a>.</p>';

            mysqli_close($dbc);
            // 这里为什么退出
            exit();
        }
        else{
            echo '<p class="error">An account already exists for this username. Please use a different ' .
                'address.</p>';
            $username = "";
        }
    }
    else{
        echo '<p class="error">You must enter all of the sign-up data, including the desired password twice.</p>';
    }
}
mysqli_close($dbc);

?>

<p>Please enter your username and desired password to sign up to Mismatch.</p>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
        <legend>Registration Info</legend>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php if(!empty($username)) echo $username; ?>" /><br />
        <label for="password1">Password:</label>
        <input type="password" id="password1" name="password1" /><br />
        <label for="password2">Password (retype):</label>
        <input type="password" id="password2" name="password2" /><br />
    </fieldset>
    <input type="submit" value="Sign Up" name="submit" />
</form>

</body>
</html>