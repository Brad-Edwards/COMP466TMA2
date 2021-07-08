ALTER TABLE modules
ADD module_index int NOT NULL;

ALTER TABLE modules
ADD CONSTRAINT UK_modules_course_id_module_index UNIQUE KEY (course_id, module_index);