CREATE TABLE users (
	user_id int NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    passwd VARCHAR(50) NOT NULL,
    user_category_id int NOT NULL,
    PRIMARY KEY (user_id),
    CONSTRAINT FK_users_user_categories_user_category_id FOREIGN KEY (user_category_id) REFERENCES user_categories(user_category_id)
);

/* 
Usernames must be unique for login purposes.
*/