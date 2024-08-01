<?php
require 'set_headers.php';

$jsFilePaths = [
    './js/index.js',
    './mdui/mdui.global.js'
];

$cssFilePaths = [
    './styles/index.css',
    './mdui/mdui.css',
    './mdui/material-icons.css'
];
$elements = setSecurityHeadersAndGenerateElements($jsFilePaths, $cssFilePaths);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anon File Upload</title>
    <?php insertElementsIntoHead($elements); ?>
</head>

<body>
    <mdui-card class="container" variant="elevated">
        <h2><img src="logo.svg" alt="Logo">non File Upload</h2>
        <form id="upload-form">
            <input type="file" id="file-input" required>
            <mdui-button type="submit" end-icon="attach_file">Upload</mdui-button>

            <!-- Progress bar -->
            <progress id="upload-progress" value="0" max="100"></progress>
        </form>

        <div class="storage-message">
            Files are encrypted client-side and stored for 30 days. Max. file size: 2GB
        </div>

        <div class="donate-message">
            Keep this project alive by donating XMR to address:
            <strong>87QyZJVYam8GRwj5Tc7WUqJ6mtFu2MRVpSpdH6buaYUbgs2vmx44wiZ33X3yN4gGm44XDaZD3G8WdN3NJPNyC5i8Qr2Xx3J</strong>
        </div>

        <mdui-card variant="filled" id="download-link" href="/" class="download-link">File uploaded
            successfully. Download link: </mdui-card>

        <div class="footer-links">
            <mdui-button href="mailto:nemesisuks@protonmail.com" variant="outlined">Report Abuse</mdui-button>
            <mdui-button href="https://github.com/umutcamliyurt/Anon-File-Upload" target="_blank"
                variant="outlined">Source Code</mdui-button>
            <mdui-button href="/privacy-policy.php" variant="outlined">Privacy Policy</mdui-button>
            <mdui-button href="/terms-of-use.php" variant="outlined">Terms of Use</mdui-button>
        </div>
    </mdui-card>

</body>

</html>