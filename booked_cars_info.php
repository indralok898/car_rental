<?php
// Check if user is an agency and has permission to access this page
session_start();
$user_id=$_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'agency') {
  header('Location: login.php');
  exit();
}
require_once 'db.php';

$agency_id = $_SESSION['user_id'];
$car_id = $_GET['car_id'];

if(!$car_id){
    header('Location: index.php');
    exit();
}

$query ="SELECT u.*, c.*, b.*
FROM users u
JOIN cars c ON c.agency_id = u.id
JOIN bookings b ON b.car_id = c.id
WHERE c.id = $car_id AND u.id = $agency_id";


$car_result= mysqli_query($conn, $query);

$car_details = mysqli_fetch_assoc($car_result);
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
                        <a href="#">CarRent</a>
                    </div>
                    </div>

                    <div class="col-9  text-right">
                    

                    <span class="d-inline-block d-lg-none"><a href="#" class="text-white site-menu-toggle js-menu-toggle py-5 text-white"><span class="icon-menu h3 text-white"></span></a></span>

                    <nav class="site-navigation text-right ml-auto d-none d-lg-block " role="navigation">
              <ul class="site-menu main-menu js-clone-nav ml-auto ">
                
                <li class="active" ><a href="index.php" class="nav-link" >Home</a></li>
                <?php if($user_type==='agency'): ?>
                  <li><a href="add_car.php" class="nav-link" >AddCars</a></li>
                  <li><a href="booked_cars.php" class="nav-link" >BookedCars</a></li>
                <?php endif; ?>
                <?php if($user_id!=null): ?>
                <li><a href="logout.php" class="nav-link" >Logout</a></li>
                <?php else: ?>
                <li><a href="login.php" class="nav-link" >Login</a></li>
                <li><a href="register.php" class="nav-link" >Register</a></li>
                <?php endif; ?>
              </ul>
            </nav>
                    </div>

                    
                </div>
                </div>

            </header>
            <div class="ftco-blocks-cover-1">
            <div class="ftco-cover-1 overlay" style="background-image: url(<?php echo $car_details['image_url'] ?>)">
            <div class="container">
                <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="feature-car-rent-box-1">
                            <h3> <?php echo $car_details["model"] ?> </h3>
                            <ul class="list-unstyled">
                                <li>
                                    <span> Rented By </span>
                                    <span class = "spec">
                                        <?php echo $car_details['name'] ?>
                                    </span>
                                </li>
                                <li>
                                    <span> Seats </span>
                                    <span class = "spec">
                                        <?php echo $car_details['seating_capacity'] ?>
                                    </span>
                                </li>
                                <li>
                                    <span> Vechile Number </span>
                                    <span class = "spec">
                                        <?php echo $car_details['vehicle_number'] ?>
                                    </span>
                                </li>
                                <li>
                                    <span> Rent </span>
                                    <span class = "spec">
                                    ₹ <?php echo (int) $car_details['rent_per_day']?>/day
                                    </span>
                                </li>
                                <li>
                                    <span> Rented From </span>
                                    <span class = "spec">
                                         <?php echo $car_details['start_date']?>
                                    </span>
                                </li>
                                <li>
                                    <span> Rented To </span>
                                    <span class = "spec">
                                         <?php echo $car_details['end_date']?>
                                    </span>
                                </li> 
                                
                            </ul>         
                            <div class="d-flex align-items-center bg-light p-3">
                      
                              <a href="booked_cars.php" class="btn btn-primary w-100 ">Go Back</a>
                            </div>
                    
                    </div>
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




















