<?php
    require_once "pdo.php";
    $err_msg = FALSE;
    $sucess = FALSE;

     if (!isset($_GET['name']) || strlen($_GET['name']) < 1){
        die("Name parameter missing");}

    if(isset($_POST['logout'])){
     //redirects to home
    header("Location: index.php");
    return;
    }
    //Data validation
    if ( isset($_POST['year']) && isset($_POST['mileage']) && isset($_POST['make']) ){
    if(!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])){
        $err_msg = "Mileage and year must be numeric" ;
    } elseif(strlen($_POST['make']) < 1){
        $err_msg = "Make is required";
    } else {
        $stmt = $pdo->prepare('INSERT INTO autos(make, year, mileage) VALUES ( :mk, :yr, :mi)');
        $stmt->execute(array(
                        ':mk' => $_POST['make'],
                        ':yr' => $_POST['year'],
                        ':mi' => $_POST['mileage'])
                     ); 
    }
    }
    if($pdo->lastInsertID() > 0){
        $sucess = "Record inserted";
    }

$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kaif Ahmed Khan - Automobile Tracker</title>
</head>
<body>

<h1>Tracking Autos for <?echo htmlentities($_GET['name']);?></h1>

<?php
if($err_msg !== FALSE){
    echo ('<p style="color:red;">'.htmlentities($err_msg)."</p>\n");
} elseif($sucess !== FALSE){
    echo ('<p style="color:green;">'.htmlentities($sucess)."</p>\n");
}
?>

<form method="post">
<p>Make:
<input type="text" name="make" size="40"></p>
<p>Year:
<input type="text" name="year"></p>
<p>Mileage:
<input type="text" name="mileage"></p>
<p><input type="submit" value="Add"/>
    <input type="submit" name="logout" value="Log Out"/>
</p>
</form>
<h2>Automobiles</h2>
<table border="1">
<?php
foreach ( $rows as $row ) {
    echo "<tr><td>";
    echo(htmlentities($row['make']));
    echo("</td><td>");
    echo(htmlentities($row['year']));
    echo("</td><td>");
    echo(htmlentities($row['mileage']));
    echo("</td></tr>\n");
}
?>
</table>
    
</body>
</html>
