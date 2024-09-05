DELIMITER //

CREATE PROCEDURE update_contact_for_user(
    IN in_user_id INT NOT NULL,
    IN in_contact_id INT NOT NULL,
    IN in_first_name VARCHAR(255),
    IN in_last_name VARCHAR(255),
    IN in_phone_number VARCHAR(20)
)
SQL SECURITY DEFINER
BEGIN
    DECLARE current_first_name VARCHAR(255);
    DECLARE current_last_name VARCHAR(255);
    DECLARE current_phone_number VARCHAR(20);

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

    -- Get the current contact data
    SELECT first_name, last_name, phone_number INTO current_first_name, current_last_name, current_phone_number
    FROM cop4331_contact_manager.contacts
    WHERE id = in_contact_id
    FOR UPDATE;

    -- Update the contact data, using current data if input is NULL
    UPDATE cop4331_contact_manager.contacts
    SET first_name = COALESCE(in_first_name, current_first_name),
        last_name = COALESCE(in_last_name, current_last_name),
        phone_number = COALESCE(in_phone_number, current_phone_number)
    WHERE id = in_contact_id;

    -- Commit the transaction
    COMMIT;

    -- Return success as a boolean-like value
    SELECT TRUE AS exit_status;

END //

DELIMITER ;