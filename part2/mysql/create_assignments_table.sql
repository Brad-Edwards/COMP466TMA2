CREATE TABLE assignments (
	assignment_id int NOT NULL AUTO_INCREMENT,
    course_id int NOT NULL,
    assignment_name VARCHAR(100) NOT NULL,
    assignment_content MEDIUMTEXT,
    PRIMARY KEY (assignment_id),
    CONSTRAINT FK_assignments_courses_course_id FOREIGN KEY (course_id) REFERENCES courses(course_id)
);