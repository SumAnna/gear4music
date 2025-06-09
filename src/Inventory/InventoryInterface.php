<?php

namespace App\Inventory;

interface InventoryInterface
{
    public function getName(string $lang): string;
    public function getPrice(string $currency): float;
    public function isVisibleIn(string $country): bool;
    public function getType(): string;
    public function getCondition(): string;
    public function getStockLevel(): int;
}
