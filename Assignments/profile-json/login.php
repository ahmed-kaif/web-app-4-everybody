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

<?php require_once "head.php"; ?>

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