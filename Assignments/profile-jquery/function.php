<?php

require_once "pdo.php";


//checks user login

function isloggedin()
{
   if(!isset($_SESSION['name'])){
      die('ACCESS DENIED');
   }
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


function loadPos($pdo, $profile_id) {

   $stmt = $pdo->prepare("SELECT * FROM Position WHERE profile_id = :pid ORDER BY rank");
   $stmt->execute(array( ':pid' => $profile_id ));

   $positions = array();

   while( $row = $stmt->fetch(PDO::FETCH_ASSOC) ){
      $positions[]= $row;
   }
   return $positions;

}