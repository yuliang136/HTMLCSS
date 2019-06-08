<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make Me Elvis - Remove Email</title>
</head>
<body>

<?php

$dbc = mysqli_connect('192.168.0.111', 'root', '123456', 'elvis_store') or die('Error connecting to MySQL server.');

$email = $_POST['email'];

echo "$email";

$query = "DELETE FROM email_list WHERE email = '$email'";

mysqli_query($dbc, $query) or die('Error querying database.');

echo 'Customer removed: ' . $email;

mysqli_close($dbc);
?>

</body>
</html>
