DELIMITER //

-- Create stored procedure to delete unused addresses
CREATE PROCEDURE delete_address_if_unused(IN address_id INT)
BEGIN
    IF address_id IS NOT NULL THEN
        IF NOT EXISTS (
            SELECT 1
            FROM cop4331_contact_manager.contacts
            WHERE id_address = address_id
        ) THEN
            DELETE FROM cop4331_contact_manager.addresses
            WHERE id = address_id;
        END IF;
    END IF;
END //

DELIMITER ;
