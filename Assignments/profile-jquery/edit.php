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
               header('Location: edit.php?profile_id='.$_REQUEST['profile_id']);
               return;
        } elseif(strpos($_POST['email'],'@') === FALSE){
            $_SESSION['error'] = "Email address must contain @";
            header('Location: edit.php?profile_id='.$_REQUEST['profile_id']);
            return;
       } 
       else{

       //validate the position
       $msg = validatePos();
       if(is_string($msg)){
           $_SESSION['error'] = $msg;
           header('Location: edit.php?profile_id='.$_REQUEST['profile_id']);
           return;
       }
       
    
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
            ':pid' => $_REQUEST['profile_id']));

        // Clear out the old position entries
$stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
$stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
        //insert data into position table
$rank = 1;
for($i=1; $i<=9; $i++) {
  if ( ! isset($_POST['year'.$i]) ) continue;
  if ( ! isset($_POST['desc'.$i]) ) continue;

  $year = $_POST['year'.$i];
  $desc = $_POST['desc'.$i];
  $stmt = $pdo->prepare('INSERT INTO Position
    (profile_id, rank, year, description)
    VALUES ( :pid, :rank, :year, :desc)');

  $stmt->execute(array(
  ':pid' => $_REQUEST['profile_id'],
  ':rank' => $rank,
  ':year' => $year,
  ':desc' => $desc)
  );

  $rank++;

}

$_SESSION['success'] = "Profile updated";
header('Location: index.php');
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
$stmt->execute(array(':pid' => $_REQUEST['profile_id'],
                        ':uid' => $_SESSION['user_id']) ); 

$rows= $stmt->fetch(PDO::FETCH_ASSOC);
if ( $rows === false ) {
    $_SESSION['error'] = 'Could not load profile';
    header( 'Location: index.php' ) ;
    return;
}

 $positions = loadPos($pdo, $_REQUEST['profile_id']);

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
<p>Position: <input type="submit" id="addPos" value="+"></p>

<div id="position_fields">
<?php if(! empty($positions) ){
    
        foreach($positions as $key => $value){

            echo('<div id="position'.htmlentities($value['rank']).'">');
            echo('<p><input type="text" name="year'.htmlentities($value['rank']).'" value="'.htmlentities($value['year']).'">');
            echo('<input type="button" value="-" onclick="$(\'#position'.htmlentities($value['rank']).'\').remove(); return false;"></p>');
            echo('<textarea name="desc'.htmlentities($value['rank']).'" rows="8" cols="80">'.htmlentities($value['description']).'</textarea></div>');
        }

} ?>
</div>
<p><input type="submit" value="Save"/>
    <input type="submit" name="cancel" value="Cancel">
</p>
</form>

</div>



<script>

countPos = <?= count($positions) ?>;

$(document).ready(function(){
    window.console && console.log("Document ready called");
    $('#addPos').click(function(event){
        event.preventDefault();
        if( countPos >= 9){
            alert('Maximum of nine position entries exceeded');
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#position_fields').append(
            '<div id="position'+countPos+'">\
            <p>Year: <input type="text" name="year'+countPos+'" value="">\
            <input type="button" value="-"\
            onclick="$(\'#position'+countPos+'\').remove(); return false;"></p>\
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>' );
    });
});




</script>

    
</body>
</html>