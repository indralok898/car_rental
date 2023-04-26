<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type']!=='customer') {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

// Check if a car_id is specified in the URL
if (!isset($_GET['car_id'])) {
    header('Location: index.php');   
    exit();
  }
$user_id=$_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$car_id = mysqli_real_escape_string($conn,$_GET['car_id']);
if(isset($_SESSION['msg'])){
    $error =$_SESSION['msg'];
    unset($_SESSION['msg']);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $customer_id =mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $car_id =mysqli_real_escape_string($conn, $_POST['car_id']);
    $days = mysqli_real_escape_string($conn,$_POST['num_days']);
    $start_date =mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $days . ' days'));
    if(strtotime($start_date)< strtotime(date('Y-m-d'))){
        
        $_SESSION['msg'] = "Starting date cant be less than Current date";
        header("Location: rent_car.php?car_id=".$car_id);
    }
    else{
        $stmt = $conn->prepare( "INSERT INTO bookings (customer_id, car_id, start_date, end_date) VALUES (?,?,?,?)");
        $stmt->bind_param("iiss", $customer_id, $car_id, $start_date, $end_date);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $_SESSION['msg'] ="car rented succesfully";
            header("Location: index.php");
            
        } else {
            
            $_SESSION['msg'] = $stmt->error;
            header("Location: rent_car.php?car_id=".$car_id);
        }
    }
    
}
else{
    $query = "SELECT * FROM cars WHERE id = $car_id";
    $car_result= mysqli_query($conn, $query);
    if (!$car_result || mysqli_num_rows($car_result) == 0) {
        // Car not found or does not belong to current agency user
        header('Location: index.php');
        exit();
      }
    $car = mysqli_fetch_assoc($car_result);
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
                        <a href="#">CarRent</a>
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
                    <div class="feature-car-rent-box-1">
                            <h3> <?php echo $car["model"] ?> </h3>
                            <form action="" method="post">
                            <ul class="list-unstyled">
                                <li>
                                    <span> Seats </span>
                                    <span class = "spec">
                                        <?php echo $car['seating_capacity'] ?>
                                    </span>
                                </li>
                                <li>
                                    <span> Vechile Number </span>
                                    <span class = "spec">
                                        <?php echo $car['vehicle_number'] ?>
                                    </span>
                                </li>
                                <li>
                                    <span> Rent </span>
                                    <span class = "spec">
                                    ₹ <?php echo (int) $car['rent_per_day']?>/day
                                    </span>
                                </li>
                                
                                   <li>
                                    <span>Start Date</span>

                                    <span class ="spec">
                                        <input type="date"  id="start_date" name="start_date" required>
                                    </span>
                                </li> 
                                   <li>
                                    <span>Number of days</span>

                                    <span class ="spec">
                                        <select name="num_days" id="num_days">
                                            <?php for ($i = 1; $i <= 30; $i++): ?>
                                                <option value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
                                            <?php endfor; ?>
                                        </select>
                                    </span>
                                </li> 
                                
                            </ul>         
                            <div class="d-flex align-items-center bg-light p-3">
                                <span><input type="hidden" name="car_id" value="<?php echo $car['id'] ?>"></span>          
                                <input type="submit" name="submit" value="Rent Car" class= "ml-auto btn btn-primary">
                            </div>
                        </form>
                    <?php if(!empty($error)){ ?>
                    <div class="alert alert-danger d-flex justify-content-center align-items-center" role="alert">
                    <?php echo $error; ?>
                    <?php } ?>
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
