<?php
require_once('connectvars.php');

// Start the session.
session_start();

$error_msg = "";

// 没有设置user_id这个Key时 认为没有登录.
if (!isset($_SESSION['user_id'])) {
    // 是否在进行submit提交
    if (isset($_POST['submit'])) {
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        $user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

        if (!empty($user_username) && !empty($user_password)) {
            $query = "SELECT user_id, username FROM mismatch_user WHERE username = '$user_username' AND password = SHA('$user_password')";
            $data = mysqli_query($dbc, $query);

            // 是否输入正确的账号和密码
            if (mysqli_num_rows($data) == 1) {
                $row = mysqli_fetch_array($data);
//                setcookie('user_id', $row['user_id']);
//                setcookie('username', $row['username']);

                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30));
                setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30));

                $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
                echo '$home_url = ' . $home_url . '<br />';

                echo '$_SERVER[\'HTTP_HOST\'] = ' . $_SERVER['HTTP_HOST'] . '<br />';
                echo 'dirname($_SERVER[\'PHP_SELF\']) = ' . dirname($_SERVER['PHP_SELF']) . '<br />';

                header('Location: ' . $home_url);
            } else {
                $error_msg = 'Sorry, you must enter a valid username and password to log in.';
            }
        } else {
            // 用户或者密码有为空的情况.
            $error_msg = 'Sorry, you must enter your username and password to log in.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mismatch - Log In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h3>Mismatch - Log In</h3>

<?php

if (empty($_SESSION['user_id'])) {
    echo '<p class="error">' . $error_msg . '</p>';
    ?>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend>Log In</legend>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php if (!empty($user_username)) echo $user_username; ?>"/><br/>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password"/>
        </fieldset>
        <input type="submit" value="Log In" name="submit"/>
    </form>

    <?php
} else {
    echo('<p class="login">You are logged in as ' . $_SESSION['username'] . '.</p>');
}
?>

</body>
</html>