CREATE TABLE sections (
	section_id int AUTO_INCREMENT,
    course_id int NOT NULL,
    section_order int NOT NULL,
    title VARCHAR(100) NOT NULL,
    content MEDIUMTEXT,
    PRIMARY KEY (section_id),
    CONSTRAINT FK_sections_courses_course_id FOREIGN KEY (course_id) REFERENCES courses(course_id)
);