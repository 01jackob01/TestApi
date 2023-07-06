CREATE USER 'userwww'@'%' IDENTIFIED BY 'haslohaslo123';
GRANT ALL PRIVILEGES ON *.* TO 'userwww'@'%';
FLUSH PRIVILEGES;

CREATE DATABASE api_test;

USE api_test;

CREATE TABLE users
(
    `id`                    int               NOT NULL AUTO_INCREMENT,
    `login`                 varchar(50)      NOT NULL UNIQUE,
    `password`              varchar(255)      NOT NULL,
    `visible_password`      varchar(255)      NOT NULL,
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `active`                BOOLEAN,
    PRIMARY KEY (id)
);

CREATE TABLE orders
(
    `id`            int  NOT NULL AUTO_INCREMENT,
    `product_id` int  NOT NULL,
    `user_id`   int  NOT NULL,
    `quantity`  int  NOT NULL,
    `sum_price`  float  NOT NULL,
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

CREATE TABLE product
(
    `id`        int      NOT NULL AUTO_INCREMENT,
    `name`      varchar(255)      NOT NULL,
    `description`      varchar(255)  NOT NULL,
    `quantity`  int      NOT NULL,
    `price`     float      NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE session
(
    `id`        int      NOT NULL AUTO_INCREMENT,
    `user_id`   int     NOT NULL,
    `session_id` varchar(255) NOT NULL,
    `expires_at`TIMESTAMP NOT NULL,
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

CREATE TABLE api_keys
(
    `id`        int      NOT NULL AUTO_INCREMENT,
    `user_id`   int     NOT NULL,
    `api_key` varchar(255) NOT NULL,
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

INSERT INTO api_keys(id, user_id, api_key) VALUES (1, 1, 'f4a1ade57b8ba6d411bd7aa43142f30d148e7a154dfc2f4969d63e112f8d2974');
INSERT INTO users(id, login, password, visible_password, active) VALUES (1, 'test', '123f930c1bd849b96f8576871e738a97988db6b8544d400babf2df02bcc01941', 'test', 1);