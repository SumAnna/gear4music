# Gear4music Inventory System

A PHP-based inventory system for Gear4music, supporting:

- Multi-language product names via Google Translate API.
- Real-time currency conversion via Exchange Rates API.
- Per-country product visibility.
- Product types and conditions (physical/digital types; new/used/refurbished conditions).
- SQLite and MySQL compatible schema and data management.
- PHPUnit tests for core functionality.

---

## Requirements

- PHP 8.1 or higher
- Composer
- SQLite (for in-memory tests) or MySQL for production
- Internet access for API calls (Google Translate & Exchange Rates)

---

## Installation

1. Clone the repo:
```bash
git clone https://github.com/sumAnna/gear4music.git
cd gear4music
```

2. Install dependencies:
```bash
composer install
```

3. Copy .env.example to .env and configure your database credentials:
```bash
DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_NAME=gear_inventory
DB_USER=root
DB_PASS=secret
```
4. Setup the database schema and seed data:
```bash
mysql -u root -p gear_inventory < data/schema.sql
```
5. Run PHPUnit tests:
```bash
./vendor/bin/phpunit
```
---
## Usage
The ProductIndexer class fetches product data from the database and applies filters based on country, language, and currency. Products are instances of SimpleInventory or subclasses such as DigitalProduct or PhysicalProduct.

The ProductController demonstrates a basic web interface controller pulling filtered products and rendering via templates.
---
## Project Structure

- src/Inventory - Product and indexer classes
- src/Helper - Translation, currency, and database utilities
- tests/ - PHPUnit test cases
- data/ - SQL schema and seed files
- templates/ - PHP templates for rendering output

---
## Future Improvements

- Enhance error handling and logging.
- Add indexes for large data sets.
- Improve currency conversion by adding fallback providers or historical rates.
- Add digital and physical product-specific business logic to better separate concerns.
- Add explicit exception handling for Google Translate API failures to improve reliability.
- Make the UI more polished and user-friendly, improving the overall user experience.
- Add UI unit and integration tests for the frontend components.
