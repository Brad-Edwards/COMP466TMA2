CREATE TABLE users (
	user_id int NOT NULL AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (user_id),
    CONSTRAINT UNIQUE_users_username UNIQUE (username)
);