DELIMITER //

CREATE PROCEDURE read_contact_for_user(
    IN in_user_id INT NOT NULL,
    IN in_contact_id INT NOT NULL
)
SQL SECURITY DEFINER
BEGIN

    -- Select the contact data
    SELECT c.id AS contact_id,
           c.first_name,
           c.last_name,
           c.phone_number,
           a.address_line_01,
           a.address_line_02,
           a.city,
           a.state,
           a.zip_code
    FROM cop4331_contact_manager.contacts c
    LEFT JOIN cop4331_contact_manager.addresses a ON c.id_address = a.id
    INNER JOIN cop4331_contact_manager.users_contacts uc ON uc.id_contact = c.id
    WHERE c.id = in_contact_id AND uc.id_user = in_user_id;

END //

DELIMITER ;