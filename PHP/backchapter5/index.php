<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guitar Wars - High Scores</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>

<h2>Guitar Wars - High Scores</h2>
<p>Welcome, Guitar Warrior, do you have what it takes to crack the high score list? If so, just <a href="addscore.php">add your own score</a>.</p>
<hr />

<?php

require_once('appvars.php');
require_once('connectvars.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$query = "SELECT * FROM guitarwars";
$data = mysqli_query($dbc, $query);

echo '<table>';

$i = 0;
while($row = mysqli_fetch_array($data)){

    if($i == 0)
    {
        echo '<tr><td colspan="2" class="topscoreheader">Top Score: ' . $row['score'] . '</td></tr>';
    }

    echo '<tr>';
    echo '<td class="scoreinfo">';
    echo '<span class="score">' . $row['score'] . '</span><br />';
    echo '<strong>Name:</strong>' . $row['name'] . '<br />';
    echo '<strong>Date:</strong>' . $row['date'] . '</td>';
    if(is_file(GW_UPLOADPATH . $row['screenshot']) && filesize(GW_UPLOADPATH . $row['screenshot']) > 0){
        echo '<td><img src="' . GW_UPLOADPATH . $row['screenshot'] .'" alt="Score Image" /></td>';
    }
    else{
        echo '<td><img src="' . GW_UPLOADPATH . "unverified.gif" . '" alt="Unverified score" /></td>';
    }

    $i++;

    echo '</tr>';
}

echo '</table>';

mysqli_close($dbc);

?>



</body>
</html>