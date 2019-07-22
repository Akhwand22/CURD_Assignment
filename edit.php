<?php
require_once 'controllers/authController.php' ;

// verify the user using token
/* if(isset($_GET['token'])){
    $token = $_GET['token'];
    verifyUser($token);
} */
if(!isset($_SESSION['id'])){
    header('location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="style.css">

    <title>Edit Page</title>    
</head>
<body>
    
    <div class="col-lg-6 m-auto">
        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert <?php echo $_SESSION['alert-class']; ?>">
            <?php 
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            unset($_SESSION['alert-class']);
            ?>
            </div>        
        <?php endif; ?>
        <form action="" method="post">

           <br><br><div class="card">
               <div class="card-header bg-dark">
                   <h1 class="text-white text-center">Update Record</h1>
               </div>
               <br>
               <label for="first_name">First Name</label>
               <input type="text" name="first_name"  class="form-control" placeholder="Enter First Name"><br>
               <label for="last_name">Last Name</label>
               <input type="text" name="last_name" class="form-control" placeholder="Enter Last Name"><br>
               <label for="email">E-Mail</label>
               <input type="email" name="email" class="form-control" placeholder="Enter E-Mail"><br>

               <button type="submit" name="update" value="submit" class="btn btn-success">UPDATE</button>
 
           </div> 
        </form>
    </div>
</body>
</html>