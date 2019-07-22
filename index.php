<?php 
    require_once 'controllers/authController.php' ;

    // verify the user using token
    if(isset($_GET['token'])){
        $token = $_GET['token'];
        verifyUser($token);
    }
    if(!isset($_SESSION['id'])){
        header('location: login.php');
        exit();
    }
?> 
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <!-- Bootstrap 4 CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

        <link rel="stylesheet" type="text/css" href="style.css">

        <title>Home Page</title>

    </head>

    <body>
        
        <div class="container">
            <div class="row">
                <div class="col-md-12 offset-md-2 form-div login">

                    <?php if(isset($_SESSION['message'])): ?>
                    <div class="alert <?php echo $_SESSION['alert-class']; ?>">
                        <?php 
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                        unset($_SESSION['alert-class']);
                        ?>
                    </div>
                    <?php endif; ?>
                    <!-- put your index code here -->
                    
                    
                    <h3>Welcome <?php echo $_SESSION['user_name']; ?></h3>
                    <a href="index.php?logout=1" class="logout">Logout</a> 

                    <?php if(!$_SESSION['verified']): ?>
                        <div class="alert alert-warning">
                        You need to verify your account.
                        Sign in to your account and click on the
                        verification link we just emailed at
                        <strong><?php echo $_SESSION['email']; ?></strong>
                        </div>
                    <?php endif; ?>
                    <?php if($_SESSION['verified']): ?>
                        <!-- <button class="btn btn-block btn-lg btn-primary">I am verified!</button> -->
                        <div class="btn-group float-right mt-2" role="group">
                        <button class="btn btn-md btn-secondary"><a href="signup.php" class="text-white">Add New</a></button>
                        </div>
                        <h1 class="text-warning text-center">Users List</h1>
                        <table class="table table-striped table-hover table-bordered">
                            <tr class="bg-dark text-white text-center">
                                <td> ID </td>
                                <td> First Name </td>
                                <td> Last Name </td>
                                <td> Email </td>
                                <td> Action </td>
                            </tr>
                            <?php
                            $mysqli = new mysqli("localhost", 'root', '', 'user-verification');
                            $query = "SELECT * FROM users";
                            $query = mysqli_query($mysqli,$query);

                            while($result = mysqli_fetch_array($query)){
                            ?>
                            <tr class="text-center">
                                <td> <?php echo $result['id']; ?> </td>
                                <td> <?php echo $result['first_name']; ?>  </td>
                                <td> <?php echo $result['last_name']; ?> </td>
                                <td> <?php echo $result['email']; ?>  </td>
                                <td> <button class="btn btn-primary"> <a href="edit.php?id=<?php echo $result['id']; ?>" class="text-white"> Edit </a> </button>
                                <button name="login-btn" class="btn-danger btn"> <a href="delete.php?id=<?php echo $result['id']; ?>" class="text-white"> Delete </a>  </button> </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </body>
</html>