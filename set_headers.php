<?php
function setSecurityHeadersAndGenerateElements($jsFilePaths, $cssFilePaths)
{
    // Initialize an array to hold the hashes
    $js_quoted_hashes = [];
    $js_unquoted_hashes = [];

    $css_unquoted_hashes = [];

    function generateHash($filePath)
    {
        $fileContent = file_get_contents($filePath);
        return "sha256-" . base64_encode(hash('sha256', $fileContent, true));
    }

    // Generate hashes for JavaScript files
    foreach ($jsFilePaths as $jsFilePath) {
        $js_quoted_hashes[] = "'" . generateHash($jsFilePath) . "'";
        $js_unquoted_hashes[$jsFilePath] = generateHash($jsFilePath);
    }

    // Generate hashes for CSS files
    foreach ($cssFilePaths as $cssFilePath) {
        $css_unquoted_hashes[$cssFilePath] = generateHash($cssFilePath);
    }

    // Convert the hashes array to a string for the CSP header
    $jsHashesStr = implode(' ', $js_quoted_hashes);

    // Set security headers
    header("Content-Security-Policy: default-src 'none'; script-src 'self' $jsHashesStr; style-src-elem 'self'; style-src-attr 'unsafe-inline'; img-src 'self'; font-src 'self'; connect-src 'self'; manifest-src 'self'; frame-ancestors 'none'; base-uri 'none'; require-trusted-types-for 'script'; trusted-types lit-html forceInner");
    header("Cross-Origin-Embedder-Policy: require-corp");
    header("Cross-Origin-Opener-Policy: same-origin");
    header("Cross-Origin-Resource-Policy: same-origin");
    header("Permissions-Policy: accelerometer=(), ambient-light-sensor=(), autoplay=(), battery=(), bluetooth=(), camera=(), clipboard-read=(), clipboard-write=(), display-capture=(), document-domain=(), encrypted-media=(), fullscreen=(), gamepad=(), geolocation=(), gyroscope=(), hid=(), idle-detection=(), interest-cohort=(), keyboard-map=(), local-fonts=(), magnetometer=(), microphone=(), midi=(), payment=(), picture-in-picture=(), publickey-credentials-get=(), screen-wake-lock=(), serial=(), speaker-selection=(), sync-xhr=(), usb=(), xr-spatial-tracking=()");
    header("Referrer-Policy: no-referrer");
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: DENY");
    header("X-XSS-Protection: 0");

    // Generate the <script> and <link> elements
    $elements = [
        'scripts' => [],
        'styles' => []
    ];

    foreach ($jsFilePaths as $jsFilePath) {
        $integrity = $js_unquoted_hashes[$jsFilePath];
        $elements['scripts'][$jsFilePath] = "<script defer src=\"$jsFilePath\" integrity=\"$integrity\"></script>";
    }

    foreach ($cssFilePaths as $cssFilePath) {
        $integrity = $css_unquoted_hashes[$cssFilePath];
        $elements['styles'][$cssFilePath] = "<link rel=\"stylesheet\" href=\"$cssFilePath\" integrity=\"$integrity\">";
    }

    return $elements;
}


function insertElementsIntoHead($elements)
{
    // Insert script elements
    foreach ($elements['scripts'] as $scriptElement) {
        echo $scriptElement . "\n";
    }

    // Insert style elements
    foreach ($elements['styles'] as $styleElement) {
        echo $styleElement . "\n";
    }
}