DELIMITER //

CREATE PROCEDURE delete_user(
    IN in_user_id INT
)
SQL SECURITY DEFINER
BEGIN

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

    -- Delete from users table
    DELETE FROM cop4331_contact_manager.users
    WHERE id = in_user_id;

    -- Commit the transaction
    COMMIT;

    -- Return success as a boolean-like value
    SELECT TRUE AS exit_status;

END //

DELIMITER ;