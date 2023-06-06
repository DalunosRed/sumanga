<?php

include 'config.php';

if(isset($_POST['submit'])){

   $first_name = $_POST['first_name'];
   $first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
   $last_name = $_POST['last_name'];
   $last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   $pass = sha1($_POST['password']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpassword']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_users = $conn->prepare("SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'");

   if($select_users->rowCount() > 0){
      $error_msg[] = 'user already exist!';
   }else{
      if($pass != $cpass){
         $error_msg[] = 'confirm password not matched!';
      }else{
         // $user_register= $conn->prepare("INSERT INTO `users`(first_name, last_name, email, password) VALUES('$first_name','$last_name', '$email', '$pass')");
         $insert_user = $conn->prepare("INSERT INTO `users`(first_name, last_name, email, password) VALUES(?,?,?,?)");
         $insert_user->execute([$first_name, $last_name, $email, $pass]);
         $success_reg[] = 'registered successfully!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>
   <link rel="icon" type="image/png"  href="images/logo-small.png">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/login.css">

</head>
<body>



<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   <!-- <div class="reg">
   <form class="form" action="" method="post">
    <p class="title">Register </p>
    <p class="message">Signup now and get full access to our website. </p>
        <div class="flex">
        <label>
            <input required="" placeholder="" type="text" name="last_name" class="input">
            <span>Last name</span>
        </label>

        <label>
            <input required="" placeholder="" type="text" name="first_name" class="input">
            <span>First name</span>
        </label>
    </div>  
            
    <label>
        <input required="" placeholder="" type="email" name="email" class="input">
        <span>Email</span>
    </label> 
        
    <label>
        <input required="" placeholder="" type="password" name="password" class="input">
        <span>Password</span>
    </label>
    <label>
        <input required="" placeholder="" type="password" name="cpassword" class="input">
        <span>Confirm password</span>
    </label>
    <input type="submit" name="submit" value="register now" class="submit">
    <p class="signin">Already have an acount ?<a href="login.php">Signin</a> </p>
</form>
</div> -->

<section class="w3l-mockup-form">
        <div class="container">
            <!-- /form -->
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                    <div class="alert-close">
                        <span class="fa fa-close"></span>
                    </div>
                    <div class="w3l_form align-self">
                        <div class="left_grid_info">
                            <img src="images/logo-small.png" alt="">
                        </div>
                    </div>
                    <div class="content-wthree">
                        <h2>Register Now</h2>
                        <form action="" method="post">
                        <input required="" placeholder="Enter your Last name" type="text" name="last_name" class="name">
                        <input required="" placeholder="Enter your First name" type="text" name="first_name" class="name">
                        <input required="" placeholder="Enter your email address" type="email" name="email" class="input">
                        <input required="" type="password" name="password" class="input">
                        <input required=""  type="password" name="cpassword" class="input">
                            <button name="submit" class="btn" type="submit">Register</button>
                        </form>
                        <div class="social-icons">
                            <p>Have an account! <a href="login.php">Login</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- //form -->
        </div>
    </section>


<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'alers.php' ?>
</body>
</html>