-- Process 1.0 tables (D1). Run after schema_base.sql.
USE db_mao_system;

CREATE TABLE IF NOT EXISTS barangays (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name      VARCHAR(80) NOT NULL UNIQUE,
    psgc_code VARCHAR(20) NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS profiles (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    last_name       VARCHAR(60) NOT NULL,
    first_name      VARCHAR(60) NOT NULL,
    middle_name     VARCHAR(60) NULL,
    suffix          VARCHAR(10) NULL,
    sex             ENUM('Male','Female') NOT NULL,
    birthdate       DATE NULL,
    contact_no      VARCHAR(20) NULL,
    barangay_id     INT UNSIGNED NOT NULL,
    address_detail  VARCHAR(150) NULL,
    status          ENUM('active','inactive','transferred','deceased') NOT NULL DEFAULT 'active',
    name_key        VARCHAR(140) NOT NULL,
    created_by      INT UNSIGNED NULL,
    created_at      DATETIME NULL,
    updated_at      DATETIME NULL,
    deleted_at      DATETIME NULL,
    INDEX idx_profiles_name (last_name, first_name, birthdate),
    INDEX idx_profiles_name_key (name_key),
    INDEX idx_profiles_barangay (barangay_id),
    INDEX idx_profiles_status (status),
    CONSTRAINT fk_profiles_barangay FOREIGN KEY (barangay_id) REFERENCES barangays(id),
    CONSTRAINT fk_profiles_created_by FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS farming_profiles (
    id              INT UNSIGNED PRIMARY KEY,
    rsbsa_no        VARCHAR(30) NULL UNIQUE,
    status          ENUM('active','inactive','transferred','deceased') NOT NULL DEFAULT 'active',
    created_by      INT UNSIGNED NULL,
    created_at      DATETIME NULL,
    updated_at      DATETIME NULL,
    deleted_at      DATETIME NULL,
    INDEX idx_farming_status (status),
    CONSTRAINT fk_farming_profiles_profile FOREIGN KEY (id) REFERENCES profiles(id) ON DELETE CASCADE,
    CONSTRAINT fk_farming_profiles_created_by FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS fishery_profiles (
    id              INT UNSIGNED PRIMARY KEY,
    rsbsa_no        VARCHAR(30) NULL UNIQUE,
    fishr_no        VARCHAR(30) NULL UNIQUE,
    status          ENUM('active','inactive','transferred','deceased') NOT NULL DEFAULT 'active',
    created_by      INT UNSIGNED NULL,
    created_at      DATETIME NULL,
    updated_at      DATETIME NULL,
    deleted_at      DATETIME NULL,
    INDEX idx_fishery_status (status),
    CONSTRAINT fk_fishery_profiles_profile FOREIGN KEY (id) REFERENCES profiles(id) ON DELETE CASCADE,
    CONSTRAINT fk_fishery_profiles_created_by FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS audit_logs (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED NULL,
    action      ENUM('create','update','archive','override') NOT NULL,
    table_name  VARCHAR(60) NOT NULL,
    record_id   INT UNSIGNED NOT NULL,
    old_values  LONGTEXT NULL,
    new_values  LONGTEXT NULL,
    note        VARCHAR(255) NULL,
    created_at  DATETIME NOT NULL,
    INDEX idx_audit_record (table_name, record_id),
    INDEX idx_audit_user (user_id)
) ENGINE=InnoDB;
