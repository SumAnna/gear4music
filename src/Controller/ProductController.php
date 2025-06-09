<?php

namespace App\Controller;

use App\Helper\Database;
use App\Inventory\ProductIndexer;
use Alcohol\ISO4217;

class ProductController
{
    // Validation patterns for input formats
    private const VALID_COUNTRY_PATTERN = '/^[A-Z]{2}$/';
    private const VALID_LANG_PATTERN = '/^[a-z]{2}$/i';
    private const VALID_CURRENCY_PATTERN = '/^[A-Z]{3}$/';

    private ProductIndexer $indexer;
    private array $allowedCountries = [];
    private array $allowedCurrencies = [];
    private array $allowedLangs = [];

    public function __construct(?ProductIndexer $indexer = null)
    {
        $this->loadAllowedCountryCodes();
        $this->loadAllowedLanguageCodes();
        $this->loadAllowedCurrencyCodes();

        $this->indexer = $indexer ?? new ProductIndexer(Database::connect());
    }

    /**
     * Load valid ISO 3166-1 alpha-2 country codes from umpirsky package.
     */
    private function loadAllowedCountryCodes(): void
    {
        $file = __DIR__ . '/../../vendor/umpirsky/country-list/data/en/country.json';
        if (is_readable($file)) {
            $this->allowedCountries = array_keys(json_decode(file_get_contents($file), true));
        }
    }

    /**
     * Load valid ISO 639-1 language codes from umpirsky package.
     */
    private function loadAllowedLanguageCodes(): void
    {
        $file = __DIR__ . '/../../vendor/umpirsky/language-list/data/en/language.json';
        if (is_readable($file)) {
            $this->allowedLangs = array_keys(json_decode(file_get_contents($file), true));
        }
    }

    /**
     * Load valid ISO 4217 currency codes using alcohol/iso4217.
     */
    private function loadAllowedCurrencyCodes(): void
    {
        $iso = new ISO4217();
        $this->allowedCurrencies = array_column($iso->getAll(), 'alpha3');
    }

    /**
     * Safely retrieves a GET parameter, validates format and allowed list.
     */
    private function getSafeParam(
        string $key,
        string $default,
        string $pattern,
        bool $toUpper = true,
        array $whitelist = []
    ): string {
        if (!isset($_GET[$key])) {
            return $default;
        }

        $original = trim($_GET[$key]);
        $value = $toUpper ? strtoupper($original) : strtolower($original);

        if (!preg_match($pattern, $value)) {
            return $default;
        }

        if ($whitelist && !in_array($value, $whitelist, true)) {
            return $default;
        }

        return $value;
    }

    /**
     * Main entry point to display product inventory based on validated input.
     */
    public function show(): void
    {
        $country = $this->getSafeParam('country', 'GB', self::VALID_COUNTRY_PATTERN, true, $this->allowedCountries);
        $lang = $this->getSafeParam('lang', 'en', self::VALID_LANG_PATTERN, false, $this->allowedLangs);
        $currency = $this->getSafeParam('currency', 'GBP', self::VALID_CURRENCY_PATTERN, true, $this->allowedCurrencies);

        $products = $this->indexer->getInventory($country, $lang, $currency);

        $data = compact('country', 'lang', 'currency', 'products');

        require __DIR__ . '/../../templates/product_list.php';
    }
}
