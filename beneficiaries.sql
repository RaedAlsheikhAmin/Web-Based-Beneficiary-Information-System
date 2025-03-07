-- Create the database
CREATE DATABASE IF NOT EXISTS beneficiary_system;
USE beneficiary_system;

CREATE TABLE IF NOT EXISTS beneficiaries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT,
    address VARCHAR(255),
    contact_info VARCHAR(100),
    needs_assessment TEXT
);


CREATE TABLE IF NOT EXISTS assistance_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    beneficiary_id INT,
    assistance_type VARCHAR(100),
    date_provided DATE,
    quantity INT,
    notes TEXT,
    FOREIGN KEY (beneficiary_id) REFERENCES beneficiaries(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Staff', 'Volunteer') DEFAULT 'Volunteer'
);

--  for predefined types of assistance
CREATE TABLE IF NOT EXISTS assistance_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(100) NOT NULL
);




--testing database
INSERT INTO beneficiaries (name, age, address, contact_info, needs_assessment) VALUES
('Ahmad Alhassan', 40, 'Aleppo, Salah Al-Din St', '963-995-123456', 'Requires monthly food assistance'),
('Mariam Qasem', 29, 'Damascus, Bab Touma', '963-944-567890', 'Needs temporary housing support'),
('Hassan Mustafa', 38, 'Homs, Khalid Ibn Walid St', '963-993-111222', 'Medical assistance required'),
('Layla Kareem', 33, 'Latakia, Al-Ziraa St', '963-992-334455', 'In need of child support services'),
('Omar Sulaiman', 45, 'Idlib, Al-Jalaa St', '963-991-667788', 'Requires support with utilities');


INSERT INTO assistance_records (beneficiary_id, assistance_type, date_provided, quantity, notes) VALUES
(7, 'Food', '2024-01-15', 5, 'Provided food packages to meet monthly needs'),
(7, 'Food', '2024-02-15', 5, 'Monthly food packages delivered'),
(7, 'Food', '2024-03-15', 5, 'Continued monthly food support'),
(8, 'Housing', '2024-01-20', 1, 'Arranged temporary shelter'),
(8, 'Housing', '2024-02-18', 1, 'Extended temporary housing support'),
(9, 'Medical', '2024-01-25', 3, 'Provided medical supplies'),
(9, 'Medical', '2024-02-25', 3, 'Supplied necessary medical equipment'),
(10, 'Child Support', '2024-01-30', 1, 'Childcare assistance for two children'),
(10, 'Child Support', '2024-02-28', 1, 'Extended childcare assistance'),
(11, 'Utilities', '2024-03-05', 2, 'Covered electricity bill for winter season'),
(11, 'Utilities', '2024-04-05', 2, 'Assisted with water bill'),
(7, 'Food', '2024-04-15', 5, 'Additional food support for Ramadan'),
(8, 'Housing', '2024-03-18', 1, 'Extended temporary housing assistance'),
(9, 'Medical', '2024-03-25', 3, 'Provided additional medical aid'),
(10, 'Child Support', '2024-03-30', 1, 'Additional child support services'),
(11, 'Utilities', '2024-05-05', 2, 'Gas bill assistance for heating needs');

