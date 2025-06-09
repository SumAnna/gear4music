<?php

use PHPUnit\Framework\TestCase;
use App\Inventory\SimpleInventory;

class SimpleInventoryTest extends TestCase
{
    public function testGettersReturnExpectedValues()
    {
        $data = [
            'name' => 'Electric Guitar',
            'description' => 'High-quality guitar',
            'price_gbp' => 100.0,
            'type' => 'physical',
            'condition' => 'new',
            'stock' => 10
        ];
        $visible = ['GB', 'US'];

        $product = new SimpleInventory($data, $visible);

        $this->assertSame('Electric Guitar', $product->getName('en'));
        $this->assertIsFloat($product->getPrice('GBP'));
        $this->assertTrue($product->isVisibleIn('GB'));
        $this->assertSame('physical', $product->getType());
        $this->assertSame('new', $product->getCondition());
        $this->assertSame(10, $product->getStockLevel());
    }
}
