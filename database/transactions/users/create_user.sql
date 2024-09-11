
-- Drop the existing procedure if it exists
DROP PROCEDURE IF EXISTS create_user;

DELIMITER //

CREATE PROCEDURE create_user(
    IN in_username VARCHAR(255),
    IN in_password VARCHAR(255), -- Secure hashed password
    IN in_first_name VARCHAR(255),
    IN in_last_name VARCHAR(255)
)
SQL SECURITY DEFINER
BEGIN
    DECLARE new_user_id INT DEFAULT NULL;

    -- Error handler
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        -- Rollback the transaction in case of an error
        ROLLBACK;

        -- Return failure as a boolean-like value
        SELECT NULL AS user_id;
    END;

    -- Start transaction
    START TRANSACTION;

    -- Insert the new user into the users table
    INSERT INTO cop4331_contact_manager.users (
        username, password, first_name, last_name
    ) VALUES (
        in_username, in_password, in_first_name, in_last_name
    );

    -- Get the new user ID
    SET new_user_id = LAST_INSERT_ID();

    -- Commit the transaction
    COMMIT;

    -- Return the new user ID and success as a boolean-like value
    SELECT new_user_id AS user_id;

END //

DELIMITER ;