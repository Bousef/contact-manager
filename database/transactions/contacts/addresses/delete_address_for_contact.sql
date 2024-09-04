DELIMITER //

CREATE PROCEDURE remove_address_from_contact(
    IN in_contact_id INT
)
BEGIN

    -- Update the contact to set the address ID to NULL
    UPDATE cop4331_contact_manager.contacts
    SET id_address = NULL
    WHERE id = in_contact_id;

END //

DELIMITER ;