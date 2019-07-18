<?php

    session_start();

    require 'config/db.php';

    $errors=array();
    $user_name = "";
    $first_name = "";
    $last_name = "";
    $email = "";

    // if user click on sign up button
    if(isset($_POST['signup-btn'])){
        $user_name = $_POST['user_name'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConf = $_POST['passwordConf'];

        // validation
        if(empty($user_name)){
            $errors['user_name']="Username required";
        }
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $errors['email']="Email address is invalid";
        }
        if(empty($first_name)){
            $errors['first_name']="First name required";
        }
        if(empty($last_name)){
            $errors['last_name']="Last name required";
        }
        if(empty($email)){
            $errors['email']="Email required";
        }
        if(empty($password)){
            $errors['password']="Password required";
        }
        if($password !== $passwordConf){
            $errors['password']="The two password do not match";
        }

        $emailQuery = "SELECT * FROM users where email=? LIMIT 1";
        $stmt = $conn->prepare($emailQuery);
        $stmt->bind_param('s',$email);
        $stmt->execute();
        $result = $stmt->get_result();
        $userCount = $result->num_rows;
        $stmt->close();

        if ($userCount>0) {
            $errors['email']="Email already exists";
        }

        if (count($errors)=== 0) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $token = bin2hex(random_bytes(50));
            $verified = false;

            $sql = "INSERT INTO users(user_name, first_name, last_name, email, verified, token, password) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssbss', $user_name, $first_name, $last_name, $email, $verified, $token, $password);
            
            if($stmt->execute()){
                // login user
                $user_id = $conn->insert_id;
                $_SESSION['user_name'] = $user_name;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;
                $_SESSION['email'] = $email;
                $_SESSION['verified'] = $verified;
                // $_SESSION['token'] = $token;
                // $_SESSION['password'] = $password;
                // set flash message
                $_SESSION['message'] = "You are now logged in!";
                $_SESSION['alert-class'] = "alert-success";
                header('location: index.php');
                exit();
            } else {
                $errors['db_error'] = "Database error: failed to register";
            }


        }

    }