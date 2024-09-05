DELIMITER //

CREATE PROCEDURE create_contact_for_user(
    IN in_user_id INT NOT NULL,
    IN in_first_name VARCHAR(255) NOT NULL,
    IN in_last_name VARCHAR(255) DEFAULT NULL,
    IN in_phone_number VARCHAR(20) DEFAULT NULL,
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

        -- Exit the procedure after rollback
        RETURN;

    END;

    -- Start transaction
    START TRANSACTION;

    -- Insert new contact
    INSERT INTO contacts (id_user, first_name, last_name, phone_number)
    VALUES (in_user_id, in_first_name, in_last_name, in_phone_number);

    -- Get the last inserted ID
    SET contact_id = LAST_INSERT_ID();

    -- Insert into users_contacts table
    INSERT INTO users_contacts (id_user, id_contact)
    VALUES (in_user_id, contact_id);

    -- Commit the transaction
    COMMIT;

    -- Return the last inserted ID
    SELECT contact_id AS contact_id;
    
END //

DELIMITER ;