<?php
    // Initialize the session
    session_start();

    $name = $email = "";

    $username = $_SESSION["username"];
    list($name, $email) = explode("@", $username);

    switch($_SESSION['color']){
        case "piros":
            $color = "red";
            break;
        case "kek":
            $color = "blue";
            break;
        case "zold":
            $color = "green";
            break;
        case "sarga":
            $color = "yellow";
            break;
        case "fekete":
            $color = "black";
            break;
        case "feher":
            $color = "white";
            break;
    }

?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="welcome-body">
    <div class="hello">
        <?php
            echo '<div>Hello <b style="color:' .$color. ';">'. $name .'</b>! Welcome in my website! Your color is ' .$color .'.</div>';
        ?>
    </div>
    <div style="margin-top: 50px;">
        <?php
            echo '<div style="background-color:'. $color .'; width: 200px; height: 200px; border-radius: 50%; text-align: center; "></div>';
        ?>
    </div>
</body>
</html>