<?php
require 'set_headers.php';

$jsFilePaths = [
    './js/download.js',
    './mdui/mdui.global.js'
];

$cssFilePaths = [
    './styles/download.css',
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
    <title>Download and Decrypt File</title>
    <?php insertElementsIntoHead($elements); ?>
</head>

<body>
    <div class="container">
        <h2><img src="logo.svg" alt="Logo">Download and Decrypt File</h2>
        <mdui-card variant="filled" id="message" class="message">Decrypting file...</mdui-card>

        <div class="footer-links">
            <mdui-button href="mailto:nemesisuks@protonmail.com" variant="outlined">Report Abuse</mdui-button>
            <mdui-button href="https://github.com/umutcamliyurt/Anon-File-Upload" target="_blank"
                variant="outlined">Source Code</mdui-button>
            <mdui-button href="/privacy-policy.php" variant="outlined">Privacy Policy</mdui-button>
            <mdui-button href="/terms-of-use.php" variant="outlined">Terms of Use</mdui-button>
        </div>
    </div>

</body>

</html>
