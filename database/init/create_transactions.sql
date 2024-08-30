DELIMITER //

-- Create stored procedure to handle the transaction
CREATE PROCEDURE create_address_for_contact(
    IN contact_id INT,
    IN address_line_01 VARCHAR(255),
    IN address_line_02 VARCHAR(255),
    IN city VARCHAR(255),
    IN state VARCHAR(255),
    IN zip_code VARCHAR(255),
    OUT new_address_id INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        -- Rollback the transaction in case of an error
        ROLLBACK;
        -- Set the new_address_id to NULL to indicate failure
        SET new_address_id = NULL;
    END;

    -- Start transaction
    START TRANSACTION;

    -- Insert new address with 'pending' status
    INSERT INTO cop4331_contact_manager.addresses (address_line_01, address_line_02, city, state, zip_code, status)
    VALUES (address_line_01, address_line_02, city, state, zip_code, 'pending');

    -- Get the ID of the newly inserted address
    SET new_address_id = LAST_INSERT_ID();

    -- Update the contact with the new address ID
    UPDATE cop4331_contact_manager.contacts
    SET id_address = new_address_id
    WHERE id = contact_id;

    -- Update the status of the new address to 'active'
    UPDATE cop4331_contact_manager.addresses
    SET status = 'active'
    WHERE id = new_address_id;

    -- Commit the transaction
    COMMIT;
END //

DELIMITER ;