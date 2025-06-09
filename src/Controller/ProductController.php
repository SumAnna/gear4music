<?php

namespace App\Controller;

use App\Helper\Database;
use App\Inventory\ProductIndexer;

class ProductController
{
    private ProductIndexer $indexer;

    public function __construct(?ProductIndexer $indexer = null)
    {
        if ($indexer) {
            $this->indexer = $indexer;
        } else {
            $pdo = Database::connect();
            $this->indexer = new ProductIndexer($pdo);

        }
    }

    public function show(): void
    {
        $country = $_GET['country'] ?? 'GB';
        $lang = $_GET['lang'] ?? 'en';
        $currency = $_GET['currency'] ?? 'GBP';

        $products = $this->indexer->getInventory($country, $lang, $currency);

        $data = compact('country', 'lang', 'currency', 'products');

        require __DIR__ . '/../../templates/product_list.php';
    }
}
