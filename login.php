<?php

require_once 'db.php'; // estblishing the conection.

if($_SERVER["REQUEST_METHOD"] == "POST") {

  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
   
  if($result->num_rows==1)
  {
    $row=$result->fetch_assoc();
    $stored_password=$row['password'];

    if(password_verify($password, $stored_password))
        {
          session_start();
            if($row['type']=='customer')
            {
                
                $_SESSION['email']=$email;
                $_SESSION['user_id']=$row['id'];
                $_SESSION['user_type']='customer';
                session_write_close();
                header('Location: index.php');
            }
            else
            {
                
                $_SESSION['email']=$email;
                $_SESSION['user_id']=$row['id'];
                $_SESSION['user_type']='agency';
                session_write_close();
                header('Location: index.php');
            }
      } 
      else{
        $error = "Your Email or Password is invalid";
      }
  }
  else{
      $error = "User not found. Please register";
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
<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300" >

         
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

<section class="vh-100">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <h3 class="mb-5">Sign in</h3>

            <form action="login.php" method="post">

            <div class="form-outline mb-4">
              <input type="email" id="typeEmailX-2" class="form-control form-control-lg " name="email"  placeholder="email" required />
              <!-- <label class="form-label" for="typeEmailX-2">Email</label> -->
            </div>

            <div class="form-outline mb-4">
              <input type="password" id="typePasswordX-2" class="form-control form-control-lg" name="password" placeholder="password" required/>
              <!-- <label class="form-label" for="typePasswordX-2">Password</label> -->
            </div>


            <input class="btn btn-primary btn-lg btn-block" type="submit" value="Log In">
            </form>


          </div>
          <div class ="d-flex justify-content-center mb-4">
              <p class="mb-0">Don't have an account? <a href="register.php" class="text-blue-50 fw-bold">Sign Up</a>
              </p>
            </div>
          <?php if(!empty($error)){ ?>
            <div class="alert alert-danger d-flex justify-content-center align-items-center" role="alert">
              
              <?php echo $error; ?>
              
              
              
         <?php } ?>
        </div>
      </div>
    </div>
  </div>
</section>
</div>
<?php include('footer.php'); ?>
      </div>
      <?php include ('scripts.php'); ?>
      </body>

</html>

