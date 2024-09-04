DELIMITER //

CREATE PROCEDURE create_address_for_contact(
    IN in_contact_id INT,
    IN in_address_line_01 VARCHAR(255),
    IN in_address_line_02 VARCHAR(255),
    IN in_city VARCHAR(255),
    IN in_state VARCHAR(255),
    IN in_zip_code VARCHAR(255)
)
BEGIN
    DECLARE address_id INT;

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

    -- Check if the address already exists (case-insensitive)
    SELECT id INTO address_id
    FROM cop4331_contact_manager.addresses
    WHERE LOWER(address_line_01) = LOWER(in_address_line_01)
        AND LOWER(address_line_02) = LOWER(in_address_line_02)
        AND LOWER(city) = LOWER(in_city)
        AND LOWER(state) = LOWER(in_state)
        AND LOWER(zip_code) = LOWER(in_zip_code)
        AND status = 'active';

    IF address_id IS NOT NULL THEN

        -- Address exists, update its status to 'pending'
        UPDATE cop4331_contact_manager.addresses
        SET status = 'pending'
        WHERE id = address_id;

    ELSE

        -- Address does not exist, insert a new address
        INSERT INTO cop4331_contact_manager.addresses (address_line_01, address_line_02, city, state, zip_code, status)
        VALUES (in_address_line_01, in_address_line_02, in_city, in_state, in_zip_code, 'pending');
        -- Get the ID of the newly inserted address
        SET address_id = LAST_INSERT_ID();
    END IF;

    -- Update the contact with the address ID
    UPDATE cop4331_contact_manager.contacts
    SET id_address = address_id
    WHERE id = in_contact_id;

    -- Update the status of the address to 'active'
    UPDATE cop4331_contact_manager.addresses
    SET status = 'active'
    WHERE id = address_id;

    -- Commit the transaction
    COMMIT;

    -- Return exit_status as a boolean-like value
    SELECT TRUE AS exit_status;

END //

DELIMITER ;