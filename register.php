<?php
include 'db.php';
if(isset($_GET['user-type'])){
    $redirected_type=$_GET['user-type'];
}
else{
    $redirected_type="";
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Fetch form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $type = mysqli_real_escape_string($conn,$_POST['type']);

    if(!$name || !$email || !$password || !$type){
      $error = "Please provide details";
      
    }
    else{
    // hash the password
    $password = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepare and execute SQL query
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $type);
    $stmt->execute();
    
    // Check for successful registration
    if ($stmt->affected_rows > 0) {
        // Redirect to login page
        header("Location: login.php");
        exit;
    } else {
        // registration failed, display an error message
        $error=  $stmt->error;
    }

    }


}
?>



<!DOCTYPE html>
<html>

      
<head>
      <title>Login Page</title>
      <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=DM+Sans:300,400,700&display=swap" rel="stylesheet">
    <?php include('css.php');?>


   </head>
<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

         
<div class="site-wrap" id="home-section" style="background-image: url('images/bg_1.jpg')">

   <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
      <div class="site-mobile-menu-close mt-3">
            <span class="icon-close2 js-menu-toggle"></span>
      </div>
      </div>
      <div class="site-mobile-menu-body"></div>
   </div>
   <header class="site-navbar site-navbar-target" role="banner">

      <div class="container">
      <div class="row align-items-center position-relative">

            <div class="col-3 ">
            <div class="site-logo">
               <a href="">Car Rental</a>
            </div>
            </div>

            <div class="col-9  text-right">
            

            <span class="d-inline-block d-lg-none"><a href="#" class="text-white site-menu-toggle js-menu-toggle py-5 text-white"><span class="icon-menu h3 text-white"></span></a></span>

            
            <nav class="site-navigation text-right ml-auto d-none d-lg-block" role="navigation">
              <ul class="site-menu main-menu js-clone-nav ml-auto ">
                <li class="active"><a href="index.php" class="nav-link">Home</a></li>
                  <li><a href="login.php" class="nav-link">Login</a></li>
                  <li><a href="register.php" class="nav-link">Register</a></li>
              </ul>
            </nav>
         
            </div>

            
      </div>
      </div>

   </header>

   <section class="vh-100 bg-image">
  <div class="mask d-flex align-items-center h-100 gradient-custom-3">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
          <div class="card" style="border-radius: 15px;">
            <div class="card-body px-5 pt-5 pb-1">
            <?php if($redirected_type): ?>
              <h2 class="text-uppercase text-center mb-5"> Agent Registration</h2>
            <?php else: ?>
                <h2 class="text-uppercase text-center mb-5"> Customer Registration</h2>
            <?php endif; ?>

              <form action="" method="post">

                <div class="form-outline mb-4">
                  <input type="text" id="form3Example1cg" class="form-control form-control-lg" name="name" placeholder="Name" />
                  
                </div>

                <div class="form-outline mb-4">
                  <input type="email" id="form3Example3cg" class="form-control form-control-lg" name="email"  required placeholder="E-mail" />
                  
                </div>

                <div class="form-outline mb-4">
                  <input type="password" id="form3Example4cg" class="form-control form-control-lg" name="password"  required placeholder="Password" />
                  
                </div>
                <?php if($redirected_type): ?>
                  <input type="hidden" name="type" value="agency">
                <?php else: ?>
                  <input type="hidden" name="type" value="customer">
                <?php endif; ?>
                

                <div class="d-flex justify-content-center">
                <input class="btn btn-primary btn-lg btn-block" type="submit" value="Register">
                </div>
                </form>
                <?php if(!empty($error)){ ?>
                  <div class="alert alert-danger mt-2 d-flex justify-content-center align-items-center" role="alert">
                <?php echo $error; ?>
                  </div>
                <?php } ?>
            </div>
            
            <?php if($redirected_type): ?>
                    <p class="text-center text-muted mt-2 mb-0">Are you a customer? <a href="register.php"
                    class="fw-bold text-body"><u>register here</u></a></p>
                    
            <?php else: ?>
                    <p class="text-center text-muted mt-2 mb-0">Are you a Agent? <a href="register.php?user-type=agency"
                    class="fw-bold text-body"><u>register here</u></a></p>
             <?php endif; ?>
                
            <p class="text-center text-muted mt-2 mb-3">Have already an account? <a href="login.php"
                    class="fw-bold text-body"><u>Login here</u></a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>

   <?php include('footer.php'); ?>
      </div>
      <?php include ('scripts.php'); ?>
      </body>

</html>













































