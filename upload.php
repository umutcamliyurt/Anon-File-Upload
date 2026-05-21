<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define upload directory and max file size
$uploadDir = 'uploads/';
$maxFileSize = 500 * 1024 * 1024; // 500MB
$maxTotalSize = 150 * 1024 * 1024 * 1024; // 150GB

// Allowed MIME types
$allowedMimeTypes = [
    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
    'video/webm', 'video/mp4',
    'audio/wav', 'audio/mpeg', 'audio/ogg',
    'application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed',
    'application/pdf', 'text/plain',
    'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/octet-stream'
];

// Allowed file extensions
$allowedExtensions = [
    'jpg', 'jpeg', 'png', 'gif', 'webp', 'webm', 'mp4',
    'wav', 'mp3', 'ogg', 'zip', 'rar', '7z', 'pdf', 'txt', 'doc', 'docx'
];

// Forbidden MIME types (executable types)
$forbiddenMimeTypes = [
    'application/x-php', 'text/x-php', 'application/x-sh', 'application/x-csh', 
    'application/x-msdos-program', 'application/x-msdownload', 'application/x-exe',
    'application/x-object', 'application/x-bat', 'application/x-python', 'application/x-perl'
];

// Forbidden file extensions
$forbiddenExtensions = [
    'php', 'php3', 'php4', 'php5', 'phtml', 'sh', 'bash', 'bat', 'cmd', 'exe', 'msi',
    'bin', 'cgi', 'pl', 'py', 'asp', 'aspx', 'jsp', 'vbs'
];

// Security headers
header('Content-Security-Policy: default-src \'self\';');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Function to get the total directory size
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

// Function to delete the oldest file
function deleteOldestFile($dir)
{
    $oldestFile = null;
    $oldestTime = PHP_INT_MAX;

    foreach (new DirectoryIterator($dir) as $fileInfo) {
        if ($fileInfo->isFile()) {
            $fileTime = $fileInfo->getCTime();
            if ($fileTime < $oldestTime) {
                $oldestTime = $fileTime;
                $oldestFile = $fileInfo->getPathname();
            }
        }
    }

    return $oldestFile !== null ? unlink($oldestFile) : false;
}

// Function to prevent excessive uploads
function isRateLimited()
{
    $maxUploadsPerMinute = 2;

    if (!isset($_SESSION['uploads'])) {
        $_SESSION['uploads'] = [];
    }

    $currentTime = time();
    $_SESSION['uploads'] = array_filter($_SESSION['uploads'], function ($timestamp) use ($currentTime) {
        return $timestamp >= $currentTime - 60;
    });

    if (count($_SESSION['uploads']) >= $maxUploadsPerMinute) {
        return true;
    }

    $_SESSION['uploads'][] = $currentTime;
    return false;
}

// Ensure the request is POST with a file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {

    if (isRateLimited()) {
        http_response_code(429);
        echo 'Rate limit exceeded. Try again later.';
        exit;
    }

    $file = $_FILES['file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo 'File upload error.';
        exit;
    }

    if ($file['size'] > $maxFileSize) {
        http_response_code(400);
        echo 'File too large.';
        exit;
    }

    // Get MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $fileMimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    // Get file extension
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Block dangerous MIME types
    if (in_array($fileMimeType, $forbiddenMimeTypes)) {
        http_response_code(400);
        echo 'Executable files are not allowed.';
        exit;
    }

    // Allow application/octet-stream only if the extension is whitelisted
    if ($fileMimeType === 'application/octet-stream') {
        if (!in_array($fileExtension, $allowedExtensions)) {
            http_response_code(400);
            echo 'Invalid file type.';
            exit;
        }
    } elseif (!in_array($fileMimeType, $allowedMimeTypes)) {
        http_response_code(400);
        echo 'Invalid file type.';
        exit;
    }

    // Block forbidden extensions
    if (in_array($fileExtension, $forbiddenExtensions)) {
        http_response_code(400);
        echo 'Executable files are not allowed.';
        exit;
    }

    // Generate a random 16-byte hex string
    $randomHex = bin2hex(random_bytes(16));
    // Preserve the original file extension
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    // New filename: 16-byte hex + original extension
    $uniqueName = $randomHex . '.' . $fileExtension;

    $targetFilePath = $uploadDir . $uniqueName;

    // Ensure upload directory exists
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        echo 'Failed to create upload directory.';
        exit;
    }

    if (!is_writable($uploadDir)) {
        http_response_code(500);
        echo 'Upload directory is not writable.';
        exit;
    }

    // Manage total upload size
    $currentTotalSize = getDirectorySize($uploadDir);
    if ($currentTotalSize === false) {
        http_response_code(500);
        echo 'Failed to calculate total size.';
        exit;
    }

    while ($currentTotalSize + $file['size'] > $maxTotalSize) {
        if (!deleteOldestFile($uploadDir)) {
            http_response_code(500);
            echo 'Failed to free space.';
            exit;
        }
        $currentTotalSize = getDirectorySize($uploadDir);
    }

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        echo htmlspecialchars($uniqueName, ENT_QUOTES, 'UTF-8');
    } else {
        http_response_code(500);
        echo 'Failed to save file.';
    }

} else {
    http_response_code(400);
    echo 'Invalid request.';
}
?>