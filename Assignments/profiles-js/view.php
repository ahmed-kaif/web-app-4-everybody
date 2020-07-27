<?php
    require_once "pdo.php";
    session_start();


        //Guardian: Checks GET parameter
        if ( ! isset($_GET['profile_id']) ) {
            $_SESSION['error'] = "Missing profile_id";
            header('Location: index.php');
            return;
          }

    $stmt = $pdo->prepare('SELECT first_name, last_name, email, headline, summary FROM Profile 
                            WHERE profile_id = :pid');
    $stmt->execute(array(
        ':pid' => $_GET['profile_id'] ) );
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
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

<h1>Profile Information</h1>

<p>First Name: <?php echo( htmlentities($rows["first_name"]) ); ?></p>
<p>Last Name: <?php echo( htmlentities($rows["last_name"]) ); ?></p>
<p>Email: <?php echo( htmlentities($rows["email"]) ); ?></p>
<p>Headline:<br> <?php echo( htmlentities($rows["headline"]) ); ?></p>
<p>Summary:<br> <?php echo( htmlentities($rows["summary"]) ); ?></p>

<p><a href="index.php">Done</a></p>
    
</body>
</html>