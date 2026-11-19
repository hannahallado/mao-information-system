CREATE DATABASE IF NOT EXISTS db_mao_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_mao_system;

CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name     VARCHAR(120) NOT NULL,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role          ENUM('technician','agriculturist') NOT NULL DEFAULT 'technician',
    sector        ENUM('crops','livestock_poultry','fisheries','all') NOT NULL DEFAULT 'all',
    is_active     TINYINT(1) NOT NULL DEFAULT 1,
    last_login_at DATETIME NULL,
    created_at    DATETIME NULL,
    updated_at    DATETIME NULL
) ENGINE=InnoDB;

-- Used by app/services/IdGenerator.php (shared Farmer / Program / Assistance IDs)
CREATE TABLE IF NOT EXISTS id_sequences (
    prefix      VARCHAR(10)    NOT NULL,
    year        SMALLINT       NOT NULL,
    last_number INT UNSIGNED   NOT NULL DEFAULT 0,
    PRIMARY KEY (prefix, year)
) ENGINE=InnoDB;

-- When you add the D1-D5 tables from the ERD, include these display-ID columns:
--   farmers.farmer_no            VARCHAR(20) NOT NULL UNIQUE   (FRM-2026-00001)
--   programs.code                VARCHAR(20) NOT NULL UNIQUE   (PRG-2026-001)
--   assistance_records.assistance_no VARCHAR(20) NOT NULL UNIQUE (AST-2026-00001)
