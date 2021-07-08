CREATE TABLE course_grades (
	course_grade_id int NOT NULL AUTO_INCREMENT,
    course_id int NOT NULL,
    student_id int NOT NULL,
    grade float NOT NULL,
    PRIMARY KEY (course_grade_id),
    CONSTRAINT FK_course_grades_courses_course_id FOREIGN KEY (course_id) REFERENCES courses(course_id),
    CONSTRAINT FK_course_grades_students_student_id FOREIGN KEY (student_id) REFERENCES students(student_id)
);