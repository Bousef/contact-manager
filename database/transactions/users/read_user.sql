DELIMITER //

CREATE PROCEDURE read_user(
    IN in_user_id INT NOT NULL
)
SQL SECURITY DEFINER
BEGIN

    -- Select user data based on the provided user ID
    SELECT 
        id,
        username,
        password, -- Include the password column
        first_name,
        last_name,
        date_created,
        last_logged_in
    FROM cop4331_contact_manager.users
    WHERE id = in_user_id;

END //

DELIMITER ;