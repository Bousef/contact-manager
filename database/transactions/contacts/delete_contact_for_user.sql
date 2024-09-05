DELIMITER //

CREATE PROCEDURE delete_contact_for_user(
    IN in_user_id INT NOT NULL,
    IN in_contact_id INT NOT NULL
)
SQL SECURITY DEFINER
BEGIN
    DECLARE current_address_id INT DEFAULT NULL;

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

    -- Get the address ID of the contact
    SELECT address_id INTO current_address_id
    FROM contacts
    WHERE id = in_contact_id
    FOR UPDATE;

    -- Delete from users_contacts table
    DELETE FROM users_contacts
    WHERE id_user = in_user_id AND id_contact = in_contact_id;

    -- Delete from contacts table
    DELETE FROM contacts
    WHERE id = in_contact_id;

    -- Delete the contact's address if it is not used by any other contacts
    IF current_address_id IS NOT NULL THEN
        DELETE FROM addresses
        WHERE id = current_address_id
        AND NOT EXISTS (SELECT 1 FROM contacts WHERE address_id = current_address_id);
    END IF;

    -- Commit the transaction
    COMMIT;

    -- Return success as a boolean-like value
    SELECT TRUE AS exit_status;

END //

DELIMITER ;