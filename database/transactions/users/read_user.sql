
-- Drop the existing procedure if it exists
DROP PROCEDURE IF EXISTS read_user;

DELIMITER //

CREATE PROCEDURE read_user(
    IN in_user_id INT
)
SQL SECURITY DEFINER
BEGIN

    -- Select user data based on the provided user ID
    SELECT 
        id,
        username,
        first_name,
        last_name
    FROM cop4331_contact_manager.users
    WHERE id = in_user_id;

END //

DELIMITER ;