<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_config.php';

$ch = curl_init(rtrim(APP_BASE_URL, '/') . '/api/auth.php');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode(['action' => 'logout']),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
]);
curl_exec($ch);
curl_close($ch);

header('Location: login.php');
exit;
