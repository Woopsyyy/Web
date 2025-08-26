<?php
require __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = get_pdo();
    
    // Add tags column if it doesn't exist
    $pdo->exec("ALTER TABLE dishes ADD COLUMN IF NOT EXISTS tags VARCHAR(255) DEFAULT ''");
    
    // Update existing dishes with appropriate tags
    $updates = [
        "UPDATE dishes SET tags = 'main,chicken' WHERE category = 'main' AND (title LIKE '%chicken%' OR title LIKE '%Chicken%')",
        "UPDATE dishes SET tags = 'main,pork' WHERE category = 'main' AND (title LIKE '%pork%' OR title LIKE '%Pork%')",
        "UPDATE dishes SET tags = 'main,beef' WHERE category = 'main' AND (title LIKE '%beef%' OR title LIKE '%Beef%')",
        "UPDATE dishes SET tags = 'appetizer' WHERE category = 'appetizer'",
        "UPDATE dishes SET tags = 'dessert' WHERE category = 'dessert'",
        "UPDATE dishes SET tags = 'beverage,brewed-coffee' WHERE category = 'beverage' AND (title LIKE '%coffee%' OR title LIKE '%Coffee%' OR title LIKE '%americano%' OR title LIKE '%cappuccino%')",
        "UPDATE dishes SET tags = 'beverage,blended-beverage' WHERE category = 'beverage' AND (title LIKE '%blend%' OR title LIKE '%smoothie%' OR title LIKE '%shake%')",
        "UPDATE dishes SET tags = 'beverage,teavana-tea' WHERE category = 'beverage' AND (title LIKE '%tea%' OR title LIKE '%Tea%')",
        "UPDATE dishes SET tags = 'beverage,chocolate-more' WHERE category = 'beverage' AND (title LIKE '%chocolate%' OR title LIKE '%milk%' OR title LIKE '%hot chocolate%')"
    ];
    
    foreach ($updates as $update) {
        $pdo->exec($update);
    }
    
    // Set default tags for any remaining items
    $pdo->exec("UPDATE dishes SET tags = 'main' WHERE tags = '' AND category = 'main'");
    $pdo->exec("UPDATE dishes SET tags = 'beverage' WHERE tags = '' AND category = 'beverage'");
    $pdo->exec("UPDATE dishes SET tags = 'appetizer' WHERE tags = '' AND category = 'appetizer'");
    $pdo->exec("UPDATE dishes SET tags = 'dessert' WHERE tags = '' AND category = 'dessert'");
    
    // Get updated dish count
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM dishes');
    $totalDishes = $stmt->fetch()['total'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Database updated successfully with tags!',
        'total_dishes' => $totalDishes
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database update failed: ' . $e->getMessage()
    ]);
}
?>
