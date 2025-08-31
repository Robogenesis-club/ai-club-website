
-- Databases
CREATE DATABASE IF NOT EXISTS robogenadmin CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
CREATE DATABASE IF NOT EXISTS member CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Tables for robogenadmin
USE robogenadmin;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS core (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tables for member DB
USE member;

CREATE TABLE IF NOT EXISTS personal (
  id INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(255) NOT NULL,
  bennettid VARCHAR(100) NOT NULL UNIQUE,
  number VARCHAR(50),
  accomodation VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert a sample admin into core (password is 'admin123' hashed using PHP's password_hash)
-- If importing via phpMyAdmin, you can run the following PHP script on the server to create the admin with a hashed password.
