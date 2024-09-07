DELIMITER //

CREATE PROCEDURE create_address_for_contact(
    IN in_contact_id INT,
    IN in_address_line_01 VARCHAR(255),
    IN in_address_line_02 VARCHAR(255),
    IN in_city VARCHAR(255),
    IN in_state VARCHAR(255),
    IN in_zip_code VARCHAR(255)
)
SQL SECURITY DEFINER
BEGIN
    DECLARE new_address_id INT DEFAULT NULL;

    -- Error handler
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        -- Rollback the transaction in case of an error
        ROLLBACK;

        -- Return failure as a boolean-like value
        SELECT FALSE AS exit_status;
    END;

    -- Start transaction
    START TRANSACTION;

    -- Check if the address already exists (case-insensitive) with row lock
    SELECT id INTO new_address_id
    FROM cop4331_contact_manager.addresses
    WHERE LOWER(address_line_01) = LOWER(in_address_line_01)
        AND LOWER(address_line_02) = LOWER(in_address_line_02)
        AND LOWER(city) = LOWER(in_city)
        AND LOWER(state) = LOWER(in_state)
        AND LOWER(zip_code) = LOWER(in_zip_code)
    FOR UPDATE;

    IF new_address_id IS NOT NULL THEN
        -- Address exists, use the existing address ID
        UPDATE cop4331_contact_manager.contacts
        SET id_address = new_address_id
        WHERE id = in_contact_id;
    ELSE
        -- Address does not exist, create a new address
        INSERT INTO cop4331_contact_manager.addresses (
            address_line_01, address_line_02, city, state, zip_code
        ) VALUES (
            in_address_line_01, in_address_line_02, in_city, in_state, in_zip_code
        );

        -- Get the new address ID
        SET new_address_id = LAST_INSERT_ID();

        -- Assign the new address to the contact
        UPDATE cop4331_contact_manager.contacts
        SET id_address = new_address_id
        WHERE id = in_contact_id;
    END IF;

    -- Commit the transaction
    COMMIT;

    -- Return success as a boolean-like value
    SELECT TRUE AS exit_status;

END //

DELIMITER ;