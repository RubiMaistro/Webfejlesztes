<?php

    session_start();

    require_once "connect.php";

    // Code: 5, -14, 31, -9, 3
    $offset = array(5, -14, 31, -9, 3);
    $count = 0;


    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $usernamePost = $passwordPost = "";
        $usernamePostErr = $passwordPostErr = "";

        if(empty(trim($_POST['username']))){
            $usernamePostErr = "Please enter the username";
        }else{
            $usernamePost = $_POST['username'];
        }
        
        if(empty(trim($_POST['password']))){
            $passwordPostErr = "Please enter the password";
        }else{
            $passwordPost = trim($_POST['password']);
        }

        $filename = "password.txt";
        $file = fopen($filename, 'rb') or die ("Not found.");

        $fileArray = $usernameFile = $passwordFile = "";

        while(!feof($file)){

            $hex = bin2hex(fread($file, 1));
    
            //echo $hex ." ";
            if($hex != "0a") {
                $decimal = hexdec($hex) - $offset[$count];
                if($decimal < 0){
                    $decimal += 128;
                }else if($decimal > 127){
                    $decimal -=128;
                }
                
                $fileArray .= chr($decimal);
    
            }else{
                list($usernameFile, $passwordFile) = explode("*", $fileArray);
                $fileArray = "";


                if(empty($usernamePostErr) && empty($passwordPostErr)){
                    if($usernameFile == $usernamePost){

                        if($stmt = $pdo->prepare("SELECT username, titkos FROM tabla WHERE username = :username")){
                            // Bind parameter
                            $stmt->bindParam(":username", $usernamePost, PDO::PARAM_STR);

                            if($stmt->execute()){
                                // Check username exist
                                if($stmt->rowCount() == 1){
                                    $usernamePostErr = "";

                                    if($row = $stmt->fetch()){
                                        // Password 
                                        if($passwordFile == $passwordPost){
                                            $passwordPostErr = "";
                                            // Then new session
                                            session_start();

                                            $color = $row['titkos'];

                                            $_SESSION['username'] = $usernamePost;
                                            $_SESSION['color'] = $color;

                                            header("location: welcome.php");
                                        }else{
                                            // Password error
                                            $passwordPostErr = "Hibás jelszó.";
                                            header("location: http://police.hu/");
                                        }
                                    }
                                }else{
                                    // Username error
                                    $usernamePostErr = "Hibás felhasználó.";
                                }
                            }
                            // Close statement
                            unset($stmt);
                        }
                    }
                }
            }

            // Ha a titkosítás visszafejtéskor végiglépkedtünk a kódszámokon, 
            // akkor kezdje elölről, valamint minden új sorban elölről 
            if($count == 4 || $hex == "0a"){
                $count = 0;
            }else{
                $count += 1;
            }
        }

        //Close file
        fclose($file);
    
        // Close database
        unset($pdo);
    }

?>