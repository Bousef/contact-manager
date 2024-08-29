DELIMITER //

-- Trigger for AFTER UPDATE on contacts
CREATE TRIGGER after_update_contact
AFTER UPDATE ON cop4331_contact_manager.contacts
FOR EACH ROW
BEGIN
    -- Call the stored procedure with the old address ID if it was changed or set to NULL
    IF OLD.id_address IS NOT NULL AND (NEW.id_address IS NULL OR OLD.id_address <> NEW.id_address) THEN
        CALL delete_address_if_unused(OLD.id_address);
    END IF;
END //

-- Trigger for AFTER DELETE on contacts
CREATE TRIGGER after_delete_contact
AFTER DELETE ON cop4331_contact_manager.contacts
FOR EACH ROW
BEGIN
    -- Call the stored procedure with the old address ID
    CALL delete_address_if_unused(OLD.id_address);
END //

DELIMITER ;
