<!DOCTYPE html>
<html lang="<?= htmlspecialchars($data['lang']) ?>">
<head>
    <meta charset="UTF-8">
    <title>Inventory</title>
    <style>
        body { font-family: sans-serif; line-height: 1.5; margin: 2rem; }
        h1 { color: #333; }
        ul { padding-left: 1.5rem; }
        li { margin-bottom: 1rem; }
        .price { font-weight: bold; }
    </style>
</head>
<body>
<h1>
    Products available in <?= htmlspecialchars($data['country']) ?>
    (Language: <?= htmlspecialchars($data['lang']) ?>,
    Currency: <?= htmlspecialchars($data['currency']) ?>)
</h1>

<?php if (empty($data['products'])): ?>
    <p>No products available in this region.</p>
<?php else: ?>
    <ul>
        <?php foreach ($data['products'] as $product): ?>
            <li>
                <strong><?= htmlspecialchars($product['name']) ?></strong><br>
                <span class="price"><?= htmlspecialchars($data['currency']) ?> <?= htmlspecialchars($product['price']) ?></span><br>
                Type: <?= htmlspecialchars($product['type']) ?> |
                Condition: <?= htmlspecialchars($product['condition']) ?> |
                Stock: <?= htmlspecialchars($product['stock']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
</body>
</html>
