-- Create products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price_gbp DECIMAL(10, 2) NOT NULL,
    type ENUM('digital', 'physical') NOT NULL,
    `condition` ENUM('new', 'used', 'refurbished') NOT NULL,
    stock INT NOT NULL
);

-- Create product visibility table
CREATE TABLE product_visibility (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    country CHAR(2) NOT NULL,
    PRIMARY KEY (product_id, country),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert sample products
INSERT INTO products (id, sku, name, description, price_gbp, type, `condition`, stock) VALUES
    (1, 'SKU001', 'Electric Guitar', 'A high-quality electric guitar', 100.00, 'physical', 'new', 10),
    (2, 'SKU002', 'Music Software', 'Professional music creation software', 79.99, 'digital', 'new', 999),
    (3, 'SKU003', 'Used Drum Kit', 'Pre-owned drum kit with minor wear', 59.50, 'physical', 'used', 2);

-- Insert visibility sample data
INSERT INTO product_visibility (product_id, country) VALUES
    (1, 'GB'),
    (1, 'FR'),
    (2, 'GB'),
    (2, 'FR'),
    (2, 'US'),
    (3, 'GB');
