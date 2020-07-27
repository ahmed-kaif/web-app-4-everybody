<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Kaif Ahmed Khan 84f11ceb</title>
</head>

<body>

    <h1>Welcome to my guessing game</h1>

    <?php
    if (isset($_GET["guess"]) === FALSE) {
        echo "Missing guess parameter";
    } elseif (empty($_GET["guess"] == TRUE)) {
        echo "Your guess is too short";
    } elseif (!is_numeric($_GET["guess"])) {
        echo "Your guess is not a number";
    } elseif ($_GET["guess"] < 62) {
        echo "Your guess is too low";
    } elseif ($_GET["guess"] > 62) {
        echo "Your guess is too high";
    } else {
        echo "Congratulations - You are right";
    }
    ?>

</body>

</html>