
-- Insert test data into 'users' table
INSERT INTO cop4331_contact_manager.users (username, password, first_name, last_name)
VALUES 
('johndoe', 'hashedpassword1', 'John', 'Doe'),
('janedoe', 'hashedpassword2', 'Jane', 'Doe'),
('alice', 'hashedpassword3', 'Alice', 'Smith');

-- Insert test data into 'contacts' table
INSERT INTO cop4331_contact_manager.contacts (id_user, first_name, last_name, phone_number)
VALUES 
(1, 'Michael', 'Johnson', '555-1234'),
(1, 'Sarah', 'Connor', '555-5678'),
(2, 'Emily', 'Davis', '555-8765'),
(3, 'David', 'Brown', '555-4321');
