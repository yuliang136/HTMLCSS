<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Risky Jobs - Search</title>
    <link rel="stylesheet" ref="style.css"/>
</head>
<body>

<img src="riskyjobs_title.gif" alt="Risky Jobs"/>
<img src="riskyjobs_fireman.jpg" alt="Risky Jobs" style="float:right"/>
<h3>Risky Jobs - Search Results</h3>

<?php
//echo '$user_search = ' . $user_search . '<br />';


function build_query($user_search, $sort){
    $search_query = "SELECT * FROM riskyjobs";
    $clean_search = str_replace(',', ' ', $user_search);
    $search_words = explode(' ', $clean_search);
    $final_search_words = array();

    if (count($search_words) > 0) {
        foreach ($search_words as $word) {
            if (!empty($word)) {
                $final_search_words[] = $word;
            }
        }
    }

    $where_list = array();
    if (count($final_search_words) > 0) {
        foreach ($final_search_words as $word) {
            $where_list[] = "description LIKE '%$word%'";
        }
    }

    $where_clause = implode(' OR ', $where_list);

    if (!empty($where_clause)) {
        $search_query .= " WHERE $where_clause";
    }

    // Sort the search query using the sort setting
    switch ($sort) {
// Ascending by job title
        case 1:
            $search_query .= " ORDER BY title";
            break;
// Descending by job title
        case 2:
            $search_query .= " ORDER BY title DESC";
            break;
// Ascending by state
        case 3:
            $search_query .= " ORDER BY state";
            break;
// Descending by state
        case 4:
            $search_query .= " ORDER BY state DESC";
            break;
// Ascending by date posted (oldest first)
        case 5:
            $search_query .= " ORDER BY date_posted";
            break;
// Descending by date posted (newest first)
        case 6:
            $search_query .= " ORDER BY date_posted DESC";
            break;
        default:
// No sort setting provided, so don't sort the query
    }

    return $search_query;
}

function generate_page_links($user_search, $sort, $cur_page, $num_pages){

    $page_links = '';

//    $page_links = 'Hello';

    if($cur_page > 1){
        $page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page - 1) . '"><-</a> ';
    }
    else{
        $page_links .= '<- ';
    }

    for($i = 1; $i <= $num_pages; $i++){
        if($cur_page == $i){
            $page_links .= ' ' . $i;
        }
        else{
            $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . ($i) . '"> ' . $i . '</a> ';
        }
    }

    // If this page is not the last page, generate the "Next" link.
    if($cur_page < $num_pages){
        $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page + 1) . '">' . '->' . '</a> ';
    }
    else{
        $page_links .= ' ->';
    }

    return $page_links;
}

function generate_sort_links($user_search, $sort)
{

//    echo 'Enter generate_sort_links' . '<br />';
//
//    echo 'Enter $sort = ' . $sort . '<br />';

    $sort_links = '';

    switch ($sort)
    {
        case 1:
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2">Job Title</a></td><td>Description</td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">State</a></td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Date Posted</a></td>';
            break;
        case 3:
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Job Title</a></td><td>Description</td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=4">State</a></td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Date Posted</a></td>';
            break;
        case 5:
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Job Title</a></td><td>Description</td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">State</a></td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=6">Date Posted</a></td>';
            break;

        default:
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Job Title</a></td><td>Description</td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">State</a></td>';
            $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Date Posted</a></td>';

            break;
    }


//    echo 'Enter $sort_links = ' . $sort_links . '<br />';
    return $sort_links;
}


// Start generating the table of results
echo '<table border="0" cellpadding="2">';

//// Generate the search result headings
//echo '<tr class="heading">';
//echo '<td>Job Title</td><td>Description</td><td>State</td><td>Date Posted</td>';
//echo '</tr>';

// Connect to the database
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//// Query to get the results
//$query = "SELECT * FROM riskyjobs WHERE title = '$user_search'";

$user_search = $_GET['usersearch'];

$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
$results_per_page = 5;
$skip = (($cur_page - 1) * $results_per_page);


$sort = isset($_GET['sort']) ? $_GET['sort'] : 0;

//echo '$sort = ' . $sort . '<br />';
//echo '$user_search = ' . $user_search . '<br />';

echo '<tr class="heading">';
echo $sort_links = generate_sort_links($user_search, $sort);
echo '</tr>';


$search_query = build_query($user_search, $sort);






//echo '$search_query = ' . $search_query . '<br />';


$result = mysqli_query($dbc, $search_query);

$total = mysqli_num_rows($result);

//echo '$total = ' . $total . '<br />';



$num_pages = ceil($total / $results_per_page);

//echo '$num_pages = ' . $num_pages . '<br />';

$search_query = $search_query . " LIMIT $skip, $results_per_page";
$result = mysqli_query($dbc, $search_query);

while ($row = mysqli_fetch_array($result)) {
    echo '<tr class="results">';
    echo '<td valign="top" width="20%">' . $row['title'] . '</td>';
    echo '<td valign="top" width="50%">' . substr($row['description'],0,100) . '...</td>';
    echo '<td valign="top" width="10%">' . $row['state'] . '</td>';
    echo '<td valign="top" width="20%">' . substr($row['date_posted'],0,10) . '</td>';
    echo '</tr>';
}

//substr($row['date_posted'],0,10)

echo '</table>';

if($num_pages > 1){
    echo generate_page_links($user_search, $sort, $cur_page, $num_pages);
}

mysqli_close($dbc);


// Test.
//$clean_search = str_replace('thousands', 'hundreds',
//    'Make thousands of dollars your very first month. Apply now!');
//echo '$clean_search = ' . $clean_search . '<br >';

?>

</body>
</html>