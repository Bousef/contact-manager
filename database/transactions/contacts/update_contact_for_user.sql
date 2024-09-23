-- Drop the existing procedure if it exists
DROP PROCEDURE IF EXISTS update_contact_for_user;

DELIMITER //

CREATE PROCEDURE update_contact_for_user(
    IN in_user_id INT,
    IN in_contact_id INT,
    IN in_first_name VARCHAR(255),
    IN in_last_name VARCHAR(255),
    IN in_phone_number VARCHAR(20),
    IN in_email_address VARCHAR(255)
)
SQL SECURITY DEFINER
BEGIN
    DECLARE current_first_name VARCHAR(255);
    DECLARE current_last_name VARCHAR(255);
    DECLARE current_phone_number VARCHAR(20);
    DECLARE current_email_address VARCHAR(255);
    DECLARE exit_status BOOLEAN DEFAULT 1;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        -- Rollback the transaction in case of an error
        ROLLBACK;
        -- Set the status to false
        SET exit_status = 0;
    END;

    -- Start transaction
    START TRANSACTION;

    -- Retrieve current contact data
    SELECT first_name, last_name, phone_number, email_address
    INTO current_first_name, current_last_name, current_phone_number, current_email_address
    FROM cop4331_contact_manager.contacts
    WHERE id = in_contact_id AND id_user = in_user_id
    FOR UPDATE;

    -- Update the contact information with non-null input values
    UPDATE cop4331_contact_manager.contacts
    SET first_name = COALESCE(in_first_name, current_first_name),
        last_name = COALESCE(in_last_name, current_last_name),
        phone_number = COALESCE(in_phone_number, current_phone_number),
        email_address = COALESCE(in_email_address, current_email_address)
    WHERE id = in_contact_id AND id_user = in_user_id;

    -- Commit the transaction
    COMMIT;

    -- Return the update status
    SELECT exit_status AS exit_status;

END //

DELIMITER ;