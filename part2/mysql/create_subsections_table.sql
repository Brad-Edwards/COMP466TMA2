CREATE TABLE subsections (
	subsection_id int AUTO_INCREMENT,
    section_id int,
    parent_subsection_id int,
    subsection_order int NOT NULL,
    subsection_level int NOT NULL,
    title VARCHAR(100) NOT NULL,
    content MEDIUMTEXT,
    PRIMARY KEY (subsection_id),
    CONSTRAINT FK_subsections_sections_section_id FOREIGN KEY (section_id) REFERENCES sections(section_id),
    CONSTRAINT FK_subsections_subsections_subsection_ID FOREIGN KEY (parent_subsection_id) REFERENCES subsections(subsection_id)
);