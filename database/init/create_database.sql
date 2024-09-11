
-- Create the database
CREATE DATABASE IF NOT EXISTS cop4331_contact_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Create 'addresses' table
CREATE TABLE IF NOT EXISTS cop4331_contact_manager.addresses 
(
    id INT AUTO_INCREMENT,
    address_line_01 VARCHAR(255) NOT NULL,
    address_line_02 VARCHAR(255),
    city VARCHAR(255) NOT NULL,
    state VARCHAR(255) NOT NULL,
    zip_code VARCHAR(255) NOT NULL,
    CONSTRAINT unique_addresses UNIQUE (address_line_01, address_line_02, city, state, zip_code),
    PRIMARY KEY (id)
);

-- Create 'users' table
CREATE TABLE IF NOT EXISTS cop4331_contact_manager.users 
(
    id INT AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL, -- Secure hashed password
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) DEFAULT NULL,
    CONSTRAINT unique_users_username UNIQUE (username),
    PRIMARY KEY (id)
);

-- Create 'contacts' table
CREATE TABLE IF NOT EXISTS cop4331_contact_manager.contacts 
(
    id INT AUTO_INCREMENT,
    id_user INT NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) DEFAULT NULL,
    phone_number VARCHAR(255) DEFAULT NULL,
    email_address VARCHAR(255) DEFAULT NULL,
    id_address INT DEFAULT NULL,
    CONSTRAINT fk_contacts_id_user FOREIGN KEY (id_user) 
        REFERENCES cop4331_contact_manager.users(id) 
        ON DELETE CASCADE,
    CONSTRAINT fk_contacts_id_address FOREIGN KEY (id_address) 
        REFERENCES cop4331_contact_manager.addresses(id) 
        ON DELETE SET NULL,
    PRIMARY KEY (id)
);
