-- Database: medical_system_normalized
CREATE DATABASE IF NOT EXISTS `medical_system`;
USE `medical_system`;

-- Table: provinces
CREATE TABLE provinces (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

-- Table: cities
CREATE TABLE cities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  province_id INT,
  FOREIGN KEY (province_id) REFERENCES provinces(id)
);

-- Table: genders
CREATE TABLE genders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name ENUM('Male', 'Female', 'Other') NOT NULL
);

-- Table: specializations
CREATE TABLE specializations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

-- Table: appointment_statuses
CREATE TABLE appointment_statuses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  status ENUM('Pending', 'Accepted', 'Declined') NOT NULL
);

-- Table: patients
CREATE TABLE patients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  phone VARCHAR(20),
  gender_id INT,
  age INT,
  address TEXT,
  password VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (gender_id) REFERENCES genders(id)
);

-- Table: doctors
CREATE TABLE doctors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255),
  password VARCHAR(255),
  phone VARCHAR(20),
  specialization_id INT,
  city_id INT,
  hospital_name VARCHAR(150),
  image VARCHAR(255),
  days VARCHAR(100),
  timing VARCHAR(100),
  experience VARCHAR(100),
  degree VARCHAR(255),
  description TEXT,
  status ENUM('pending', 'approved') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  location VARCHAR(255),
  address TEXT,
  is_notified TINYINT(1) DEFAULT 0,
  changes_pending TINYINT(1) DEFAULT 0,
  FOREIGN KEY (specialization_id) REFERENCES specializations(id),
  FOREIGN KEY (city_id) REFERENCES cities(id)
);

-- Table: appointments
CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT,
  doctor_id INT,
  specialization_id INT,
  appointment_date DATE,
  appointment_time TIME,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status_id INT DEFAULT 1,
  is_notified_patient TINYINT(1) DEFAULT 0,
  is_notified_admin TINYINT(1) DEFAULT 0,
  FOREIGN KEY (patient_id) REFERENCES patients(id),
  FOREIGN KEY (doctor_id) REFERENCES doctors(id),
  FOREIGN KEY (specialization_id) REFERENCES specializations(id),
  FOREIGN KEY (status_id) REFERENCES appointment_statuses(id)
);

-- Table: feedback
CREATE TABLE feedback (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100),
  subject VARCHAR(150),
  message TEXT,
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: diseases
CREATE TABLE diseases (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  image VARCHAR(255),
  reviewed_date DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: medical_news
CREATE TABLE medical_news (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  author VARCHAR(100) NOT NULL,
  image VARCHAR(255),
  likes INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
