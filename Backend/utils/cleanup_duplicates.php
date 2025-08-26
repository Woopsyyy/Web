<?php
require __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');

// Check admin authentication first
$user = current_user_or_null();
if (!$user || $user['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Admin access required']);
    exit;
}

try {
    $pdo = get_pdo();
    
    // Find duplicates
    $stmt = $pdo->query('
        SELECT title, COUNT(*) as count, GROUP_CONCAT(id ORDER BY id) as ids
        FROM dishes 
        GROUP BY title 
        HAVING COUNT(*) > 1
        ORDER BY count DESC
    ');
    $duplicates = $stmt->fetchAll();
    
    $removedCount = 0;
    $removedItems = [];
    
    foreach ($duplicates as $duplicate) {
        $title = $duplicate['title'];
        $ids = explode(',', $duplicate['ids']);
        
        // Keep the first (lowest ID) and remove the rest
        $keepId = array_shift($ids);
        
        foreach ($ids as $idToRemove) {
            $stmt = $pdo->prepare('DELETE FROM dishes WHERE id = ?');
            $stmt->execute([$idToRemove]);
            
            if ($stmt->rowCount() > 0) {
                $removedCount++;
                $removedItems[] = [
                    'id' => $idToRemove,
                    'title' => $title
                ];
            }
        }
    }
    
    // Get updated dish count
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM dishes');
    $totalDishes = $stmt->fetch()['total'];
    
    echo json_encode([
        'success' => true,
        'message' => "Cleanup completed successfully!",
        'removed_count' => $removedCount,
        'removed_items' => $removedItems,
        'total_duplicates_found' => count($duplicates),
        'total_dishes_remaining' => $totalDishes
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ]);
}
?>
