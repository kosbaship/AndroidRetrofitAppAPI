CREATE TABLE users (
    id int NOT NULL AUTO_INCREMENT,
    email varchar(200) NOT NULL,
    password text NOT NULL,
    name varchar(500) NOT NULL,
    school varchar(1000) NOT NULL,
    CONSTRAINT users_pk PRIMARY KEY (id)
);


INSERT INTO users (email, password, name, school) VALUES (? ,? ,? ,? )


SELECT id, email, name, school FROM users WHERE email = ?
