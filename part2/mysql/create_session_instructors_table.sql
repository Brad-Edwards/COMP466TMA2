CREATE TABLE session_instructors (
	session_instructor_id int NOT NULL AUTO_INCREMENT,
    session_id int NOT NULL,
    instructor_id int NOT NULL,
    PRIMARY KEY (session_instructor_id),
    CONSTRAINT FK_session_instructors_sessions_session_id FOREIGN KEY (session_id) REFERENCES sessions(session_id),
    CONSTRAINT FK_session_instructors_instructors_instructor_id FOREIGN KEY (instructor_id) REFERENCES instructors(instructor_id)
);

/*

Many-to-many table to instructors to sessions.

*/