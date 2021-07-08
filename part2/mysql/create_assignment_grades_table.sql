CREATE TABLE assignment_grades (
	assignment_grade_id int NOT NULL AUTO_INCREMENT,
    student_id int NOT NULL,
    assignment_id int NOT NULL,
    grade float NOT NULL,
    PRIMARY KEY (assignment_grade_id),
    CONSTRAINT FK_assignment_grades_students_student_id FOREIGN KEY (student_id) REFERENCES students(student_id),
    CONSTRAINT FK_assignment_grades_assignments_assignment_id FOREIGN KEY (assignment_id) REFERENCES assignments(assignment_id)
);