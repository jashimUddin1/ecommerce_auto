ALTER TABLE orders ADD COLUMN reseller_id INT NULL AFTER user_id;
ALTER TABLE orders ADD CONSTRAINT fk_orders_reseller FOREIGN KEY (reseller_id) REFERENCES users(id);
ALTER TABLE orders ADD COLUMN assigned_by INT NULL;

CREATE TABLE IF NOT EXISTS withdrawals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO roles (name, description) VALUES
('reseller', 'Reseller account')
ON DUPLICATE KEY UPDATE description = VALUES(description);
