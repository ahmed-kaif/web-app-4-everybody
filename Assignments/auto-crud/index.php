<?php 
    require_once "pdo.php";
    session_start();

    if(isset($_SESSION['name'])){
        $stmt = $pdo->query("SELECT * FROM autos");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    }
     
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kaif Ahmed Khan 97d01cde</title>
</head>
<style>

body{
    font-family: Arial, sans-serif, serif;
}

</style>

<body>

<h1>Welcome to Automobiles Database</h1>

<?php if(!isset($_SESSION['name'])){ ?>
<a href="login.php">Please log in</a>
<?php } elseif($rows){ ?>
<!-- table data -->
<?php
//Flash message
if(isset($_SESSION['success'])){
    echo('<p style="color:green">'.htmlentities($_SESSION['success']).'</p>');
    unset($_SESSION['success']);
}
if(isset($_SESSION['error'])){
    echo ('<p style="color:red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}

?>
<table border="1">
<tr>
<th>Make</th>
<th>Model</th>
<th>Year</th>
<th>Mileage</th>
<th>Action</th>
</tr>
<?php
foreach ( $rows as $row ) {
    echo "<tr><td>";
    echo(htmlentities($row['make']));
    echo("</td><td>");
    echo(htmlentities($row['model']));
    echo("</td><td>");
    echo(htmlentities($row['year']));
    echo("</td><td>");
    echo(htmlentities($row['mileage']));
    echo("</td><td>");
    echo('<a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a> / ');
    echo('<a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a>');
    echo("</td></tr>\n");
}
?>
</table>


<p> <a href="add.php">Add New Entry</a> </p>
<p> <a href="logout.php">Logout</a> </p>

<?php } else {
    echo "No rows found";
    echo('<p> <a href="add.php">Add New Entry</a> </p>'); 
    echo('<p> <a href="logout.php">Logout</a> </p>');
} ?> 
</body>
</html>