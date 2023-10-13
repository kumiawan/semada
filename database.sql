CREATE DATABASE php_login_semada;

CREATE DATABASE php_login_semada_test;

CREATE TABLE users (
    id VARCHAR(11) PRIMARY KEY ,
    nama VARCHAR(50) NOT NULL ,
    password varchar(255) NOT NULL
)ENGINE InnoDB;

CREATE TABLE sessions (
    id VARCHAR(255) NOT NULL ,
    user_id VARCHAR(255) NOT NULL
)ENGINE innoDB;

ALTER TABLE sessions
    ADD CONSTRAINT fk_sessions_user
    FOREIGN KEY (user_id)
    REFERENCES users(id);