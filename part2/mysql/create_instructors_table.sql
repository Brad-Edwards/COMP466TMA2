CREATE TABLE instructors (
	instructor_id int NOT NULL AUTO_INCREMENT,
    user_id int NOT NULL,
    PRIMARY KEY (instructor_id),
    CONSTRAINT FK_instructors_users_user_id FOREIGN KEY (user_id) REFERENCES users(user_id),
    CONSTRAINT UNIQUE_instructors_user_id UNIQUE (user_id)
);