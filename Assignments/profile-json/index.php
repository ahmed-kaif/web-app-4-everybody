<?php
require_once "pdo.php";
session_start();

    $stmt = $pdo->query("SELECT * FROM Profile");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);  


?>

<!DOCTYPE html>
<html lang="en">

<?php require_once "head.php"; ?>

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