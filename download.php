<?php
session_start();

// Function to check if the request exceeds the allowed rate limit
function isRateLimited() {
    $maxRequestsPerMinute = 20; // Adjust this as needed

    if (!isset($_SESSION['requests'])) {
        $_SESSION['requests'] = [];
    }

    // Remove old entries (requests older than 1 minute)
    foreach ($_SESSION['requests'] as $timestamp => $count) {
        if ($timestamp < time() - 60) {
            unset($_SESSION['requests'][$timestamp]);
        }
    }

    // Add current request to the session
    $timestamp = time();
    $_SESSION['requests'][$timestamp] = isset($_SESSION['requests'][$timestamp]) ? $_SESSION['requests'][$timestamp] + 1 : 1;

    // Count total requests in the last minute
    $totalRequests = array_sum($_SESSION['requests']);

    // Check if rate limit is exceeded
    if ($totalRequests > $maxRequestsPerMinute) {
        return true;
    }

    return false;
}

$uploadDir = 'uploads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file'])) {
    // Check if rate limit is exceeded
    if (isRateLimited()) {
        http_response_code(429); // 429 Too Many Requests
        echo 'Rate limit exceeded. Please try again later.';
        exit;
    }

    $fileName = basename($_POST['file']);

    // Sanitize the file path
    if (strpos($fileName, '..') !== false || strpos($fileName, '/') !== false) {
        http_response_code(400);
        echo 'Invalid file path.';
        exit;
    }

    $fullPath = $uploadDir . $fileName;
    if (!file_exists($fullPath)) {
        http_response_code(404);
        echo 'File not found.';
        exit;
    }

    // Set headers to prevent caching
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Expires: 0");

    $fileType = mime_content_type($fullPath);
    header('Content-Type: ' . $fileType);
    header('Content-Disposition: attachment; filename="' . htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8') . '"');
    header('Content-Length: ' . filesize($fullPath));

    readfile($fullPath);
    exit;
}

http_response_code(400);
echo 'Invalid request.';
