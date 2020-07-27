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
                   return;
            } elseif(strpos($_POST['email'],'@') === FALSE){
                $_SESSION['error'] = "Email address must contain @";
                header('Location: add.php');
                return;
           } else{
                       //data is valid now insert
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
        //Position data validation

        $msg = validatePos();
        if(is_string($msg)){
            $_SESSION['error']= $msg;
            header('Location: add.php');
            return;
        }

        //Education Data validation
        $msg = validateEdu();
        if(is_string($msg)){
            $_SESSION['error']= $msg;
            header('Location: add.php');
            return;
        }
        



        
       
       // checks if the profile is added to the DB and sends a success msg
       if($pdo->lastInsertID() > 0){

        $profile_id = $pdo->lastInsertID();
    
        insertPos($pdo, $profile_id);
    
        insertEdu($pdo, $profile_id);
        
        $_SESSION['success'] = "Profile Added";
        header('Location: index.php');
        return;
    }





?>

<!DOCTYPE html>
<html lang="en">

<?php require_once "head.php"; ?>

<body>

<div class="container">

<h1>Add a New Profile</h1>

<?php

if(isset($_SESSION['error'])){
    echo ('<p style="color: red;">'.$_SESSION['error']."</p>\n");
    unset($_SESSION['error']); // flash messeage
}

?>

<form method="post" action="add.php">
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

<p>Education: <input type="submit" id="addEdu" value="+"></p>
<div id="edu_fields"></div>

<p>Position: <input type="submit" id="addPos" value="+"></p>
<div id="position_fields"></div>

<p><input type="submit" value="Add"/>
    <input type="submit" name="cancel" value="Cancel">
</p>
</form>


</div>

<script>

countPos = 0;
countEdu = 0;


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