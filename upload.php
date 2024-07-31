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

// Allowed MIME types
$allowedMimeTypes = [
    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp',
    'video/webm',
    'video/mp4',
    'audio/wav',
    'audio/mpeg',
    'audio/ogg',
    'application/zip',
    'application/x-rar-compressed',
    'application/x-7z-compressed',
    'application/pdf',
    'text/plain',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
];

// Allowed file extensions (fallback)
$allowedExtensions = [
    'jpg',
    'jpeg',
    'png',
    'gif',
    'webp',
    'webm',
    'mp4',
    'wav',
    'mp3',
    'ogg',
    'zip',
    'rar',
    '7z',
    'pdf',
    'txt',
    'doc',
    'docx'
];

// Set security headers
header('Content-Security-Policy: default-src \'self\';');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

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

    // Check if rate limit is exceeded
    if (count($_SESSION['uploads']) >= $maxUploadsPerMinute) {
        return true;
    }

    // Add current upload to the session
    $_SESSION['uploads'][] = $currentTime;

    return false;
}

// Function to check if the same file is being uploaded too frequently
function isRepeatedFileUpload($fileName)
{
    $maxUploadsPerFile = 1; // Number of allowed uploads for the same file within the time window
    $timeWindow = 600; // Time window in seconds (e.g., 600 seconds = 10 minutes)

    if (!isset($_SESSION['uploaded_files'])) {
        $_SESSION['uploaded_files'] = [];
    }

    $currentTime = time();

    // Remove old entries (uploads older than the time window)
    $_SESSION['uploaded_files'] = array_filter($_SESSION['uploaded_files'], function ($data) use ($currentTime, $timeWindow) {
        return $data['timestamp'] >= $currentTime - $timeWindow;
    });

    // Check if the file is being uploaded too frequently
    $fileUploads = array_filter($_SESSION['uploaded_files'], function ($data) use ($fileName) {
        return $data['file_name'] === $fileName;
    });

    if (count($fileUploads) >= $maxUploadsPerFile) {
        return true;
    }

    // Add the current file upload to the session
    $_SESSION['uploaded_files'][] = [
        'file_name' => $fileName,
        'timestamp' => $currentTime
    ];

    return false;
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
        echo 'File upload error.';
        exit;
    }

    // Validate file size
    if ($file['size'] > $maxFileSize) {
        http_response_code(400);
        echo 'File is too large.';
        exit;
    }

    // Validate MIME type using finfo
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $fileMimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    // Debug output for MIME type
    error_log("Detected MIME type: " . $fileMimeType);

    // Check if MIME type is allowed
    if (!in_array($fileMimeType, $allowedMimeTypes)) {
        // If MIME type is 'application/octet-stream', check the file extension
        if ($fileMimeType !== 'application/octet-stream') {
            http_response_code(400);
            echo 'Invalid file type. Detected MIME type: ' . htmlspecialchars($fileMimeType, ENT_QUOTES, 'UTF-8');
            exit;
        } else {
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                http_response_code(400);
                echo 'Invalid file type. Detected MIME type: ' . htmlspecialchars($fileMimeType, ENT_QUOTES, 'UTF-8') . ' with extension: ' . htmlspecialchars($fileExtension, ENT_QUOTES, 'UTF-8');
                exit;
            }
        }
    }

    // Check if the same file is being uploaded too frequently
    $fileName = basename($file['name']);
    if (isRepeatedFileUpload($fileName)) {
        http_response_code(429); // 429 Too Many Requests
        echo 'The same file is being uploaded too frequently. Please wait before trying again.';
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
    if ($currentTotalSize === false) {
        http_response_code(500);
        echo 'Failed to calculate the total size of the upload directory.';
        exit;
    }

    while ($currentTotalSize + $file['size'] > $maxTotalSize) {
        if (!deleteOldestFile($uploadDir)) {
            http_response_code(500);
            echo 'Failed to delete the oldest file to maintain total size limit.';
            exit;
        }
        $currentTotalSize = getDirectorySize($uploadDir);
        if ($currentTotalSize === false) {
            http_response_code(500);
            echo 'Failed to calculate the total size of the upload directory.';
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