DELIMITER //

CREATE PROCEDURE delete_address_for_contact(
    IN in_contact_id INT
)
BEGIN
    DECLARE current_address_id INT DEFAULT NULL;
    DECLARE address_count INT DEFAULT 0;

    -- Error handler
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN

        -- Rollback the transaction in case of an error
        ROLLBACK;

        -- Return failure as a boolean-like value
        SELECT FALSE AS exit_status;

        -- Exit the procedure after rollback
        RETURN;

    END;

    -- Start transaction
    START TRANSACTION;

    -- Get the current address ID for the given contact ID
    SELECT id_address INTO current_address_id
    FROM cop4331_contact_manager.contacts
    WHERE id = in_contact_id
    FOR UPDATE;

    -- Update the contact to set the address ID to NULL
    UPDATE cop4331_contact_manager.contacts
    SET id_address = NULL
    WHERE id = in_contact_id;

    -- Check if the address is used by any other contacts
    SELECT COUNT(*) INTO address_count
    FROM cop4331_contact_manager.contacts
    WHERE id_address = current_address_id;

    -- If no other contacts use the address, delete it
    IF address_count = 0 THEN

        DELETE FROM cop4331_contact_manager.addresses
        WHERE id = current_address_id;
        
    END IF;

    -- Commit the transaction
    COMMIT;

    -- Return success as a boolean-like value
    SELECT TRUE AS exit_status;

END //

DELIMITER ;