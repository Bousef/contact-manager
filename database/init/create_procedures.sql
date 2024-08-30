DELIMITER //

-- Create stored procedure to delete unused addresses
CREATE PROCEDURE delete_address_if_unused(IN address_id INT)
BEGIN
    DELETE FROM cop4331_contact_manager.addresses
    WHERE id = address_id
    AND status = 'active'
    AND NOT EXISTS (
        SELECT 1
        FROM cop4331_contact_manager.contacts
        WHERE id_address = address_id
    );
END //

DELIMITER ;