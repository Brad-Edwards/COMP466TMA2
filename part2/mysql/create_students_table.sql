CREATE TABLE students (
	student_id int NOT NULL,
    user_id int NOT NULL,
    PRIMARY KEY (student_id),
    CONSTRAINT FK_students_users_user_id FOREIGN KEY (user_id) REFERENCES users(user_id)
);