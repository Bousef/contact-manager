DELIMITER //

CREATE PROCEDURE login_user(
    IN in_username VARCHAR(255),
    IN in_hashed_password VARCHAR(255)
)
SQL SECURITY DEFINER
BEGIN
    DECLARE out_user_id INT;
    DECLARE stored_hashed_password VARCHAR(255);
    DECLARE error_code INT DEFAULT 0;

    -- Declare an exit handler for SQL exceptions
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT NULL AS user_id, error_code AS error_code;
    END;

    -- Start transaction
    START TRANSACTION;

    -- Select user ID and stored hashed password based on the provided username
    SELECT 
        id,
        password
    INTO 
        out_user_id,
        stored_hashed_password
    FROM cop4331_contact_manager.users
    WHERE username = in_username;

    -- Check if user was found
    IF out_user_id IS NULL THEN
        SET error_code = 1; -- User not found
        ROLLBACK;
        SELECT NULL AS user_id, error_code AS error_code;
    ELSE
        -- Check if the provided hashed password matches the stored hashed password
        IF stored_hashed_password != in_hashed_password THEN
            SET error_code = 2; -- Incorrect password
            ROLLBACK;
            SELECT NULL AS user_id, error_code AS error_code;
        ELSE
            -- Commit transaction if everything is correct
            COMMIT;
            SELECT out_user_id AS user_id, error_code AS error_code;
        END IF;
    END IF;

END //

DELIMITER ;