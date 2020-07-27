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
    //Data validation
    if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) ){
        if(strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 
               || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ){
               $_SESSION['error'] = "All fields are required";
               header('Location: edit.php?profile_id='.$_GET['profile_id']);
               return FALSE;
        } elseif(strpos($_POST['email'],'@') === FALSE){
            $_SESSION['error'] = "Email address must contain @";
            header('Location: edit.php?profile_id='.$_GET['profile_id']);
            return FALSE;
       } else {
            $sql = "UPDATE Profile SET user_id = :uid, first_name = :fn, last_name = :ln, email = :em, 
                    headline = :he, summary = :su WHERE profile_id = :pid";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':uid' => $_SESSION['user_id'],
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary'],
                ':pid' => $_GET['profile_id']));
            
                $_SESSION['success'] = 'Profile updated';
                 header( 'Location: index.php' ) ;
                 return;
            } 
       } 
// Guardian: Make sure that user_id is present
if ( ! isset($_GET['profile_id']) ) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
  }


$stmt = $pdo->prepare("SELECT first_name, last_name, email, headline, summary FROM Profile 
                        WHERE profile_id = :pid AND user_id = :uid");
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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaif Ahmed Khan</title>
</head>
<body>

<h1>Editing Profile for <?= htmlentities($_SESSION['name'])?></h1>

<?php

if(isset($_SESSION['error'])){
    echo ('<p style="color: red;">'.$_SESSION['error']."</p>\n");
    unset($_SESSION['error']); // flash messeage
}

?>

<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60" value="<?= $rows['first_name'] ?>"></p>
<p>Last Name:
<input type="text" name="last_name" size="60" value="<?= $rows['last_name'] ?>"></p>
<p>Email:
<input type="text" name="email" size="30" value="<?= $rows['email'] ?>"></p>
<p>Headline:<br>
<input type="text" name="headline" size="80" value="<?= $rows['headline'] ?>"></p>
<p>Summary:<br>
<textarea name="summary" rows="8" cols="80"><?= $rows['summary'] ?></textarea>
<p><input type="submit" value="Save"/>
    <input type="submit" name="cancel" value="Cancel">
</p>
</form>

    
</body>
</html>