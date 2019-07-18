<?php require_once 'controllers/authController.php' ?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <!-- Bootstrap 4 CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

        <link rel="stylesheet" type="text/css" href="style.css">

        <title>Login</title>

    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-4 offset-md-4 form-div login">
                    <form action="login.php" method="post">
                        <h3 class="text-center">Login</h3>

                        <?php if(count($errors) > 0): ?>
                        <div class="alert alert-danger">
                            <?php foreach($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </div>
                        <?php endif ; ?>
                        
                        <div class="form group">
                            <label for="user_name">Username or Email</label>
                            <input type="text" name="user_name" value="<?php echo $user_name; ?>" class="form-control form-control-lg">
                        </div>

                        <div class="form group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg">
                        </div>

                        <div class="form group">
                        <label for="login-btn"></label>
                            <button type="submit" name="login-btn" class="btn btn-primary btn-block btn-lg">Login</button>
                        </div>
                        
                        <p class="text-center">Not yet a member? <a href="signup.php">Sign Up</a></p>

                    </form>
                </div>
            </div>
        </div>
    </body>
</html>