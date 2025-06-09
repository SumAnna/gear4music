<?php

namespace App\Inventory;

use App\Helper\CurrencyHelper;
use App\Helper\TranslationHelper;
use Throwable;

class SimpleInventory implements InventoryInterface
{
    protected string $name;
    protected string $description;
    protected float $priceGBP;
    protected array $visibleCountries;
    protected string $type;
    protected string $condition;
    protected int $stock;

    public function __construct(array $data, array $visibleCountries)
    {
        $this->name = $data['name'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->priceGBP = (float)($data['price_gbp'] ?? 0);
        $this->visibleCountries = $visibleCountries;
        $this->type = $data['type'] ?? 'physical';
        $this->condition = $data['condition'] ?? 'new';
        $this->stock = (int)($data['stock'] ?? 0);
    }

    public function getName(string $lang): string
    {
        return TranslationHelper::safeTranslate($this->name ?? '', $lang);
    }

    public function getPrice(string $currency): float
    {
        try {
            return CurrencyHelper::convert($this->priceGBP ?? 0.0, 'GBP', $currency);
        } catch (Throwable $e) {
            return $this->priceGBP ?? 0.0;
        }
    }

    public function isVisibleIn(string $country): bool
    {
        return in_array($country, $this->visibleCountries ?? []);
    }

    public function getType(): string
    {
        return $this->type ?? 'N/A';
    }

    public function getCondition(): string
    {
        return $this->condition ?? 'N/A';
    }

    public function getStockLevel(): int
    {
        return $this->stock ?? 0;
    }
}
