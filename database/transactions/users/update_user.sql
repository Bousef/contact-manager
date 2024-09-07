DELIMITER //

CREATE PROCEDURE update_user(
    IN in_user_id INT,
    IN in_username VARCHAR(255),
    IN in_password VARCHAR(255),
    IN in_first_name VARCHAR(255),
    IN in_last_name VARCHAR(255)
)
SQL SECURITY DEFINER
BEGIN
    DECLARE current_username VARCHAR(255);
    DECLARE current_password VARCHAR(255);
    DECLARE current_first_name VARCHAR(255);
    DECLARE current_last_name VARCHAR(255);

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

    -- Get the current user data
    SELECT username, password, first_name, last_name INTO current_username, current_password, current_first_name, current_last_name
    FROM cop4331_contact_manager.users
    WHERE id = in_user_id
    FOR UPDATE;

    -- Update the user data, using current data if input is NULL
    UPDATE cop4331_contact_manager.users
    SET username = COALESCE(in_username, current_username),
        password = COALESCE(in_password, current_password),
        first_name = COALESCE(in_first_name, current_first_name),
        last_name = COALESCE(in_last_name, current_last_name)
    WHERE id = in_user_id;

    -- Commit the transaction
    COMMIT;

    -- Return success as a boolean-like value
    SELECT TRUE AS exit_status;

END //

DELIMITER ;