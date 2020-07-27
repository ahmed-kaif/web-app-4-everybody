<?php
session_start();

if(isset($_POST['cancel'])){
    //redirects to home
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';

if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    unset($_SESSION['name']); //logout current user
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "User name and password are required";
        header("Location: login.php");
        return;
    } elseif(strpos($_POST['email'],'@') === FALSE){
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;
    } else {
        $check = hash('md5', $salt.$_POST['pass']);
        if ( $check == $stored_hash ) {
            error_log("Login success ".$_POST['email']);
            // Redirect the browser to autos.php
            $_SESSION['name'] = $_POST['email'];
            header("Location: index.php");
            return;
        } else {
            error_log("Login fail ".$_POST['email']." $check");
            $_SESSION['error'] = "Incorrect password";
            header("Location: login.php");
            return;
            
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kaif Ahmed Khan 97d01cde</title>
</head>
<body>

<h1>Please Log In</h1>

<?php 

if(isset($_SESSION['error'])){
    echo ('<p style="color: red;">'.$_SESSION['error']."</p>\n");
    unset($_SESSION['error']); // flash messeage
}

?>


<form method="POST">

User Name <input type="text" name="email"><br/>
Password <input type="text" name="pass"><br/>

<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">

</form>
    
</body>
</html>