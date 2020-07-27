<?php
require_once "pdo.php";
session_start();

    $stmt = $pdo->query("SELECT * FROM Profile");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);  


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kaif Ahmed Khan</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

</head>
<body>

<div class="container">
<h1>Kaif Ahmed's Resume Registry</h1>

<?php
        if(isset($_SESSION['success'])){
            echo ('<p style="color: green;">'.$_SESSION['success']."</p>\n");
            unset($_SESSION['success']); // flash success messeage
        } elseif(isset($_SESSION['error'])){
            echo ('<p style="color: red;">'.$_SESSION['error']."</p>\n");
            unset($_SESSION['error']); // flash error messeage
        }

        if( isset($_SESSION['name']) ){
            echo ('<p><a href="logout.php">Logout</a></p>');
        }

?>

<ul>
<?php
foreach ( $rows as $row ) {
    echo "<li>";
    echo(htmlentities($row['first_name']).' '.htmlentities($row['last_name']).' ');
    echo('<a href="view.php?profile_id='.$row['profile_id'].'">View Details</a> / ');
    echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
    echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
    echo("</li>\n");
}
?>
</ul>
<?php
        if( !isset($_SESSION['name']) ){
            echo ('<p><a href="login.php">Please log in</a></p>');
        }
?>
<p><a href="add.php">Add New Entry</a></p>
</div>

    
</body>
</html>