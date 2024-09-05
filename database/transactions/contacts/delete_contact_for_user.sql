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
    FROM cop4331_contact_manager.contacts
    WHERE id = in_contact_id
    FOR UPDATE;

    -- Delete from users_contacts table
    DELETE FROM cop4331_contact_manager.users_contacts
    WHERE id_user = in_user_id AND id_contact = in_contact_id;

    -- Delete from contacts table
    DELETE FROM cop4331_contact_manager.contacts
    WHERE id = in_contact_id;

    -- Commit the transaction
    COMMIT;

    -- Return success as a boolean-like value
    SELECT TRUE AS exit_status;

END //

DELIMITER ;