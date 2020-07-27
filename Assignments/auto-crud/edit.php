<?php
    require_once "pdo.php";
    session_start();

    if (!isset($_SESSION['name'])){
        die("ACCESS DENIED");}

    if(isset($_POST['cancel'])){
            //redirects to home
            header("Location: index.php");
            return;
        }

    if( isset($_POST['make']) && isset($_POST['model']) 
        && isset($_POST['year']) && isset($_POST['mileage']) ){
            //Data validation
        if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1
        || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1 ) {
            $_SESSION['error'] = "All fields are required";
            header("Location: edit.php?autos_id=".$_POST['autos_id']);
            return;
          } elseif( !is_numeric($_POST['year']) ){
            $_SESSION['error'] = "Year must be numeric" ;
            header('Location: add.php');
            return;
        } elseif(!is_numeric($_POST['mileage'])){
            $_SESSION['error'] = "Mileage must be numeric" ;
            header('Location: add.php');
            return;
        } else {
    $sql = "UPDATE autos SET make = :mk, model = :md, year = :yr, mileage = :mi
            WHERE autos_id = :autos_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':mk' => $_POST['make'],
        ':md' => $_POST['model'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage'],
        ':autos_id' => $_POST['autos_id']));
    $_SESSION['success'] = 'Record edited';
    header( 'Location: index.php' ) ;
    return;

          }

        }
// Guardian: Make sure that user_id is present
if ( ! isset($_GET['autos_id']) ) {
    $_SESSION['error'] = "Missing autos_id";
    header('Location: index.php');
    return;
  }


$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :id");
$stmt->execute(array(":id" => $_GET['autos_id']));
$rows= $stmt->fetch(PDO::FETCH_ASSOC);
if ( $rows === false ) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header( 'Location: index.php' ) ;
    return;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kaif Ahmed Khan - Automobile Updater</title>
</head>
<body>

<?php
 if(isset($_SESSION['error'])){
     echo ( '<p style="color: red">'.$_SESSION['error']."</p>\n");
     unset($_SESSION['error']);
 }
?>

<form method="post">
<p>Make:
<input type="text" name="make" value="<?= htmlentities($rows['make']) ?>" size="40"></p>
<p>Model:
<input type="text" name="model" value="<?= htmlentities($rows['model']) ?>" size="40"></p>
<p>Year:
<input type="text" name="year" value="<?= htmlentities($rows['year']) ?>"></p>
<p>Mileage:
<input type="text" name="mileage" value="<?= htmlentities($rows['mileage']) ?>"></p>

<input type="hidden" name="autos_id" value="<?= htmlentities($rows['autos_id']) ?>">

<p><input type="submit" value="Save"/>
    <input type="submit" name="cancel" value="Cancel"/>
</p>
</form>
    
</body>
</html>