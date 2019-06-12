<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guitar Wars - Remove a High Score</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php

require_once('appvars.php');
require_once('connectvars.php');

//echo 'Here 1' . '<br />';

if(isset($_GET['id']) && isset($_GET['date']) && isset($_GET['name']) && isset($_GET['score']) && isset($_GET['screenshot']))
{
//    echo 'Here 2' . '<br />';

    $id = $_GET['id'];
    $date = $_GET['date'];
    $name = $_GET['name'];
    $score = $_GET['score'];
    $screenshot = $_GET['screenshot'];
}
else if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['score'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $score = $_POST['score'];
}
else{
    echo '<p class="error">Sorry, no high score was specified for removal.</p>';
}


if(isset($_POST['submit'])){
    if($_POST['confirm'] == 'YES'){

        @unlink(GW_UPLOADPATH . $screenshot);

        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        $query = "DELETE FROM guitarwars WHERE id = $id LIMIT 1";
        mysqli_query($dbc, $query);

        mysqli_close($dbc);

        echo '<p>The high score of ' . $score . ' for ' . $name . ' was successfully removed.';

    }
    else{
        echo '<p class="error">The high score was not removed.</p>';
    }
}
else if(isset($id) && isset($name) && isset($date) && isset($score) && isset($screenshot)){
    echo '<p>Are you sure you want to delete the following high score?</p>';

    echo '<p><strong>Name: </strong>' . $name . '<br /><strong>Date: </strong>' . $date .
        '<br /><strong>Score: </strong>' . $score . '</p>';

    echo '<form method="post" action="removescore.php">';

    echo '<input type="radio" name="confirm" value="YES" /> Yes ';
    echo '<input type="radio" name="confirm" value="NO" checked="checked" /> No <br /> ';
    echo '<input type="submit" value="Submit" name="submit" />';

    echo '<input type="hidden" name="id" value="' . $id . '" />';
    echo '<input type="hidden" name="name" value="' . $name . '" />';
    echo '<input type="hidden" name="score" value="' . $score . '" />';

    echo '</form>';
}

echo '<p><a href="admin.php">&lt;&lt; Back to admin page</a></p>';


?>

</body>
</html>