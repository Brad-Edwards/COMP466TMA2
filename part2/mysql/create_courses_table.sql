CREATE TABLE courses (
	course_id int NOT NULL AUTO_INCREMENT,
    course_name VARCHAR(100) NOT NULL,
    course_code VARCHAR(20) NOT NULL,
    summary TEXT NOT NULL,
    introduction TEXT NOT NULL,
    content MEDIUMTEXT NOT NULL,
    PRIMARY KEY (course_id),
    CONSTRAINT UNIQUE_courses_course_name_course_code UNIQUE (course_name, course_code)
      
);