
-- Drop the existing procedure if it exists
DROP PROCEDURE IF EXISTS read_contact_for_user;

DELIMITER //

CREATE PROCEDURE read_contact_for_user(
    IN in_user_id INT,
    IN in_contact_id INT
)
SQL SECURITY DEFINER
BEGIN

    -- Select the contact data without the address information
    SELECT c.id AS contact_id,
        c.first_name,
        c.last_name,
        c.phone_number,
        c.email_address
    FROM cop4331_contact_manager.contacts c
    WHERE c.id = in_contact_id AND c.id_user = in_user_id;

END //

DELIMITER ;