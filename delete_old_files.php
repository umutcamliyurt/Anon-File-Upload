<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$uploadDir = 'uploads/';

define('COLOR_RESET', "\033[0m");
define('COLOR_CYAN', "\033[36m"); 
define('COLOR_WHITE', "\033[37m"); 
define('COLOR_RED', "\033[31m");   

function deleteOldFiles($dir)
{
    $files = glob($dir . '*');
    foreach ($files as $file) {
        if (is_file($file) && time() - filemtime($file) >= 10 * 60) { // 10 minutes
            unlink($file);
        }
    }
}

function formatFileSize($size)
{
    if ($size < 1024) {
        return $size . ' bytes';
    } elseif ($size < 1048576) {
        return round($size / 1024, 2) . ' KB';
    } elseif ($size < 1073741824) {
        return round($size / 1048576, 2) . ' MB';
    } else {
        return round($size / 1073741824, 2) . ' GB';
    }
}

function displayTimeLeftForFiles($dir, $totalSeconds)
{
    while (true) {
        system('clear');

        deleteOldFiles($dir); // Run deletion every loop

        $files = glob($dir . '*');
        echo COLOR_CYAN . str_pad("File", 40) . str_pad("Size", 15) . str_pad("Time Left", 30) . COLOR_RESET . "\n";
        echo str_repeat("-", 85) . "\n";

        foreach ($files as $file) {
            if (is_file($file)) {
                $fileAge = time() - filemtime($file); 
                $remainingTime = ($totalSeconds - $fileAge);
                $fileSize = filesize($file); 
                $formattedSize = formatFileSize($fileSize); 
                $fileName = substr(basename($file), 0, 40);

                if ($remainingTime > 0) {
                    $minutesLeft = floor(($remainingTime % 3600) / 60);
                    $secondsLeft = $remainingTime % 60;

                    echo sprintf("%s%-40s %-15s %d minutes, %d seconds%s\n", 
                                 COLOR_WHITE, $fileName, $formattedSize, $minutesLeft, $secondsLeft, COLOR_RESET);
                } else {
                    echo sprintf("%s%-40s %-15s %-1s%s\n", 
                                 COLOR_RED, $fileName, $formattedSize, "Eligible for deletion", COLOR_RESET);
                }
            }
        }

        echo str_repeat("-", 85) . "\n";
        sleep(10);
    }
}

// Start monitoring with 10-minute threshold (600 seconds)
displayTimeLeftForFiles($uploadDir, 600);
