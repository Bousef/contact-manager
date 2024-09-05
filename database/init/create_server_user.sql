DROP USER IF EXISTS 'bitnami'@'%';

CREATE USER 'bitnami'@'%' IDENTIFIED BY 'X7g9#4vZ1$2cQ5';

GRANT EXECUTE ON cop4331_contact_manager.* TO 'bitnami'@'%';

FLUSH PRIVILEGES;