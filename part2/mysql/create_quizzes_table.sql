CREATE TABLE quizzes (
	quiz_id int NOT NULL AUTO_INCREMENT,
    course_id int NOT NULL,
    quiz_name VARCHAR(100) NOT NULL,
    quiz_content MEDIUMTEXT,
    PRIMARY KEY (quiz_id),
    CONSTRAINT FK_quizzes_courses_course_id FOREIGN KEY (course_id) REFERENCES courses(course_id)
);