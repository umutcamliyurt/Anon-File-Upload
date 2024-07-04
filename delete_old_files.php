<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define the upload directory
$uploadDir = 'uploads/';

// Function to delete old files based on expiration time (360 days)
function deleteOldFiles($dir)
{
    $files = glob($dir . '*');
    foreach ($files as $file) {
        if (is_file($file) && time() - filemtime($file) >= 360 * 24 * 60 * 60) { // 360 days in seconds
            unlink($file);
        }
    }
}

// Call the function to delete old files
deleteOldFiles($uploadDir);