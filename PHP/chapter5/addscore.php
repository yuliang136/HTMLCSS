<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guitar Wars - Add Your High Score</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<h2>Guitar Wars - Add Your High Score</h2>


<?php

//echo empty($name);
//if(isset($_POST['submit'])){
//
//    $name = $_POST['name'];
//    $score = $_POST['score'];
//}

$dbc = mysqli_connect('192.168.1.207', 'root', '123456', 'gwdb');

$query = "SELECT * FROM guitarwars";
$data = mysqli_query($dbc, $query);

echo '<table>';

while ($row = mysqli_fetch_array($data)) {
    echo '<tr>';

    echo '<td class="scoreinfo">';
    echo '<span class="score">' . $row['score'] . '</span><br/>';
    echo '<strong>Name:</strong>' . $row['name'] . '<br />';
    echo '<strong>Date:</strong>' . $row['date'] . '</td>';

    if (is_file($row['screenshot']) && filesize($row['screenshot']) > 0) {
        echo '<td><img src="' . $row['screenshot'] . '" alt="Score image" /></td>';
    } else {
        echo '<td><img src="unverified.gif" alt="Unverified score" /></td>';
    }

    echo '</tr>';
}


echo '</table>';

mysqli_close($dbc);

?>


<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="32768"/>
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php if (!empty($name)) echo $name; ?>"/>
    <br/>
    <label for="score">Score:</label>
    <input type="text" id="score" name="score" value="<?php if (!empty($score)) echo $score; ?>"/>
    <br/>
    <label for="screenshot">Screen shot:</label>
    <input type="file" id="screenshot" name="screenshot"/>
    <hr/>
    <input type="submit" value="Add" name="submit"/>
</form>


</body>
</html>