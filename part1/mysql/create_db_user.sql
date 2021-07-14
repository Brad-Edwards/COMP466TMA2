CREATE USER 'bookmarks_server'@'localhost' IDENTIFIED BY 'COMP466!';
GRANT SELECT, INSERT, DELETE, UPDATE ON bookmarksdb.* TO 'bookmarks_server'@'localhost';