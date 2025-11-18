<?php
require_once "dbcontroller.php";

$db = new dbcontroller();

echo "<h2>Testing Database Queries</h2>";

echo "<h3>1. Products with images:</h3>";
$products = $db->getALL("SELECT id, nama_produk, gambar FROM product LIMIT 5");
echo "<pre>";
print_r($products);
echo "</pre>";

echo "<h3>2. Recent templates structure:</h3>";
$recent = $db->getALL("
    SELECT tr.*, p.nama_produk, p.gambar, p.link
    FROM template_recent tr
    JOIN product p ON tr.product_id = p.id
    LIMIT 5
");
echo "<pre>";
print_r($recent);
echo "</pre>";

echo "<h3>3. User templates structure:</h3>";
$userTemplates = $db->getALL("
    SELECT ut.*, p.nama_produk, p.gambar
    FROM user_templates ut
    JOIN product p ON ut.product_id = p.id
    LIMIT 5
");
echo "<pre>";
print_r($userTemplates);
echo "</pre>";

echo "<h3>4. Check image files:</h3>";
if ($products) {
    foreach ($products as $p) {
        $images = explode(',', $p['gambar']);
        $img = trim($images[0]);
        $pathOld = "barang/" . $img;
        $pathNew = "public/images/produk/" . $img;
        echo "Product: {$p['nama_produk']}<br>";
        echo "Image: {$img}<br>";
        echo "Old Path (barang/): {$pathOld} - " . (file_exists($pathOld) ? "YES ✓" : "NO ✗") . "<br>";
        echo "New Path (public/images/produk/): {$pathNew} - " . (file_exists($pathNew) ? "YES ✓" : "NO ✗") . "<br><br>";
    }
}
