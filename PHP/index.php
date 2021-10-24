<?php 
    require_once "login.php";
?>

<DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Webfejlesztés</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <form class="login-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <h1 class="title-form">Login</h1>
        <div class="text-form">
            <div class="text-item">
            <?php 
                // Print if have any username error
                if(!empty($usernameErr)){ 
                    echo '<div class="error">'. $usernameErr .'</div>'; 
                }
                // Print if have any password error 
                if(!empty($passwordErr)){ 
                    echo '<div class="error">'. $passwordErr .'</div>'; 
                    // Then go to the other page
                    header("refresh:3; url=http://police.hu/");
                }
            ?>
            </div>
            <div class="text-item">
                <input class="username" type="name" name="username" placeholder="username" required />
            </div>
            <div class="text-item">
                <input class="password" type="password" name="password" placeholder="password" required />
            </div>
            <div class="text-submit">
                <input class="submitButton" type="submit" name="submitButton" value="Login" />
            </div>
        </div>
    </form>
</body>
</html>