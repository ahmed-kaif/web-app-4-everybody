<?php

if(isset($_POST['cancel'])){
    //redirects to home
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';
$failure = FALSE;

if ( isset($_POST['who']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1 ) {
        $failure = "Email and password are required";
    } elseif(strpos($_POST['who'],'@') === FALSE){
        $failure = "Email must have an at-sign (@)";
    } else {
        $check = hash('md5', $salt.$_POST['pass']);
        if ( $check == $stored_hash ) {
            // Redirect the browser to autos.php
            header("Location: autos.php?name=".urlencode($_POST['who']));
            error_log("Login success ".$_POST['who']);
            return;
        } else {
            $failure = "Incorrect password";
            error_log("Login fail ".$_POST['who']." $check");
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

if($failure !== FALSE){
    echo ('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}

?>


<form method="POST">

<p><label for="who">Email:</label>
<input type="text" name="who" size="40" value=""></p>

<p> <label for="pass">Password:</label>
<input type="password" name="pass" size="40" value="">
</p>

<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">

</form>

<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the three character name of a web programming Language
(all lower case) followed by 123. -->
</p>
    
</body>
</html>