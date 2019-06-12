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


//
//$dbc = mysqli_connect('192.168.1.207', 'root', '123456', 'gwdb');
//
//$query = "SELECT * FROM guitarwars";
//$data = mysqli_query($dbc, $query);
//
//echo '<table>';
//
//while ($row = mysqli_fetch_array($data)) {
//    echo '<tr>';
//
//    echo '<td class="scoreinfo">';
//    echo '<span class="score">' . $row['score'] . '</span><br/>';
//    echo '<strong>Name:</strong>' . $row['name'] . '<br />';
//    echo '<strong>Date:</strong>' . $row['date'] . '</td>';
//
//    if (is_file($row['screenshot']) && filesize($row['screenshot']) > 0) {
//        echo '<td><img src="' . $row['screenshot'] . '" alt="Score image" /></td>';
//    } else {
//        echo '<td><img src="unverified.gif" alt="Unverified score" /></td>';
//    }
//
//    echo '</tr>';
//}
//
//
//echo '</table>';

//define('GW_UPLOADPATH', 'images/');

require_once('appvars.php');
require_once('connectvars.php');

// 判断是否是submit提交 还是第一次登入.
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $score = $_POST['score'];
    $screenshot = $_FILES['screenshot']['name'];

    // 判断提交的内容不为空. 都不为空时 进行插入操作
    if (!empty($name) && !empty($score) && !empty($screenshot)) {

        // Move the file to the target upload folder.
        $target = GW_UPLOADPATH . $screenshot;

        echo 'GW_UPLOADPATH = ' . GW_UPLOADPATH .'<br />';
        echo '$screenshot = ' . $screenshot . '<br />';
        echo '$target = ' . $target . '<br />';
        echo $_FILES['screenshot']['tmp_name'] . '<br />';


        if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $target)) {
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $query = "INSERT INTO guitarwars VALUES (0, NOW(), '$name', '$score', '$screenshot')";
            mysqli_query($dbc, $query) or die('Something wrong!');

            // Confirm success with the user.
            echo '<p>Thanks for adding your new high score!</p>';
            echo '<p><strong>Name:</strong> ' . $name . '<br />';
            echo '<strong>Score:</strong> ' . $score . '<br />';
            echo '<img src="' . GW_UPLOADPATH . $screenshot . '" alt="Score image" /></p>';
            echo '<p><a href="index.php">&lt;&lt; Back to high scores</a></p>';

            // Clear the score data to clear the form
            $name = "";
            $score = "";

            mysqli_close($dbc);
        }


    } else {
        echo '<p class="error">Please enter all of the information to add your high score.</p>';
    }
}
?>


<!--<form enctype="multipart/form-data" method="post" action="--><?php //echo $_SERVER['PHP_SELF']; ?><!--">-->
<!--    <input type="hidden" name="MAX_FILE_SIZE" value="32768"/>-->
<!--    <label for="name">Name:</label>-->
<!--    <input type="text" id="name" name="name" value="--><?php //if (!empty($name)) echo $name; ?><!--"/>-->
<!--    <br/>-->
<!--    <label for="score">Score:</label>-->
<!--    <input type="text" id="score" name="score" value="--><?php //if (!empty($score)) echo $score; ?><!--"/>-->
<!--    <br/>-->
<!--    <label for="screenshot">Screen shot:</label>-->
<!--    <input type="file" id="screenshot" name="screenshot"/>-->
<!--    <hr/>-->
<!--    <input type="submit" value="Add" name="submit"/>-->
<!--</form>-->

<hr/>
<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="32768"/>
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php if (!empty($name)) echo $name; ?>"/><br/>
    <label for="score">Score:</label>
    <input type="text" id="score" name="score" value="<?php if (!empty($score)) echo $score; ?>"/><br/>
    <label for="screenshot">Screen shot:</label>
    <input type="file" id="screenshot" name="screenshot"/>
    <hr/>
    <input type="submit" value="Add" name="submit"/>
</form>

</body>
</html>