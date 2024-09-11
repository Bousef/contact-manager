
-- Drop the existing procedure if it exists
DROP PROCEDURE IF EXISTS create_contact_for_user;

DELIMITER //

CREATE PROCEDURE create_contact_for_user(
    IN in_user_id INT,
    IN in_first_name VARCHAR(255),
    IN in_last_name VARCHAR(255),
    IN in_phone_number VARCHAR(20),
    IN in_email_address VARCHAR(255)
)
SQL SECURITY DEFINER
BEGIN
    DECLARE contact_id INT DEFAULT NULL;

    -- Error handler
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        -- Rollback the transaction in case of an error
        ROLLBACK;

        -- Return failure as a boolean-like value
        SELECT NULL AS contact_id;
    END;

    -- Start transaction
    START TRANSACTION;

    -- Insert new contact
    INSERT INTO cop4331_contact_manager.contacts (id_user, first_name, last_name, phone_number, email_address)
    VALUES (in_user_id, in_first_name, in_last_name, in_phone_number, in_email_address);

    -- Get the last inserted ID
    SET contact_id = LAST_INSERT_ID();

    -- Commit the transaction
    COMMIT;

    -- Return the last inserted ID
    SELECT contact_id AS contact_id;

END //

DELIMITER ;