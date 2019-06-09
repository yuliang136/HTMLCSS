<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make Me Elvis - Remove Email</title>
</head>
<body>








<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

    <?php

    $dbc = mysqli_connect('192.168.1.207', 'root', '123456', 'elvis_store') or die('Error connecting to MySQL server.');

    if(isset($_POST['submit'])){
        foreach($_POST['todelete'] as $delete_id){
//            echo $delete_id . '<br />';
            $query = "DELETE FROM email_list WHERE id=$delete_id";
            mysqli_query($dbc, $query) or die('Error querying database.');
        }

        echo 'Customer(s) removed.<br />';
    }



    $query = "SELECT * FROM email_list";
    $result = mysqli_query($dbc, $query);

    while ($row = mysqli_fetch_array($result)) {
        echo '<input type="checkbox" value="' . $row['id'] . '" name="todelete[]" />';
        echo $row['first_name'];
        echo ' ' . $row['last_name'];
        echo ' ' . $row['email'];
        echo '<br />';
    }

    mysqli_close($dbc);


    ?>

    <input type="submit" name="submit" value="Remove"/>

</form>


</body>
</html>
