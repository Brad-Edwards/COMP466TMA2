CREATE TABLE instructors (
	instructor_id int NOT NULL,
    user_id int NOT NULL,
    PRIMARY KEY (instructor_id),
    CONSTRAINT FK_instructors_users_user_id FOREIGN KEY (user_id) REFERENCES users(user_id)
);