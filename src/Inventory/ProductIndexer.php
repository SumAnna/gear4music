<?php

namespace App\Inventory;

use PDO;

class ProductIndexer
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getInventory(string $country, string $lang = 'en', string $currency = 'GBP'): array
    {
        $products = [];

        foreach ($this->fetchInventory() as $product) {
            if ($product->isVisibleIn($country)) {
                $products[] = [
                    'name' => $product->getName($lang),
                    'price' => $product->getPrice($currency),
                    'type' => $product->getType(),
                    'condition' => $product->getCondition(),
                    'stock' => $product->getStockLevel(),
                ];
            }
        }

        return $products;
    }

    private function fetchInventory(): array
    {
        $stmt = $this->db->query("SELECT * FROM products");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $products = [];

        foreach ($rows as $row) {
            $visibleCountries = $this->getVisibleCountries((int)($row['id'] ?? 0));

            $productClass = match ($row['type'] ?? 'physical') {
                'digital' => DigitalProduct::class,
                'physical' => PhysicalProduct::class,
                default => SimpleInventory::class,
            };

            $products[] = new $productClass($row, $visibleCountries);
        }

        return $products;
    }

    private function getVisibleCountries(int $productId): array
    {
        $stmt = $this->db->prepare("SELECT country FROM product_visibility WHERE product_id = :id");
        $stmt->execute(['id' => $productId]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'country');
    }
}
