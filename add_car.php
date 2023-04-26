<?php
// Check if user is an agency and has permission to access this page
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'agency') {
  header('Location: login.php');
  exit();
}
$user_id=$_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Connect to the database
require_once 'db.php';
$errors ="";
// Handle form submission to add or edit cars
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model =mysqli_real_escape_string($conn, $_POST['model']);
    $vehicle_number =mysqli_real_escape_string($conn, $_POST['vehicle_number']);
    $seating_capacity =mysqli_real_escape_string($conn, $_POST['seating_capacity']);
    $rent_per_day =mysqli_real_escape_string($conn, $_POST['rent_per_day']);
    $url =mysqli_real_escape_string($conn, $_POST['url']);

    
    // Check for any validation errors
    
    if (!$model || !$vehicle_number || !$seating_capacity  || !$rent_per_day) {
        $errors = 'Please enter Full Details';
    }

    

    if(!$errors){

        $agency_id = $_SESSION['user_id'];
        // Add new car
        if(!$url){
            $query = "INSERT INTO cars (model, vehicle_number, seating_capacity, rent_per_day, agency_id) VALUES (?, ?, ?, ?,?)";
        }
        else{
            $query = "INSERT INTO cars (model, vehicle_number, seating_capacity, rent_per_day,image_url, agency_id) VALUES (?, ?, ?, ?, ?,?)";
        }
        
        // prepare statement 
        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            die('Failed to prepare statement');
          }
          
        if(!$url){
            mysqli_stmt_bind_param($stmt, 'ssddi', $model, $vehicle_number, $seating_capacity, $rent_per_day, $agency_id);
        }
        else{
            mysqli_stmt_bind_param($stmt, 'ssddsi', $model, $vehicle_number, $seating_capacity, $rent_per_day,$url, $agency_id);
        }
         // Execute statement
        if (mysqli_stmt_execute($stmt)) {
            // Success
            header('Location: index.php');
            exit;
        } else {
            // Error
            $errors = mysqli_error($conn);
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
   <header class="site-navbar site-navbar-target" role="banner" >

      <div class="container">
      <div class="row align-items-center position-relative">

            <div class="col-3 ">
            <div class="site-logo">
               <a href="" style="color: black;">Car Rental</a>
            </div>
            </div>

            <?php include('nav.php');?>

   </header>

   <section class="vh-100 bg-image">
  <div class="mask d-flex align-items-center h-100 ">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
          <div class="card" style="border-radius: 15px;">
            <div class="card-body px-5 pt-5 pb-1">

              <h2 class="text-uppercase text-center mb-5"> ADD YOUR CAR</h2>

              


              <form action="" method="post">

                <div class="form-outline mb-4">
                <input type="text" id="form3Example1cg" class="form-control form-control-lg" name="model" placeholder="Enter Model Number"  required>
                </div>
                <div class="form-outline mb-4">
                  <input type="text" id="form3Example1cg" class="form-control form-control-lg" name="vehicle_number" placeholder="Enter Vehicle Number" required>
                </div>
                <div class="form-outline mb-4">
                  <input type="number" id="form3Example1cg" class="form-control form-control-lg" name="seating_capacity" placeholder="Enter Seating Capacity"  required>
                </div>

                <div class="form-outline mb-4">
                  <input type="number" step="0.01" id="form3Example3cg" class="form-control form-control-lg" name="rent_per_day" placeholder="Enter Rent Per Day"  required>
                </div>

                <div class="form-outline mb-4">
                  <input type="url"  id="form3Example4cg"  class="form-control form-control-lg" name="url" placeholder="Enter Image URL" >	
                </div>

                <div class="d-flex justify-content-center">
                <input type="submit" value="Add car " class="btn btn-primary btn-lg btn-block">
                </div>
                </form>
                <div class="mb-4"></div>
                <?php if(!empty($errors)){ ?>
                  <div class="alert alert-danger mt-2 d-flex justify-content-center align-items-center" role="alert">
                <?php echo $errors; ?>
                  </div>
                <?php } ?>
            </div>
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
