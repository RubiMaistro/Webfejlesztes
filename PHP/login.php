<?php

    session_start();

    require_once "connect.php";

    // OffsetCode: 5, -14, 31, -9, 3
    $offset = array(5, -14, 31, -9, 3);
    $count = 0;

    // Check the post
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $username = $password = "";
        $usernamePost = $passwordPost = "";
        $usernamePostErr = $passwordPostErr = "";

        // Check if have any empty posted parameter
        if(empty(trim($_POST['username']))){
            $usernameErr = "Please enter the username";
        }else{
            $usernamePost = $_POST['username'];
        }
        if(empty(trim($_POST['password']))){
            $passwordErr = "Please enter the password";
        }else{
            $passwordPost = trim($_POST['password']);
        }

        // Open the file
        $filename = "password.txt";
        $file = fopen($filename, 'rb') or die ("Not found.");

        $fileArray = $usernameFile = $passwordFile = "";

        // Read the file
        while(!feof($file)){

            // Convert binary to hexadecimal
            $hex = bin2hex(fread($file, 1));
    
            // Check the end of line
            if($hex != "0a") {

                // Convert hexadecimal to decimal and subtract the offset
                $decimal = hexdec($hex) - $offset[$count];

                // Check the out of range
                if($decimal < 0){
                    $decimal += 128;
                }else if($decimal > 127){
                    $decimal -=128;
                }
                
                // Add the character to array
                $fileArray .= chr($decimal);
    
            // At the end of line
            }else{
                // Save the information from line
                list($usernameFile, $passwordFile) = explode("*", $fileArray);
                $fileArray = "";

                // It haven't any error
                if(empty($usernameErr) && empty($passwordErr)){

                    // Check the username
                    if($usernameFile == $usernamePost){

                        // If found the username
                        $username = $usernamePost;

                        // Create statement from Database
                        if($stmt = $pdo->prepare("SELECT username, titkos FROM tabla WHERE username = :username")){

                            // Bind parameter
                            $stmt->bindParam(":username", $usernamePost, PDO::PARAM_STR);

                            // It can execute statement then continue
                            if($stmt->execute()){

                                // Check the username is exist
                                if($stmt->rowCount() == 1){

                                    // Then fetch the information into the row from Database
                                    if($row = $stmt->fetch()){

                                        // Check the password
                                        if($passwordFile == $passwordPost){

                                            // Save the password
                                            $password = $passwordPost;

                                            // Then new session
                                            session_start();

                                            // Save the information from row
                                            $color = $row['titkos'];

                                            // Set parameters in the new session
                                            $_SESSION['username'] = $username;
                                            $_SESSION['color'] = $color;

                                            // Then go to welcome page
                                            header("location: welcome.php");
                                        }
                                    }
                                }
                            }
                            // Close statement
                            unset($stmt);
                        }
                    }
                }
            }

            // At the end of line or offset array restart offset counter 
            if($count == 4 || $hex == "0a"){
                $count = 0;
            }else{
                $count += 1;
            }
        }

        if(empty($username)){
            // Username error
            $usernameErr = "Hibás felhasználónév";
        }else if(empty($password)){
            // Password error
            $passwordErr = "Hibás jelszó.";
        }

        // Close file
        fclose($file);
    
        // Close database
        unset($pdo);
    }

?>