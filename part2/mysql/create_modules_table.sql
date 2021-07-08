CREATE TABLE modules (
	module_id int NOT NULL AUTO_INCREMENT,
    course_id int NOT NULL,
    module_name VARCHAR(100) NOT NULL,
    content MEDIUMTEXT,
    PRIMARY KEY (module_id),
    CONSTRAINT FK_modules_courses_course_id FOREIGN KEY (course_id) REFERENCES courses(course_id)
);