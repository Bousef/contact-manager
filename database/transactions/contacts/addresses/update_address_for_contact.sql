DELIMITER //

CREATE PROCEDURE update_address_for_contact(
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
    DECLARE current_address_id INT;
    DECLARE current_address_line_01 VARCHAR(255);
    DECLARE current_address_line_02 VARCHAR(255) DEFAULT "";
    DECLARE current_city VARCHAR(255);
    DECLARE current_state VARCHAR(255);
    DECLARE current_zip_code VARCHAR(255);
    DECLARE address_count INT DEFAULT 0;

    -- Error handler
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN

        -- Rollback the transaction in case of an error
        ROLLBACK;

        -- Return the status
        SELECT FALSE AS exit_status;

        -- Exit the procedure after rollback
        RETURN;
        
    END;

    -- Start transaction
    START TRANSACTION;

    -- Get the current address ID for the given contact ID and lock the row
    SELECT id_address INTO current_address_id
    FROM cop4331_contact_manager.contacts
    WHERE id = in_contact_id
    FOR UPDATE;

    -- Check if current_address_id is NULL
    IF current_address_id IS NULL THEN

        -- Rollback the transaction
        ROLLBACK;

        -- Return the status
        SELECT FALSE AS exit_status;
        RETURN;
        
    END IF;

    -- Get the current address details and lock the row
    SELECT address_line_01, address_line_02, city, state, zip_code
    INTO current_address_line_01, current_address_line_02, current_city, current_state, current_zip_code
    FROM cop4331_contact_manager.addresses
    WHERE id = current_address_id
    FOR UPDATE;

    -- Use existing values if input is NULL
    SET in_address_line_01 = COALESCE(in_address_line_01, current_address_line_01);
    SET in_address_line_02 = COALESCE(in_address_line_02, current_address_line_02);
    SET in_city = COALESCE(in_city, current_city);
    SET in_state = COALESCE(in_state, current_state);
    SET in_zip_code = COALESCE(in_zip_code, current_zip_code);

    -- Check if the new address already exists (case-insensitive)
    SELECT id INTO new_address_id
    FROM cop4331_contact_manager.addresses
    WHERE LOWER(address_line_01) = LOWER(in_address_line_01)
        AND LOWER(address_line_02) = LOWER(in_address_line_02)
        AND LOWER(city) = LOWER(in_city)
        AND LOWER(state) = LOWER(in_state)
        AND LOWER(zip_code) = LOWER(in_zip_code)
    LIMIT 1
    FOR UPDATE;

    -- Count the number of contacts using the current address
    SELECT COUNT(*) INTO address_count
    FROM cop4331_contact_manager.contacts
    WHERE id_address = current_address_id
    FOR UPDATE;

    IF new_address_id IS NOT NULL THEN
    
        -- Check if the current address is shared by other contacts
        IF address_count = 1 THEN

            -- Current address is not shared, delete the current address
            DELETE FROM cop4331_contact_manager.addresses
            WHERE id = current_address_id;

        END IF;

        -- Update the contact to use the new address ID
        UPDATE cop4331_contact_manager.contacts
        SET id_address = new_address_id
        WHERE id = in_contact_id;

    ELSE

        -- Check if the current address is shared by other contacts
        IF address_count > 1 THEN

            -- Current address is shared, create a new address
            INSERT INTO cop4331_contact_manager.addresses (address_line_01, address_line_02, city, state, zip_code)
            VALUES (in_address_line_01, in_address_line_02, in_city, in_state, in_zip_code);

            -- Get the new address ID
            SET new_address_id = LAST_INSERT_ID();

            -- Update the contact to use the new address ID
            UPDATE cop4331_contact_manager.contacts
            SET id_address = new_address_id
            WHERE id = in_contact_id;

        ELSE
        
            -- Current address is not shared, update the existing address
            UPDATE cop4331_contact_manager.addresses
            SET address_line_01 = in_address_line_01,
                address_line_02 = in_address_line_02,
                city = in_city,
                state = in_state,
                zip_code = in_zip_code
            WHERE id = current_address_id;

        END IF;

    END IF;

    -- Commit the transaction
    COMMIT;

    -- Return the status
    SELECT TRUE AS exit_status;

END //
DELIMITER ;