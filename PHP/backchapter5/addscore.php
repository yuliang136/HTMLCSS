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

require_once('appvars.php');
require_once('connectvars.php');

// 判断是否是submit提交 还是第一次登入.
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $score = $_POST['score'];
    $screenshot = $_FILES['screenshot']['name'];
    $screenshot_type = $_FILES['screenshot']['type'];
    $screenshot_size = $_FILES['screenshot']['size'];

    // 判断提交的内容不为空. 都不为空时 进行插入操作
    if (!empty($name) && !empty($score) && !empty($screenshot)) {
        if ((($screenshot_type == 'image/gif') || ($screenshot_type == 'image/jpeg') || ($screenshot_type == 'image/pjpeg') || ($screenshot_type == 'image/png'))
            && ($screenshot_size > 0)
            && ($screenshot_size <= GW_MAXFILESIZE)
        )
        {

            if ($_FILES['screenshot']['error'] == 0) {
                // Move the file to the target upload folder.
                $target = GW_UPLOADPATH . $screenshot;

//        echo 'GW_UPLOADPATH = ' . GW_UPLOADPATH .'<br />';
//        echo '$screenshot = ' . $screenshot . '<br />';
//        echo '$target = ' . $target . '<br />';
//        echo $_FILES['screenshot']['tmp_name'] . '<br />';
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
            }
        }
        else{
            echo '<p class="error">The screen shot must be a GIF, JPEG, or PNG image file no ' .
                'greater than' . (GW_MAXFILESIZE / 1024) . ' KB in size.</p>';
        }

        // delete tmp files.
        @unlink($_FILES['screenshot']['tmp_name']);
    }
    else{
        echo '<p class="error">Please enter all of the information to add your high score.</p>';

    }
}

?>



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