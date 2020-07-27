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

        //data validation
        if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) ){
            if(strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 
                   || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ){
                   $_SESSION['error'] = "All fields are required";
                   header('Location: add.php');
                   return FALSE;
            } elseif(strpos($_POST['email'],'@') === FALSE){
                $_SESSION['error'] = "Email address must contain @";
                header('Location: add.php');
                return FALSE;
           } else {
            $stmt = $pdo->prepare('INSERT INTO Profile
            (user_id, first_name, last_name, email, headline, summary)
                VALUES ( :uid, :fn, :ln, :em, :he, :su)');

            $stmt->execute(array(
                ':uid' => $_SESSION['user_id'],
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary']) );
           } 
           } 
           // checks if the profile is added to the DB and sends a success msg

           if($pdo->lastInsertID() > 0){
            $_SESSION['success'] = "Profile Added";
            header('Location: index.php');
            return;
        }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Add a New Profile</h1>

<?php

if(isset($_SESSION['error'])){
    echo ('<p style="color: red;">'.$_SESSION['error']."</p>\n");
    unset($_SESSION['error']); // flash messeage
}

?>

<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60"></p>
<p>Last Name:
<input type="text" name="last_name" size="60"></p>
<p>Email:
<input type="text" name="email" size="30"></p>
<p>Headline:<br>
<input type="text" name="headline" size="80"></p>
<p>Summary:<br>
<textarea name="summary" rows="8" cols="80"></textarea>
<p><input type="submit" value="Add"/>
    <input type="submit" name="cancel" value="Cancel">
</p>
</form>


</body>
</html>