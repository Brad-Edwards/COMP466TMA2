CREATE USER 'lms_server'@'localhost' IDENTIFIED BY 'COMP466!';
GRANT SELECT, INSERT, DELETE, UPDATE ON lms.* TO 'lms_server'@'localhost';