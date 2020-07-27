<?php
    require_once "pdo.php";
    require_once "function.php";
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
    
    $positions = loadPos($pdo, $_GET['profile_id']); 

    $educations = loadEdu($pdo, $_GET['profile_id']);


?>

<!DOCTYPE html>
<html lang="en">

<?php require_once "head.php"; ?>

<body>

<div class="container">

<h1>Profile Information</h1>

<p>First Name: <?php echo( htmlentities($rows["first_name"]) ); ?></p>
<p>Last Name: <?php echo( htmlentities($rows["last_name"]) ); ?></p>
<p>Email: <?php echo( htmlentities($rows["email"]) ); ?></p>
<p>Headline:<br> <?php echo( htmlentities($rows["headline"]) ); ?></p>
<p>Summary:<br> <?php echo( htmlentities($rows["summary"]) ); ?></p>

<?php 

if(! empty($educations) ){
    echo('<p>Education</p><ul>');

    foreach($educations as  $value){
        echo('<li>'.$value['year'].': '.$value['name']."</li>\n");
    }

    echo('</ul>');
}

if(! empty($positions) ){
    echo('<p>Position</p><ul>');

    foreach($positions as  $value){
        echo('<li>'.$value['year'].': '.$value['description']."</li>\n");
    }

    echo('</ul>');
}
?>

<p><a href="index.php">Done</a></p>
</div>

    
</body>
</html>