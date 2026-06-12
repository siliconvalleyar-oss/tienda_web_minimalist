CREATE DATABASE IF NOT EXISTS tienda_minimal;
USE tienda_minimal;

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password_hash VARCHAR(255) NOT NULL
);

INSERT INTO admins (username, password_hash)
SELECT 'admin', '\$2y\$10\$l3pI5HgWyo0KUOTcLgohJOcU1ciITg4OmqHi.C8Z67G3oc1ryikLa'
WHERE NOT EXISTS (SELECT 1 FROM admins WHERE username='admin');

CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT NOT NULL
);

INSERT INTO settings (setting_key, setting_value) VALUES
('primary_color', '#000000'),
('bg_color', '#ffffff'),
('text_color', '#111111'),
('whatsapp_number', '56912345678')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

CREATE TABLE IF NOT EXISTS chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visitor_name VARCHAR(100),
    message TEXT NOT NULL,
    response TEXT,
    forwarded_to_whatsapp BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
