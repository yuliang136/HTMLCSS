<?php

// Start the session
require_once('startsession.php');

// Insert the page header
$page_title = 'Questionnaire';
require_once('header.php');

require_once('appvars.php');
require_once('connectvars.php');

// Make sure the user is logged in before going any further.
if (!isset($_SESSION['user_id'])) {
    echo '<p class="login">Please <a href="login.php">log in</a> to access this page.</p>';
    exit();
}

// Show the navigation menu
require_once('navmenu.php');

//echo '$_SESSION[\'user_id\'] = ' . $_SESSION['user_id'] . '<br />';

// Connect to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// If this user has never answered the questionnaire, insert empty responses into the database
$query = "SELECT * FROM mismatch_response WHERE user_id = '" . $_SESSION['user_id'] . "'";
$data = mysqli_query($dbc, $query);
if (mysqli_num_rows($data) == 0) {
    echo 'mysqli_num_rows($data) == 0' . '<br />';
    $query = "SELECT topic_id FROM mismatch_topic ORDER BY category, topic_id";
    $data = mysqli_query($dbc, $query);
    $topicIDs = array();
    while ($row = mysqli_fetch_array($data)) {
        array_push($topicIDs, $row['topic_id']);
    }

//    foreach($topicIDs as $topicID){
//        echo '$topicID = ' . $topicID  . '<br />';
//    }

    foreach ($topicIDs as $topic_id) {
        $query = "INSERT INTO mismatch_response (user_id, topic_id) VALUES ('" . $_SESSION['user_id'] . "', '$topic_id')";
        mysqli_query($dbc, $query);
    }
}


// If the questionnaire form has been submitted, write the form responses to the database.
if (isset($_POST['submit'])) {
    foreach ($_POST as $response_id => $response) {
        $query = "UPDATE mismatch_response SET response = '$response' WHERE response_id = '$response_id'";
        mysqli_query($dbc, $query);
    }
    echo '<p>Your responses have been saved.</p>';
}

// Grab the response data from the database to generate the form.
$query = "SELECT response_id, topic_id, response FROM mismatch_response WHERE user_id = '" . $_SESSION['user_id'] . "'";
$data = mysqli_query($dbc, $query);
$responses = array();


while ($row = mysqli_fetch_array($data)) {
    $query2 = "SELECT name, category FROM mismatch_topic WHERE topic_id = '" . $row['topic_id'] . "'";
//    echo '$query2 = ' . $query2 . '<br />';

    $data2 = mysqli_query($dbc, $query2);
    if (mysqli_num_rows($data2) == 1) {
        // 通过topic_id查询 只有一个记录.
        $row2 = mysqli_fetch_array($data2);
        $row['topic_name'] = $row2['name'];
        $row['category_name'] = $row2['category'];
        array_push($responses, $row);
    }
//    echo '$row[\'topic_name\'] = ' . $row['topic_name'] . '<br />';
//    echo '$row[\'category_name\'] = ' . $row['category_name'] . '<br />';
//    echo '$row[\'response_id\'] = ' . $row['response_id'] . '<br />';
//    echo '$row[\'topic_id\'] = ' . $row['topic_id'] . '<br />';
}
mysqli_close($dbc);



echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';

echo '<p>How do you feel about each topic?</p>';
$category = $responses[0]['category_name'];
//echo '$category = ' . $category . '<br />';
echo '<fieldset><legend>' . $category . '</legend>';

foreach ($responses as $response) {
    if ($category != $response['category_name']) {
        $category = $response['category_name'];
        echo '</fieldset><fieldset><legend>' . $response['category_name'] . '</legend>';
    }

    echo '<label ' . ($response['response'] == NULL ? 'class="error"' : '') . ' for="' . $response['response_id'] . '">' . $response['topic_name'] . ':</label>';

    echo '<br />';
}


echo '</fieldset>';

echo '</form>';


//$age = array("Bill"=>"63", "Steve"=>"56", "Elon"=>"47");
//foreach($age as $key=>$value){
//    echo 'Key=' . $key . ', Value=' . $value;
//    echo '<br />';
//}


// Insert the page footer
require_once('footer.php');

?>
