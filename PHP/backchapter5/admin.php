<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guitar Wars - High Scores Administration</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>

<?php

require_once('appvars.php');
require_once('connectvars.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$query = "SELECT * FROM guitarwars ORDER BY score DESC, date ASC";
$data = mysqli_query($dbc, $query);

echo '<table>';

while ($row = mysqli_fetch_array($data)) {

    echo '<tr class="scorerow">';
    echo '<td><strong>' . $row['name'] . '</strong></td>';
    echo '<td>' . $row['date'] . '</td>';
    echo '<td>' . $row['score'] . '</td>';
    echo '<td><a href="removescore.php?id=' . $row['id'] . '&amp;date=' . $row['date'] .
        '&amp;name=' . $row['name'] . '&amp;score=' . $row['score'] . '&amp;screenshot=' .
        $row['screenshot'] . '">Remove</a></td>';
    echo '</tr>';

}

echo '</table>';

mysqli_close($dbc);

?>

</body>
</html>