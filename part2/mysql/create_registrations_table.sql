CREATE TABLE registrations (
	registration_id int NOT NULL AUTO_INCREMENT,
    student_id int NOT NULL,
    course_id int NOT NULL,
    registration_date DATE NOT NULL,
    PRIMARY KEY (registration_id),
    CONSTRAINT FK_registrations_students_student_id FOREIGN KEY (student_id) REFERENCES students(student_id),
    CONSTRAINT FK_registrations_courses_course_id FOREIGN KEY (course_id) REFERENCES courses(course_id),
    UNIQUE KEY UK_registrations_student_id_course_id_registration_date (student_id, course_id, registration_date)
);