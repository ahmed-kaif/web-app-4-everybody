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
        $msg = validateProfile();
        if(is_array($msg)){
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
                   //validate the position
       $msg = validatePos();
       if(is_string($msg)){
           $_SESSION['error'] = $msg;
           header('Location: edit.php?profile_id='.$_REQUEST['profile_id']);
           return;
       }

        // Clear out the old position entries
        $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
        $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
  
        insertPos( $pdo, $_REQUEST['profile_id'] );

         //validate education fields
       $msg = validateEdu();
       if(is_string($msg)){
        $_SESSION['error'] = $msg;
        header('Location: edit.php?profile_id='.$_REQUEST['profile_id']);
        return;
        }
  
        $stmt = $pdo->prepare('DELETE FROM Education WHERE profile_id=:pid');
        $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
  
          //Data is validated, insert data into position table and Education table
         insertEdu( $pdo, $_REQUEST['profile_id'] );
  
  
  
        $_SESSION['success'] = "Profile updated";
        header('Location: index.php');
        return;
    
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
 $educations = loadEdu($pdo, $_REQUEST['profile_id']);

?>

<!DOCTYPE html>
<html lang="en">

<?php require_once "head.php"; ?>

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
<p>Education: <input type="submit" id="addEdu" value="+"></p>
<div id="edu_fields">

<?php 

if(! empty($educations) ){
   $countEdu = 0; 
    foreach($educations as $value){
        $countEdu++;
        echo('<div id="edu'.$countEdu.'">');
        echo('<p>Year: <input type="text" name="year'.$countEdu.'" value="'.htmlentities($value['year']).'">');
        echo('<input type="button" value="-" onclick="$(\'#edu'.$countEdu.'\').remove(); return false;"></p>');
        echo('<p>School: <input type="text" size="80" name="edu_school'.$countEdu.'" class="school ui-autocomplete-input" value="'.$value['name'].'" autocomplete="off"></p>'.'</div>');
    }

} ?>

</div>

<p>Position: <input type="submit" id="addPos" value="+"></p>

<div id="position_fields">
<?php if(! empty($positions) ){
    
        foreach($positions as $key => $value){

            echo('<div id="position'.htmlentities($value['rank']).'">');
            echo('<p>Year: <input type="text" name="year'.htmlentities($value['rank']).'" value="'.htmlentities($value['year']).'">');
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
countEdu = <?= count($educations) ?>;

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

    $('#addEdu').click(function(event){
        event.preventDefault();
        if( countEdu >= 9){
            alert('Maximum of nine education entries exceeded');
            return;
        }
        countEdu++;
        window.console && console.log("Adding education "+countEdu);
        $('#edu_fields').append(
            '<div id="edu'+countEdu+'">\
            <p>Year: <input type="text" name="edu_year'+countEdu+'" value="">\
            <input type="button" value="-"\
            onclick="$(\'#edu'+countEdu+'\').remove(); return false;"></p>\
            <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school ui-autocomplete-input" value="" autocomplete="off"></p>\
            </div>' );

            $('.school').autocomplete({
            source: "school.php"
        });

    });
});




</script>

    
</body>
</html>