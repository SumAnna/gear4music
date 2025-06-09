<?php

use PHPUnit\Framework\TestCase;
use App\Inventory\ProductIndexer;

class ProductIndexerTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create products table (SQLite compatible)
        $this->pdo->exec("
            CREATE TABLE products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                sku TEXT UNIQUE,
                name TEXT NOT NULL,
                description TEXT,
                price_gbp REAL NOT NULL,
                type TEXT NOT NULL,
                condition TEXT NOT NULL,
                stock INTEGER NOT NULL
            );
        ");

        // Create product_visibility table
        $this->pdo->exec("
            CREATE TABLE product_visibility (
                product_id INTEGER NOT NULL,
                country TEXT NOT NULL,
                PRIMARY KEY (product_id, country),
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            );
        ");

        // Insert a sample product
        $this->pdo->exec("
            INSERT INTO products (sku, name, description, price_gbp, type, condition, stock)
            VALUES ('SKU001', 'Software', 'Digital software', 50.00, 'digital', 'new', 100);
        ");

        // Insert visibility for the product
        $this->pdo->exec("
            INSERT INTO product_visibility (product_id, country)
            VALUES (1, 'US');
        ");
    }

    public function testGetInventoryFiltersCorrectly()
    {
        $indexer = new ProductIndexer($this->pdo);
        $inventory = $indexer->getInventory('US', 'en', 'USD');

        $this->assertCount(1, $inventory);
        $this->assertSame('digital', $inventory[0]['type']);
        $this->assertSame('new', $inventory[0]['condition']);
    }
}
