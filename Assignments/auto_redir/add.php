<?php
    session_start();
    require_once "pdo.php";

     if (!isset($_SESSION['name'])){
        die("Name parameter missing");}

    if(isset($_POST['logout'])){
     //redirects to home
    header("Location: logout.php");
    return;
    }
    //Data validation
    if ( isset($_POST['year']) && isset($_POST['mileage']) && isset($_POST['make']) ){
    if(!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])){
        $_SESSION['error'] = "Mileage and year must be numeric" ;
        header('Location: add.php');
        return;
    } elseif(strlen($_POST['make']) < 1){
        $_SESSION['error'] = "Make is required";
        header('Location: add.php');
        return;
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
        $_SESSION['success'] = "Record inserted";
        header('Location: view.php');
        return;
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kaif Ahmed Khan - Automobile Tracker</title>
</head>
<body>

<h1>Tracking Autos for <?echo htmlentities($_SESSION['name']);?></h1>

<?php
if(isset($_SESSION['error'])){
    echo ('<p style="color:red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
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

</body>
</html>