<?php
require __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    json_response(['error' => 'Method not allowed'], 405);
}

$user = current_user_or_null();
if (!$user) {
    json_response(['user' => null]);
}

json_response(['user' => $user]);


