<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SendEmails</title>
</head>
<body>

<?php
//    echo "sendemail.php";


$from = 'elmer@makemeelvis.com';
$subject = $_POST['subject'];
$text = $_POST['elvismail'];

if (!empty($subject)) {
    if (!empty($text)) {
        $dbc = mysqli_connect('192.168.0.111', 'root', '123456', 'elvis_store') or die('Error connecting to MySQL server.');
        $query = "SELECT * FROM email_list";
        $result = mysqli_query($dbc, $query);

        while ($row = mysqli_fetch_array($result)) {
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];

            $msg = "Dear $first_name $last_name,\n $text";

            $to = $row['email'];

            mail($to, $subject, $msg, 'From:' . $from);

            echo 'Email sent to: ' . $to . '<br />';
        }
        mysqli_close($dbc);
    }
}


?>
</body>
</html>