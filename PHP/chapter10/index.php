<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

<?php
    echo 'Test' . '<br />';

    $matStr = '/^\d{3}-\d{2}-\d{4}$/';
    $strTest = '555-02-9983';

    if(preg_match($matStr, $strTest))
    {
        echo 'Valid social security number.' . '<br />';
    }
    else
    {
        echo 'That social security number is invalid!' . '<br />';
    }

?>

</body>
</html>