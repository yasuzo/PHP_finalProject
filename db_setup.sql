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

