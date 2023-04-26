<?php
// Check if user is an agency and has permission to access this page
session_start();
$user_id=$_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'agency') {
  header('Location: login.php');
  exit();
}

// Connect to the database
require_once 'db.php';
$errors = array();

$agency_id = $_SESSION['user_id'];
// Query to select all the bookings for the cars owned by the agency

$query = "SELECT cars.*, bookings.* , cars.id AS car_id
FROM cars
INNER JOIN bookings ON cars.id = bookings.car_id
WHERE cars.agency_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $agency_id);
$stmt->execute();
$result = $stmt->get_result();
$available_cars = $result->fetch_all(MYSQLI_ASSOC);

$agency_query_result = mysqli_query($conn, "SELECT name FROM users WHERE id = $agency_id");
$agency_name=mysqli_fetch_assoc($agency_query_result);

?>

!doctype html>
<html lang="en">

  <head>
    <title>Cars</title>
    
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


    <header class="site-navbar site-navbar-target bg-light" role="banner" style="background-color: #e3f2fd !important;">

        <div class="container">
          <div class="row align-items-center position-relative">

            <div class="col-3 ">
              <div class="site-logo">
                <a href="" style="color: black;">CarRent</a>
              </div>
            </div>

            <div class="col-9  text-right">
              

              <span class="d-inline-block d-lg-none"><a href="#" class="text-white site-menu-toggle js-menu-toggle py-5 text-white"><span class="icon-menu h3 text-white"></span></a></span>

              <?php include('nav.php'); ?>
          </div>

          
        </div>
      </div>
    </header>

    <div class="site-section bg-light ">
      <!-- <div class="container"> -->
      <?php if(count($available_cars)!=0): ?>
      <h2 class =" d-flex  justify-content-center  mb-5 pb-2 ">Booked Cars</h2>
          <div class="container-fluid m-2  "> 
            <div class=" d-flex justify-content-around  align-content-between flex-wrap ">
                <?php foreach ($available_cars as $car): ?>
                    <div class="item-1 col-sm-3 mb-3 ">
                      <img src="<?php echo $car['image_url'] ?>"alt="Image" class="img-fluid h-50">
                      <div class="item-1-contents">
                        <div class="text-center">
                        <h3><a href="rent_car.php"><?= htmlspecialchars($car['model']) ?></a></h3>
                        <div class="rating">
                          <span class="icon-star text-warning"></span>
                          <span class="icon-star text-warning"></span>
                          <span class="icon-star text-warning"></span>
                          <span class="icon-star text-warning"></span>
                          <span class="icon-star text-warning"></span>
                        </div>
                        <div class="rent-price"><span>₹<?= htmlspecialchars( (int) $car['rent_per_day']) ?>/</span>day</div>
                        </div>
                        <ul class="specs">
                          <li>
                            <span>Vehicle Number</span>
                            <span class="spec"><?= htmlspecialchars($car['vehicle_number']) ?></span>
                          </li>
                          <li>
                            <span>Seats</span>
                            <span class="spec"><?= htmlspecialchars($car['seating_capacity']) ?></span>
                          </li>
                          <li>
                            <span>Agency name</span>
                            <span class="spec"><?= htmlspecialchars($agency_name['name']) ?></span>
                          </li>
                          
                        </ul>
                        
      
                          <div class="d-flex action justify-content-center ">
                              <a href="booked_cars_info.php?car_id=<?php echo $car['car_id'] ?>" class="btn btn-primary w-100 ">View Booking Details</a>
                          </div>
                        
                        
                      </div>
                    </div>
                <?php endforeach; ?>
            </div>
          </div>
       <?php else: ?>
       <p>No cars available to rent</p>
       <?php endif; ?>
    </div>
    <?php include('footer.php'); ?>
    </div>
    <?php include ('scripts.php'); ?>
  </body>
</html>