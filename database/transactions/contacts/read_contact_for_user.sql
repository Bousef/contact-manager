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
        c.phone_number
    FROM cop4331_contact_manager.contacts c
    INNER JOIN cop4331_contact_manager.users_contacts uc ON uc.id_contact = c.id
    WHERE c.id = in_contact_id AND uc.id_user = in_user_id;

END //

DELIMITER ;