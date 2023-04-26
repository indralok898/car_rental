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
$errors = array();

// Check if a car_id is specified in the URL
if (!isset($_GET['car_id'])) {
  header('Location: index.php');
  exit();
}

// Get the car information from the database
$car_id = mysqli_real_escape_string($conn, $_GET['car_id']);
$agency_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
$query = "SELECT * FROM cars WHERE id = $car_id AND agency_id = $agency_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
  // Car not found or does not belong to current agency user
  $_SESSION['error_msg'] ="car rented succesfully";
  header('Location: index.php');
  exit();
}


$car = mysqli_fetch_assoc($result);

// Handle form submission to update the car information
if (isset($_POST['submit'])) {
  $model = mysqli_real_escape_string($conn, $_POST['model']);
  $vehicle_number = mysqli_real_escape_string($conn, $_POST['vehicle_number']);
  $seating_capacity = mysqli_real_escape_string($conn, $_POST['seating_capacity']);
  $rent_per_day = mysqli_real_escape_string($conn, $_POST['rent_per_day']);
  $url = mysqli_real_escape_string($conn, $_POST['url']);

  // Check for any validation errors
  if (!$model) {
    $errors[] = 'Please enter a vehicle model';
  }
  if (!$vehicle_number) {
    $errors[] = 'Please enter a vehicle number';
  }
  if (!$seating_capacity) {
    $errors[] = 'Please enter a valid seating capacity';
  }
  if (!$rent_per_day) {
    $errors[] = 'Please enter a valid rent per day';
  }

  if (count($errors) == 0) {
    // Update the car information in the database
    $query = "UPDATE cars SET model = ?, vehicle_number = ?, seating_capacity = ?, rent_per_day = ?, image_url = ? WHERE id = ? AND agency_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
      die('Failed to prepare statement');
    }
    mysqli_stmt_bind_param($stmt, 'ssddssi', $model, $vehicle_number, $seating_capacity, $rent_per_day, $url, $car_id, $agency_id);
    if (mysqli_stmt_execute($stmt)) {
      // Success
      header('Location: index.php');
      exit;
    } else {
      // Error
      echo "Error: " . mysqli_error($conn);
    }
  }
}
?>


<!doctype html>
<html lang="en">

    <head>
            <title>Rent car</title>
            
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <link href="https://fonts.googleapis.com/css?family=DM+Sans:300,400,700&display=swap" rel="stylesheet">
            <?php include('css.php');?>
            
            

    </head>
    <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    
        <div class="site-wrap" id="home-section">

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
                        <a href="">Edit Car</a>
                    </div>
                    </div>

                    <div class="col-9  text-right">
                    

                    <span class="d-inline-block d-lg-none"><a href="#" class="text-white site-menu-toggle js-menu-toggle py-5 text-white"><span class="icon-menu h3 text-white"></span></a></span>

                    
                    <?php include('nav.php'); ?>
                   
                    </div>

                    
                </div>
                </div>

            </header>
            <div class="ftco-blocks-cover-1">
            <div class="ftco-cover-1 overlay" style="background-image: url(<?php echo $car['image_url'] ?>)">
            <div class="container">
                <div class="row align-items-center">
                <div class="col-lg-5">
                <form method ="post">
                    <div class="feature-car-rent-box-1">
                            <h3> <input type="text" id="model" name="model" value="<?php echo $car['model']; ?>" required></h3>
                            <ul class="list-unstyled">
                                <li>
                                    <span> Seats </span>
                                    <span class = "spec">
                                      <input type="number" id="seating_capacity" name="seating_capacity" value="<?php echo $car['seating_capacity']; ?>" required>
                                    </span>
                                </li>
                                <li>
                                    <span> Vechile Number </span>
                                    <span class = "spec">
                                      <input type="text" id="vehicle_number" name="vehicle_number" value="<?php echo $car['vehicle_number']; ?>" required>
                                    </span>
                                </li>
                                <li>
                                    <span> Rent </span>
                                    <span class = "spec">
                                    <input type="number" step="0.01" id="rent_per_day" name="rent_per_day" value="<?php echo (int) $car['rent_per_day']; ?>" required>
                                    </span>
                                </li>
                                <li>
                                    <span> Image </span>
                                    <span class = "spec">
                                      <input type="url" id="my-url-field" name="url" value="<?php echo $car['image_url']; ?>">
                                </li>  
                            </ul>         
                            <div class="d-flex align-items-center bg-light p-3">
                                <span><input type="hidden" name="car_id" value="<?php echo $car['id']; ?>"></span>
                                
                                <input type="submit" name="submit" value="Update Car" class= "ml-auto btn btn-primary">
                            </div>
                    </form>
                    </div>
                    <?php if(($errors)){ ?>
                        <div class="alert alert-danger" role="alert">
                        <?php echo $errors[0]; ?>
                        </div>
                    <?php } ?>
                </div>
                </div>
            </div>
            </div>
            </div>
            <?php include('footer.php'); ?>
        </div>
        <?php include ('scripts.php'); ?>
    </body>
</html>
