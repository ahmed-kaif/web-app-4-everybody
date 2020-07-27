<?php

    if (!isset($_GET['name']) || strlen($_GET['name']) < 1){
        die("Name parameter missing");
    }
    if(isset($_POST['logout'])) {
        header("Location: index.php");
        return;
    }

    $names = array('Rock', 'Paper', 'Scissors');
    $human = isset($_POST['human']) ? $_POST['human']+0 : -1 ; 
    $computer = 0;

    $computer = rand(0,2);

    function check($human,$computer) {
        if($human == $computer){ //same
            return "Tie";
        } elseif( ($human==2 && $computer==1) || ($human==0 && $computer==2) || ($human==1 && $computer==0)){ 
            // scissor vs paper || rock vs scissor || paper vs rock
            return "You Win";
        } elseif(($human==1 && $computer==2) || ($human==0 && $computer==1) || ($human==2 && $computer==0)){
            //paper vs scissor || rock vs paper || scissor vs rock
            return "You Lose";
        }
        return FALSE;

    }

    $result = check($human,$computer);

?>

<!DOCTYPE html>
<html>

<?php require_once "bootstrap.php"; ?>

<body>

<div class="container">

<h1>Rock Paper Scissors</h1>
<p>Welcome: <?=htmlentities($_GET['name'])?></p>

<form method="POST">
<select name="human">
<option value="-1">Select</option>
<option value="0">Rock</option>
<option value="1">Paper</option>
<option value="2">Scissors</option>
<option value="3">Test</option>
</select>
<input type="submit" value="Play">
<input type="submit" name="logout" value="Logout">
</form>
<pre>
<?php
if($human == 3) {
    for($c=0;$c<3;$c++) {

        for($h=0;$h<3;$h++) {
        
        $r = check($h,$c);
        
        print "Human=$names[$h] Computer=$names[$c] Result=$r\n";
        
        }
        
        }
} elseif($human == -1) {
    print ("Please select a strategy and press Play.");
} else {
    echo ("Your Play=$names[$human] Computer Play=$names[$computer] Result=$result\n");
}
?>
</pre> 
</body>

</div>


</html>