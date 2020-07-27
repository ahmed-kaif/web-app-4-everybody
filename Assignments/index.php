<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title>Kaif Ahmed Khan MD5</title>
</head>
<body>

<h1>MD5 Cracker</h1>
<p>This application takes an MD5 hash of a four digit pin and check all 10,000 possible four digit PINs to determine the PIN.</p>
<pre>
Debug Output:
<?php

    $goodText = "Not found";
    //if there is no parameter, this code is all skipped.
    if( isset($_GET['md5']) ) {
        $time_start = microtime(TRUE); //starts time count
        $md5 = $_GET['md5']; // our hash text

        //our alphabet
        $txt = "0123456789";
        $show = 15;

        //first position in our possible pre-hash text
        for($i=0; $i<strlen($txt); $i++){
            $ch1 = $txt[$i];

            for($j=0; $j<strlen($txt); $j++) {
                $ch2 = $txt[$j];

                for($k=0; $k<strlen($txt); $k++){
                    $ch3 = $txt[$k];

                    for($l=0; $l<strlen($txt); $l++){
                        $ch4 = $txt[$l];

                        $try = $ch1.$ch2.$ch3.$ch4;
                        //checks of the PIN matches
                        $check = hash('md5', $try);
                        if ( $check == $md5 ) {
                            $goodText = $try;
                            break;   // Exit the inner loop
                        }

                            //debug output until $show hits 0. means 15 results
                            if( $show > 0) {
                                print "$check $try\n";
                                $show = $show - 1;

                            }    
                        
                    }
                    
                }

            }

        }

  
    
    //compute elapsed time

    $time_end = microtime(TRUE);
    print "Elapsed time: ";
    print $time_end - $time_start;
    print "\n";
    }   
    
?>

</pre>

<p>PIN: <?= htmlentities($goodText); ?></p>

<form method="get">
    <input type="text" name="md5" size="60"/>
    <input type="submit" value="Crack MD5">
</form>
    
</body>
</html>