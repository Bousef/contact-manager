
-- Drop the existing procedure if it exists
DROP PROCEDURE IF EXISTS read_contacts_for_user;

DELIMITER //

CREATE PROCEDURE read_contacts_for_user(
    IN in_user_id INT,
    IN in_search_string VARCHAR(255)
)
SQL SECURITY DEFINER
BEGIN

    -- Select contacts based on the provided user ID and search string
    SELECT 
        c.id,
        c.first_name,
        c.last_name,
        c.phone_number,
        c.email_address,
        c.id_address
    FROM cop4331_contact_manager.contacts c
    WHERE c.id_user = in_user_id
    AND (
        c.first_name LIKE CONCAT('%', in_search_string, '%') OR
        c.last_name LIKE CONCAT('%', in_search_string, '%') OR
        CONCAT(c.first_name, ' ', c.last_name) LIKE CONCAT('%', in_search_string, '%')
    )
    LIMIT 24;

END //

DELIMITER ;
