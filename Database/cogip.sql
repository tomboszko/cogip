
CREATE DATABASE COGIP;

USE COGIP;

CREATE TABLE types (
    id int PRIMARY KEY,
    name varchar(50),
    created_at datetime,
    updated_at datetime
);

CREATE TABLE users (
    id int PRIMARY KEY,
    first_name varchar(50),
    role_id int,
    last_name varchar(50),
    email varchar(50),
    password varchar(50),
    created_at datetime,
    updated_at datetime
);

CREATE TABLE roles (
    id int PRIMARY KEY,
    name varchar(50),
    created_at datetime,
    updated_at datetime
);

CREATE TABLE permissions (
    id int PRIMARY KEY,
    name varchar(50),
    created_at datetime,
    update_at datetime
);

CREATE TABLE roles_permissions (
    id int PRIMARY KEY,
    permission_id int,
    role_id int
);

CREATE TABLE companies (
    id int PRIMARY KEY,
    name varchar(50),
    type_id int,
    country varchar(50),
    tva varchar(50),
    created_at datetime,
    updated_at datetime
);

CREATE TABLE contacts (
    id int PRIMARY KEY,
    name varchar(50),
    company_id int,
    email varchar(50),
    phone varchar(50),
    created_at datetime,
    updated_at datetime
);

CREATE TABLE invoices (
    id int PRIMARY KEY,
    ref varchar(50),
    id_company int,
    created_at datetime,
    updated_at datetime
);

ALTER TABLE users
ADD FOREIGN KEY (role_id) REFERENCES roles(id);

ALTER TABLE roles_permissions
ADD FOREIGN KEY (permission_id) REFERENCES permissions(id),
ADD FOREIGN KEY (role_id) REFERENCES roles(id);

ALTER TABLE companies
ADD FOREIGN KEY (type_id) REFERENCES types(id);

ALTER TABLE contacts
ADD FOREIGN KEY (company_id) REFERENCES companies(id);

ALTER TABLE invoices
ADD FOREIGN KEY (id_company) REFERENCES companies(id);
