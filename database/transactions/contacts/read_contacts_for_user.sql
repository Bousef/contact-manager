DELIMITER //

CREATE PROCEDURE read_contacts_for_user(
    IN in_user_id INT NOT NULL,
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
        c.id_address
    FROM cop4331_contact_manager.contacts c
    JOIN cop4331_contact_manager.users_contacts uc ON c.id = uc.id_contact
    WHERE uc.id_user = in_user_id
    AND (
        c.first_name LIKE CONCAT('%', in_search_string, '%') OR
        c.last_name LIKE CONCAT('%', in_search_string, '%') OR
        CONCAT(c.first_name, ' ', c.last_name) LIKE CONCAT('%', in_search_string, '%')
    );

END //

DELIMITER ;