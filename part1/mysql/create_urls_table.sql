CREATE TABLE urls (
	url_id int NOT NULL AUTO_INCREMENT,
    address VARCHAR(255) NOT NULL,
    PRIMARY KEY (url_id),
    CONSTRAINT UNIQUE_urls_address UNIQUE (address)
);