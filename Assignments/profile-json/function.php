<?php

require_once "pdo.php";


//checks user login

function isloggedin()
{
   if(!isset($_SESSION['name'])){
      die('ACCESS DENIED');
   }
}

function validateProfile() {

   if(strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 
               || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ){
               return "All fields are required";}
   if(strpos($_POST['email'],'@') === FALSE){
                 return "Email address must contain @";
               }

         return TRUE;
}

// valdiation for position

function validatePos() {

      for($i=1; $i <=9 ;$i++){

         if(! isset($_POST['year'.$i]) ) continue;
         if(! isset($_POST['desc'.$i]) ) continue;

         $year= $_POST['year'.$i];
         $desc= $_POST['desc'.$i];

         if(strlen($year)==0 || strlen($desc)==0) return "All fields are required";

         if(! is_numeric($year) ) return "Year must be numeric";
      }

      return TRUE;
}

//validation for Education
function validateEdu() {

   for($i=1; $i <=9 ;$i++){

      if(! isset($_POST['edu_year'.$i]) ) continue;
      if(! isset($_POST['edu_school'.$i]) ) continue;

      $year= $_POST['edu_year'.$i];
      $school= $_POST['edu_school'.$i];

      if(strlen($year)==0 || strlen($school)==0) return "All fields are required";

      if(! is_numeric($year) ) return "Year must be numeric";
   }

   return TRUE;
}

// function to insert data in Position table
function insertPos($pdo, $profile_id) {

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
  ':pid' => $profile_id,
  ':rank' => $rank,
  ':year' => $year,
  ':desc' => $desc)
  );

  $rank++;

}
}

//function to insert data into the institution table
function insertEdu($pdo, $profile_id) {

   $rank = 1;
   for($i=1; $i<=9; $i++) {
     if ( ! isset($_POST['edu_year'.$i]) ) continue;
     if ( ! isset($_POST['edu_school'.$i]) ) continue;
      $year = $_POST['edu_year'.$i];
      $school = $_POST['edu_school'.$i];

      //Look up the school if it is there
      $institution_id = FALSE;
      $stmt = $pdo->prepare('SELECT institution_id FROM Institution WHERE name = :name');
      $stmt->execute(array(':name' => $school));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if( $row !== FALSE ) $institution_id = $row['institution_id'];

      // if there was no school, insert it
      if( $institution_id === FALSE ){

         $stmt = $pdo->prepare('INSERT INTO Institution (name) VALUES (:name)') ;
         $stmt->execute(array(':name' => $school));
         $institution_id = $pdo->lastInsertID();

      }

      $stmt = $pdo->prepare('INSERT INTO Education(profile_id, institution_id, rank, year) VALUES (:prof, :iid, :rank, :yr)');
      $stmt->execute(array( ':prof' => $profile_id,
                           ':iid' => $institution_id,
                           ':rank' => $rank,
                           ':yr' => $year));
      $rank++;
   }
      
}

/* NOTE: fetchAll() does 
$positions = array();

while( $row = $stmt->fetch(PDO::FETCH_ASSOC) ){
   $positions[]= $row;
}
return $positions; */

function loadPos($pdo, $profile_id) {

   $stmt = $pdo->prepare("SELECT * FROM Position WHERE profile_id = :pid ORDER BY rank");
   $stmt->execute(array( ':pid' => $profile_id ));

   $positions = array();

   while( $row = $stmt->fetch(PDO::FETCH_ASSOC) ){
      $positions[]= $row;
   }
   return $positions;

}


function loadEdu($pdo, $profile_id) {

   $stmt = $pdo->prepare('SELECT year,name FROM Education JOIN Institution ON Education.institution_id = Institution.institution_id WHERE profile_id = :prof ORDER BY rank');
   $stmt->execute(array(':prof' => $profile_id));
   $educations = $stmt->fetchAll(PDO::FETCH_ASSOC);
   return $educations;

}