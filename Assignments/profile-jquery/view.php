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

<h1>Profile Information</h1>

<p>First Name: <?php echo( htmlentities($rows["first_name"]) ); ?></p>
<p>Last Name: <?php echo( htmlentities($rows["last_name"]) ); ?></p>
<p>Email: <?php echo( htmlentities($rows["email"]) ); ?></p>
<p>Headline:<br> <?php echo( htmlentities($rows["headline"]) ); ?></p>
<p>Summary:<br> <?php echo( htmlentities($rows["summary"]) ); ?></p>

<?php if(! empty($positions) ){
    echo('<p>Position</p><ul>');

    foreach($positions as $key => $value){
        echo('<li>'.$value['year'].': '.$value['description']."</li>\n");
    }

    echo('</ul>');
}
?>

<p><a href="index.php">Done</a></p>
</div>

    
</body>
</html>