CREATE USER 'lms_server'@'localhost'
IDENTIFIED BY 'Comp482!!';
GRANT DELETE, INSERT, SELECT, UPDATE
ON lms.*
TO lms_server@localhost;