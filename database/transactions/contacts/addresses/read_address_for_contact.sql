DELIMITER //

CREATE PROCEDURE read_address_for_contact(
    IN in_contact_id INT
)
BEGIN

    -- Get the address details for the given contact ID
    SELECT a.address_line_01, a.address_line_02, a.city, a.state, a.zip_code
    FROM cop4331_contact_manager.contacts c
    JOIN cop4331_contact_manager.addresses a ON c.id_address = a.id
    WHERE c.id = in_contact_id;
    
END //

DELIMITER ;