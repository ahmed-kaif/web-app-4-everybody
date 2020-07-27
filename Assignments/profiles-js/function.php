<?php

//checks user login

function isloggedin()
{
   if(!isset($_SESSION['name'])){
      die('Not logged in');
   }
}

?>