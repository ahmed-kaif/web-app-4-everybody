<?php
    require_once "pdo.php";
    require_once "function.php";
    session_start();

    isloggedin();

    if(isset($_POST['cancel'])){
        //redirects to home
        header("Location: index.php");
        return;
    }

    if( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
        $sql = "DELETE FROM Profile WHERE profile_id = :pid AND user_id = :uid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':pid' => $_GET['profile_id'],
        ':uid' => $_SESSION['user_id']));
        $_SESSION['success'] = 'Profile deleted';
        header( 'Location: index.php' ) ;
        return;
    }

    //Guardian: Checks GET parameter
    if ( !isset($_GET['profile_id']) ) {
        $_SESSION['error'] = "Missing profile_id";
        header('Location: index.php');
        return;
      }

    $stmt = $pdo->prepare('SELECT profile_id, first_name, last_name FROM Profile WHERE profile_id = :pid AND user_id =:uid');
    $stmt->execute(array(':pid' => $_GET['profile_id'],
      ':uid' => $_SESSION['user_id']) ); 

    $rows= $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $rows === false ) {
    $_SESSION['error'] = 'Could not load profile';
    header( 'Location: index.php' ) ;
    return;
    } 

?>

<!DOCTYPE html>
<html lang="en">
<?php require_once "head.php"; ?>

<body>
<div class="container">

<h1>Deleteing Profile</h1>
<form method="post">
<p>First Name:
<?= htmlentities($rows['first_name']) ?></p>
<p>Last Name:
<?= htmlentities($rows['last_name']) ?></p>
<input type="hidden" name="profile_id" value="<?= $rows['profile_id'] ?>"/>
<input type="submit" name="delete" value="Delete">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

</div>

</body>
</html>