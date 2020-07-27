<?php
require_once "pdo.php";
require_once "function.php";
session_start();

$salt = 'XyZzy12*_';

if(isset($_POST['cancel'])){
    //redirects to home
    header("Location: index.php");
    return;
}

//login data validation

 if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    unset($_SESSION['name']); //logout current user
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "Both fields must be filled out";
        header("Location: login.php");
        return;

    } elseif(strpos($_POST['email'],'@') === FALSE){
        $_SESSION['error'] = "Invalid email address";
        header("Location: login.php");
        return;

    } else {
        $check = hash('md5', $salt.$_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
        $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ( $row !== false ) {

            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            // Redirect the browser to index.php
            header("Location: index.php");
            return;

        } else {
            error_log("Login fail ".$_POST['email']." $check");
            $_SESSION['error'] = "Incorrect email or password";
            header("Location: login.php");
            return;
            
        }
    }

    return false;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaif Ahmed Khan</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

</head>
<body>

<div class="container">
<h1>Please Log In</h1>

<?php

if(isset($_SESSION['error'])){
    echo ('<p style="color: red;">'.$_SESSION['error']."</p>\n");
    unset($_SESSION['error']); // flash messeage
}


?>

    <form action="login.php" method="POST">
        <p>Email <input type="text" name="email" id="id_1722"></p>

        <p>Password <input type="password" name="pass" id="id_1723"></p>

       <p><input type="submit" onclick="return doValidate();" value="Log In">
       <input type="submit" name="cancel" value="Cancel"></p> 
    
    </form></div>



<script>
function doValidate() {

console.log('Validating...');

try {
email = document.getElementById('id_1722').value;
pw = document.getElementById('id_1723').value;

console.log("Validating pw="+pw);
console.log("Validating email="+email);

if (pw == null || pw == "" || email == null || email == "") {

alert("Both fields must be filled out");

return false;

} else if( email.includes("@") === false ){
    alert("Invalid email address");
    return false;
}

return true;

} catch(e) {

return false;

}

return false;
}
</script>
</body>
</html>