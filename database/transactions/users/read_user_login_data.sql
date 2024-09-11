DELIMITER //

CREATE PROCEDURE read_user_login_data(
    IN in_username VARCHAR(255)
)
SQL SECURITY DEFINER
BEGIN
    DECLARE out_user_id INT;
    DECLARE stored_hashed_password VARCHAR(255);

    -- Declare an exit handler for SQL exceptions
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT NULL AS user_id, NULL AS password;
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

    -- Commit transaction
    COMMIT;

    -- Return the user ID and stored hashed password
    SELECT out_user_id AS user_id, stored_hashed_password AS hashed_password;

END //

DELIMITER ;