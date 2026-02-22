
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reset_token VARCHAR(255) NULL,
    token_expiry DATETIME NULL
);


CREATE TABLE cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    make VARCHAR(50) NOT NULL, -- e.g., Toyota
    model VARCHAR(50) NOT NULL, -- e.g., Camry
    year INT NOT NULL,
    type VARCHAR(50) NOT NULL, -- e.g., SUV, Sedan, Taxi
    price_per_day DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    description TEXT,
    is_available BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (car_id) REFERENCES cars(id)
);


INSERT INTO cars (make, model, year, type, price_per_day, image, description) VALUES
('Toyota', 'Camry', 2023, 'Sedan', 50.00, 'https://images.unsplash.com/photo-1621007947382-bb3c3968e3bb?auto=format&fit=crop&w=500&q=60', 'Comfortable sedan for city driving.'),
('Ford', 'Mustang', 2022, 'Sports', 120.00, 'https://images.unsplash.com/photo-1584345604476-8ec5e12e42dd?auto=format&fit=crop&w=500&q=60', 'Experience the thrill of speed.'),
('Mercedes', 'V-Class', 2023, 'Luxury', 200.00, 'https://images.unsplash.com/photo-1617788138017-80ad40651399?auto=format&fit=crop&w=500&q=60', 'Luxury ride for VIPs.');



-- Password is 'admin123'
INSERT INTO users (name, email, password, role) 
VALUES ('Super Admin', 'admin@carlink.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

ALTER TABLE users ADD COLUMN wallet_balance DECIMAL(10, 2) DEFAULT 0.00;
ALTER TABLE users ADD COLUMN referral_code VARCHAR(20) UNIQUE;


CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    type ENUM('deposit', 'withdrawal', 'payment') DEFAULT 'deposit',
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);






CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (car_id) REFERENCES cars(id),
    UNIQUE KEY unique_booking_review (booking_id)
);


ALTER TABLE cars ADD COLUMN average_rating DECIMAL(3,2) DEFAULT 0.00;
ALTER TABLE cars ADD COLUMN total_reviews INT DEFAULT 0;


ALTER TABLE users ADD COLUMN user_rating DECIMAL(3,2) DEFAULT 0.00;
ALTER TABLE users ADD COLUMN total_user_reviews INT DEFAULT 0;
