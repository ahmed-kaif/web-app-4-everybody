<?php
    require_once "pdo.php";
    session_start();

    if(isset($_POST['cancel'])){
        //redirects to home
        header("Location: index.php");
        return;
    }


    if( isset($_POST['delete']) && isset($_POST['autos_id']) ) {
        $sql = "DELETE FROM autos WHERE autos_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':id' => $_POST['autos_id']));
        $_SESSION['success'] = 'Record deleted';
        header( 'Location: index.php' ) ;
        return;
    }

// Guardian: Make sure that autos_id is present
if ( ! isset($_GET['autos_id']) ) {
    $_SESSION['error'] = "Missing autos_id";
    header('Location: index.php');
    return;
  }
  $stmt = $pdo->prepare("SELECT make, model, autos_id FROM autos where autos_id = :id");
  $stmt->execute(array(":id" => $_GET['autos_id']));
  $rows = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <title>Kaif Ahmed Khan - DELETE entry</title>
</head>
<body>
<p>Confirm: Deleting <?php echo( htmlentities($rows['make'])." ".htmlentities($rows['model']) ); ?></p>

<form method="post">

<input type="submit" name="delete" value="Delete">
<input type="submit" name="cancel" value="Cancel">
<input type="hidden" name="autos_id" value="<?= $rows['autos_id'] ?>">

</form>
    
</body>
</html>