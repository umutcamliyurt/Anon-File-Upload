<?php
require 'set_headers.php';

$jsFilePaths = [
    './js/languageSwitcher.js'
];

$cssFilePaths = [
    './styles/privacy-policy.css'
];

$elements = setSecurityHeadersAndGenerateElements($jsFilePaths, $cssFilePaths);?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
    <?php insertElementsIntoHead($elements); ?>
</head>

<body>
    <div class="container">
        <div class="language-switcher">
        <button id="lang-en-btn">English</button> 
        <button id="lang-tr-btn">Türkçe</button>
        </div>
        <div id="lang-en" class="block">
            <h1>Privacy Policy</h1>
            <p>This website does not collect
                any personally identifiable information (PII) from its users.</p>
            <p>Last
                updated: <time datetime="2024-06-24">June 24, 2024</time></p>
        </div>
        <div id="lang-tr" class="hidden">
            <h1>Gizlilik Politikası</h1>
            <p>Bu web sitesi, kullanıcılarından herhangi bir kişisel olarak tanımlanabilir
                bilgi toplamaz.</p>
            <p>Son güncelleme: <time datetime="2024-06-24">24 Haziran
                    2024</time></p>
        </div>
    </div>
</body>

</html>