CREATE TABLE courses (
	course_id int NOT NULL AUTO_INCREMENT,
    course_name VARCHAR(100) NOT NULL,
    course_code VARCHAR(20) NOT NULL,
    summary TEXT NOT NULL,
    introduction TEXT NOT NULL,
    PRIMARY KEY (course_id)
);