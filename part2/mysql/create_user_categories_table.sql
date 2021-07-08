CREATE TABLE user_categories (
	user_category_id int NOT NULL AUTO_INCREMENT,
    category_name VARCHAR(50) NOT NULL UNIQUE,
    PRIMARY KEY (user_category_id)
);

/*

For differentiating between students and instructors or other future roles.

*/