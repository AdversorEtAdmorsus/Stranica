CREATE DATABASE IF NOT EXISTS elconfidencial CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE elconfidencial;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



INSERT INTO users (username, password) VALUES ('mlukiski', '$2y$10$uKnzbOPVajptlbJEtk1Vee.XcFvmanzk.EBOjlDM9zKijB/ek3xVq');

CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    about VARCHAR(50) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    category VARCHAR(50) NOT NULL,
    author VARCHAR(50) NOT NULL,
    archive TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
