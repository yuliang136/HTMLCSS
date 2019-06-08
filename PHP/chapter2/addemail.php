<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Addmail</title>
</head>
<body>

<?php
//    echo "Test";

$dbc = mysqli_connect('192.168.0.111', 'root', '123456', 'elvis_store') or die('Error connecting to MySQL server.');

$first_name = $_POST['firstname'];
$last_name = $_POST['lastname'];
$email = $_POST['email'];

echo $first_name . "<br />";
echo $last_name . "<br />";
echo $email . "<br />";

$query = "INSERT INTO email_list(first_name,last_name,email)" .
    "VALUES('$first_name', '$last_name', '$email')";

//echo $query;
//
mysqli_query($dbc, $query) or die('Error querying database.');

echo 'Customer added.';

mysqli_close($dbc);

?>

</body>
</html>

