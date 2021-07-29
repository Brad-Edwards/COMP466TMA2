CREATE TABLE sessions (
	session_id int NOT NULL AUTO_INCREMENT,
    course_id int NOT NULL,
    student_id int NOT NULL,
    start_date DATE NOT NULL,
    PRIMARY KEY (session_id),
    CONSTRAINT FK_sessions_courses_course_id FOREIGN KEY (course_id) REFERENCES courses(course_id),
    CONSTRAINT FK_sessions_student_id_sessions_student_id FOREIGN KEY (student_id) REFERENCES students(student_id)
);