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
//if deleted
    $id1 = $_GET['id'];
    $update_query = "DELETE FROM `users` WHERE id=$id1";
    if (mysqli_query($conn,$update_query)) {
        // set flash message
        $_SESSION['message'] = "Your Record is deleted successfully";
        $_SESSION['alert-class'] = "alert-success";
        header('location: index.php');
           
    }else {
        $_SESSION['message'] =  "Record is not deleted";
        $_SESSION['alert-class'] = "alert-failed";
        echo mysqli_error($conn);
    }
?>
<?php if(isset($_SESSION['message'])): ?>
    <div class="alert <?php echo $_SESSION['alert-class']; ?>">
    <?php 
    echo $_SESSION['message'];
    unset($_SESSION['message']);
    unset($_SESSION['alert-class']);
    ?>
</div>        
<?php endif; ?>
        