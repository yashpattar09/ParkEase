CREATE DATABASE parkease;
USE parkease;

-- Create users table
CREATE TABLE users (
    user_id INT(11) NOT NULL AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
    UNIQUE KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create parking_locations table
CREATE TABLE parking_locations (
    location_id INT(11) NOT NULL AUTO_INCREMENT,
    location_name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    total_slots INT(11) NOT NULL,
    available_slots INT(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    price_per_hour DECIMAL(10,2) NOT NULL DEFAULT 10.00,
    PRIMARY KEY (location_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create parking_sections table
CREATE TABLE parking_sections (
    section_id INT(11) NOT NULL AUTO_INCREMENT,
    location_id INT(11) NOT NULL,
    section_name VARCHAR(10) NOT NULL,
    total_sports INT(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (section_id),
    FOREIGN KEY (location_id) REFERENCES parking_locations(location_id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create parking_spots table
CREATE TABLE parking_spots (
    spot_id INT(11) NOT NULL AUTO_INCREMENT,
    location_id INT(11) NOT NULL,
    section_id INT(11) NOT NULL,
    spot_name VARCHAR(20) NOT NULL,
    spot_type VARCHAR(20) NOT NULL,
    status ENUM('vacant', 'occupied', 'maintenance', 'available') NOT NULL DEFAULT 'vacant',
    occupant VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (spot_id),
    FOREIGN KEY (location_id) REFERENCES parking_locations(location_id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    FOREIGN KEY (section_id) REFERENCES parking_sections(section_id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create bookings table
CREATE TABLE bookings (
    booking_id INT(11) NOT NULL AUTO_INCREMENT,
    booking_reference VARCHAR(20) NOT NULL,
    user_id INT(11),
    location_id INT(11) NOT NULL,
    spot_id INT(11) NOT NULL,
    car_number VARCHAR(20) NOT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    duration_hours INT(11) NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('active', 'complete', 'cancelled') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2),
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    payment_reference VARCHAR(100),
    payment_date TIMESTAMP NULL,
    PRIMARY KEY (booking_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    FOREIGN KEY (location_id) REFERENCES parking_locations(location_id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    FOREIGN KEY (spot_id) REFERENCES parking_spots(spot_id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert parking locations
INSERT INTO parking_locations (location_name, address, total_slots, available_slots, price_per_hour) VALUES
('Shahapur', 'Shahapur Main Road, Belagavi', 10, 10, 15.00),
('Vadagoan', 'Vadagoan Circle, Belagavi', 10, 10, 12.00),
('Tilakwadi', 'Tilakwadi Main Street, Belagavi', 10, 10, 18.00),
('Angol', 'Angol Junction, Belagavi', 10, 10, 10.00),
('Sadashiv nagar', 'Sadashiv nagar Complex, Belagavi', 10, 10, 20.00),
('Shivbasava nagar', 'Shivbasava nagar Market Area, Belagavi', 10, 10, 15.00),
('Neharu nagar', 'Neharu nagar Central, Belagavi', 10, 10, 12.00),
('Gandhi nagar', 'Gandhi nagar Plaza, Belagavi', 10, 10, 14.00);

-- Insert parking sections
INSERT INTO parking_sections (location_id, section_name, total_sports) VALUES
(1, 'A', 5), (1, 'B', 5),
(2, 'A', 5), (2, 'B', 5),
(3, 'A', 5), (3, 'B', 5),
(4, 'A', 5), (4, 'B', 5),
(5, 'A', 5), (5, 'B', 5),
(6, 'A', 5), (6, 'B', 5),
(7, 'A', 5), (7, 'B', 5),
(8, 'A', 5), (8, 'B', 5);

-- Insert parking spots
INSERT INTO parking_spots (location_id, section_id, spot_name, spot_type, status) VALUES
-- Shahapur
(1, 1, 'A1', 'standard', 'available'), (1, 1, 'A2', 'standard', 'available'), (1, 1, 'A3', 'standard', 'available'), (1, 1, 'A4', 'EV', 'available'), (1, 1, 'A5', 'premium', 'available'),
(1, 2, 'B1', 'standard', 'available'), (1, 2, 'B2', 'standard', 'available'), (1, 2, 'B3', 'standard', 'available'), (1, 2, 'B4', 'EV', 'available'), (1, 2, 'B5', 'premium', 'available'),
-- Vadagoan
(2, 3, 'A1', 'standard', 'available'), (2, 3, 'A2', 'standard', 'available'), (2, 3, 'A3', 'standard', 'available'), (2, 3, 'A4', 'EV', 'available'), (2, 3, 'A5', 'premium', 'available'),
(2, 4, 'B1', 'standard', 'available'), (2, 4, 'B2', 'standard', 'available'), (2, 4, 'B3', 'standard', 'available'), (2, 4, 'B4', 'EV', 'available'), (2, 4, 'B5', 'premium', 'available'),
-- Tilakwadi
(3, 5, 'A1', 'standard', 'available'), (3, 5, 'A2', 'standard', 'available'), (3, 5, 'A3', 'standard', 'available'), (3, 5, 'A4', 'EV', 'available'), (3, 5, 'A5', 'premium', 'available'),
(3, 6, 'B1', 'standard', 'available'), (3, 6, 'B2', 'standard', 'available'), (3, 6, 'B3', 'standard', 'available'), (3, 6, 'B4', 'EV', 'available'), (3, 6, 'B5', 'premium', 'available'),
-- Angol
(4, 7, 'A1', 'standard', 'available'), (4, 7, 'A2', 'standard', 'available'), (4, 7, 'A3', 'standard', 'available'), (4, 7, 'A4', 'EV', 'available'), (4, 7, 'A5', 'premium', 'available'),
(4, 8, 'B1', 'standard', 'available'), (4, 8, 'B2', 'standard', 'available'), (4, 8, 'B3', 'standard', 'available'), (4, 8, 'B4', 'EV', 'available'), (4, 8, 'B5', 'premium', 'available'),
-- Sadashiv nagar
(5, 9, 'A1', 'standard', 'available'), (5, 9, 'A2', 'standard', 'available'), (5, 9, 'A3', 'standard', 'available'), (5, 9, 'A4', 'EV', 'available'), (5, 9, 'A5', 'premium', 'available'),
(5, 10, 'B1', 'standard', 'available'), (5, 10, 'B2', 'standard', 'available'), (5, 10, 'B3', 'standard', 'available'), (5, 10, 'B4', 'EV', 'available'), (5, 10, 'B5', 'premium', 'available'),
-- Shivbasava nagar
(6, 11, 'A1', 'standard', 'available'), (6, 11, 'A2', 'standard', 'available'), (6, 11, 'A3', 'standard', 'available'), (6, 11, 'A4', 'EV', 'available'), (6, 11, 'A5', 'premium', 'available'),
(6, 12, 'B1', 'standard', 'available'), (6, 12, 'B2', 'standard', 'available'), (6, 12, 'B3', 'standard', 'available'), (6, 12, 'B4', 'EV', 'available'), (6, 12, 'B5', 'premium', 'available'),
-- Neharu nagar
(7, 13, 'A1', 'standard', 'available'), (7, 13, 'A2', 'standard', 'available'), (7, 13, 'A3', 'standard', 'available'), (7, 13, 'A4', 'EV', 'available'), (7, 13, 'A5', 'premium', 'available'),
(7, 14, 'B1', 'standard', 'available'), (7, 14, 'B2', 'standard', 'available'), (7, 14, 'B3', 'standard', 'available'), (7, 14, 'B4', 'EV', 'available'), (7, 14, 'B5', 'premium', 'available'),
-- Gandhi nagar
(8, 15, 'A1', 'standard', 'available'), (8, 15, 'A2', 'standard', 'available'), (8, 15, 'A3', 'standard', 'available'), (8, 15, 'A4', 'EV', 'available'), (8, 15, 'A5', 'premium', 'available'),
(8, 16, 'B1', 'standard', 'available'), (8, 16, 'B2', 'standard', 'available'), (8, 16, 'B3', 'standard', 'available'), (8, 16, 'B4', 'EV', 'available'), (8, 16, 'B5', 'premium', 'available');
