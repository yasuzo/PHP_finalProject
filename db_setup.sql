CREATE DATABASE `ssp`
CHARACTER SET='utf8mb4' 
COLLATE='utf8mb4_general_ci';

USE `ssp`;

CREATE TABLE users(
    id          int AUTO_INCREMENT primary key,
    firstName   varchar(25) not null,
    lastName    varchar(25) not null,
    username    varchar(40) not null unique,
    pass        varchar(256) not null,
    permission  enum('regular', 'admin', 'superadmin') not null
);

CREATE TABLE news(
    id          int AUTO_INCREMENT primary key,
    title       varchar(50) not null,
    content     text not null,
    date_time   datetime default current_timestamp,
    created_by  int not null
);

ALTER TABLE news
ADD CONSTRAINT connection_news_user
FOREIGN KEY (created_by)
REFERENCES users (id)
ON DELETE CASCADE
ON UPDATE RESTRICT;