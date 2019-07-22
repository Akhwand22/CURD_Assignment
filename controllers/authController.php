<?php

    session_start();

    require 'config/db.php';
    require_once 'emailController.php';

    $errors=array();
    $id1 ="";
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
                
                sendVerificationEmail($email,$token);

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

    // if user click on login button

    if(isset($_POST['login-btn'])){
            $user_name = $_POST['user_name'];
            $password = $_POST['password'];

            // validation
            if(empty($user_name)){
                $errors['user_name']="Username required";
            }
            if(empty($password)){
                $errors['password']="Password required";
            }

            if (count($errors)===0) {
                $sql="SELECT * FROM users WHERE email=? or user_name=? LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ss', $user_name, $user_name);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                if (password_verify($password,$user['password'])) {
                    // login success
                    $_SESSION['id']=$user['id'];
                    $_SESSION['user_name'] = $user['user_name'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['verified'] = $user['verified'];
                    // $_SESSION['token'] = $token;
                    // $_SESSION['password'] = $password;
                    // set flash message
                    $_SESSION['message'] = "You are now logged in!";
                    $_SESSION['alert-class'] = "alert-success";
                    header('location: index.php');
                    exit();
                }else {
                    $errors['login_fail']="Wrong Credentials";
                }
                $stmt->close();
            }
            
    }

    // logout user
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['first_name']);
        unset($_SESSION['last_name']);
        unset($_SESSION['email']);
        unset($_SESSION['verified']);
        header('location:login.php');
        exit();
    }

    // verify user by token
    function verifyUser($token){
        global $conn;
        $sql = "SELECT * FROM users WHERE token='$token' LIMIT 1";
        $result = mysqli_query($conn,$sql);
        if (mysqli_num_rows($result)>0) {
            $user = mysqli_fetch_assoc($result);
            $update_query = "UPDATE users SET verified = 1 WHERE token = '$token'";

            if (mysqli_query($conn,$update_query)) {
                // log user in
                $_SESSION['id'] = $user['id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['verified'] = 1;
                // set flash message
                $_SESSION['message'] = "Your email address was successfully verified!";
                $_SESSION['alert-class'] = "alert-success";
                header('location: index.php');
                exit();
            }

        }else {
            echo "User not found";
        }
    }

    //if Update
    if(isset($_POST['update'])){
        $id1 = $_GET['id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];

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
        else {
            $update_query = "UPDATE users SET 
            first_name='$first_name',
            last_name='$last_name',
            email='$email'
            WHERE id=$id1";
            if (mysqli_query($conn,$update_query)) {
                // set flash message
                $_SESSION['message'] = "Your Record updated successfully";
                $_SESSION['alert-class'] = "alert-success";
                header('location: index.php');
                
            }else {
                $_SESSION['message'] =  "Record not updated";
                $_SESSION['alert-class'] = "alert-failed";
                echo mysqli_error($conn);
            }
        }
    }
    