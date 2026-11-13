create table users(
    ID int unsigned auto_increment primary key,
    username varchar(50) unique not null,
    password_hash varchar(255) not null
);