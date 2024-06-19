<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define the upload directory and maximum file size
$uploadDir = 'uploads/';
$maxFileSize = 2 * 1024 * 1024 * 1024; // 2 GB

// Maximum total size for uploads folder (in bytes)
$maxTotalSize = 40 * 1024 * 1024 * 1024; // 40 GB

// Function to calculate directory size recursively
function getDirectorySize($path)
{
    $totalSize = 0;
    try {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $totalSize += $file->getSize();
            }
        }
    } catch (Exception $e) {
        return false;
    }
    return $totalSize;
}

// Function to delete the oldest file based on file creation time
function deleteOldestFile($dir)
{
    $oldestFile = null;
    $oldestTime = PHP_INT_MAX;

    foreach (new DirectoryIterator($dir) as $fileInfo) {
        if ($fileInfo->isFile()) {
            $fileTime = $fileInfo->getCTime(); // Creation time
            if ($fileTime < $oldestTime) {
                $oldestTime = $fileTime;
                $oldestFile = $fileInfo->getPathname();
            }
        }
    }

    if ($oldestFile !== null) {
        return unlink($oldestFile);
    }

    return false;
}

// Function to check if the request exceeds the allowed rate limit
function isRateLimited()
{
    $maxUploadsPerMinute = 2; // Adjust this as needed

    if (!isset($_SESSION['uploads'])) {
        $_SESSION['uploads'] = [];
    }

    // Remove old entries (uploads older than 1 minute)
    $currentTime = time();
    $_SESSION['uploads'] = array_filter($_SESSION['uploads'], function ($timestamp) use ($currentTime) {
        return $timestamp >= $currentTime - 60;
    });

    // Add current upload to the session
    $_SESSION['uploads'][] = $currentTime;

    // Check if rate limit is exceeded
    return count($_SESSION['uploads']) > $maxUploadsPerMinute;
}

// Ensure the request method is POST and the file is uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    // Check if rate limit is exceeded
    if (isRateLimited()) {
        http_response_code(429); // 429 Too Many Requests
        echo 'Rate limit exceeded for uploads. Please try again later.';
        exit;
    }

    $file = $_FILES['file'];

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo 'File upload error. Code: ' . $file['error'];
        exit;
    }

    // Check file size
    if ($file['size'] > $maxFileSize) {
        http_response_code(400);
        echo 'File is too large.';
        exit;
    }

    // Ensure upload directory exists
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        echo 'Failed to create upload directory.';
        exit;
    }

    // Check if the upload directory is writable
    if (!is_writable($uploadDir)) {
        http_response_code(500);
        echo 'Upload directory is not writable.';
        exit;
    }

    // Check total size of uploads directory
    $currentTotalSize = getDirectorySize($uploadDir);
    if ($currentTotalSize === false || $currentTotalSize + $file['size'] > $maxTotalSize) {
        // Try to delete the oldest file to make space
        if (!deleteOldestFile($uploadDir)) {
            http_response_code(500);
            echo 'Failed to delete the oldest file to maintain total size limit.';
            exit;
        }
    }

    // Sanitize the file name
    $fileName = basename($file['name']);
    $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $fileName); // Replace invalid characters with underscores
    $uniqueName = uniqid() . '-' . $fileName;

    // Move uploaded file to the target directory
    $targetFilePath = $uploadDir . $uniqueName;
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        echo htmlspecialchars($uniqueName, ENT_QUOTES, 'UTF-8');
    } else {
        http_response_code(500);
        echo 'Failed to move uploaded file.';
    }
} else {
    http_response_code(400);
    echo 'Invalid request.';
}
?>
