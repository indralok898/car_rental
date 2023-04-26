-- Create the "users" table
CREATE TABLE users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  type ENUM('customer', 'agency') NOT NULL,
  PRIMARY KEY (id)
);

-- Create the "cars" table
CREATE TABLE cars (
  id INT(11) NOT NULL AUTO_INCREMENT,
  model VARCHAR(255) NOT NULL,
  vehicle_number VARCHAR(255) NOT NULL,
  seating_capacity INT(11) NOT NULL,
  rent_per_day DECIMAL(10, 2) NOT NULL,
  agency_id INT(11) NOT NULL,
  image_url varchar(255) DEFAULT 'https://artsmidnorthcoast.com/wp-content/uploads/2014/05/no-image-available-icon-6.png',
  PRIMARY KEY (id),
  FOREIGN KEY (agency_id) REFERENCES users(id)
);

-- Create the "bookings" table
CREATE TABLE bookings (
  id INT(11) NOT NULL AUTO_INCREMENT,
  customer_id INT(11) NOT NULL,
  car_id INT(11) NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (customer_id) REFERENCES users(id),
  FOREIGN KEY (car_id) REFERENCES cars(id)
);
