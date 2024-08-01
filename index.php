<?php

$jsFilePaths = [
    './js/index.js',
    './mdui/mdui.global.js'
];

$cssFilePaths = [
    './styles/index.css',
    './mdui/mdui.css',
    './mdui/material-icons.css'
];

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
    $js_unquoted_hashes[] = generateHash($jsFilePath);
}

// Generate hashes for CSS files
foreach ($cssFilePaths as $cssFilePath) {
    $css_unquoted_hashes[] = generateHash($cssFilePath);
}

// Convert the hashes array to a string for the CSP header
$jsHashesStr = implode(' ', $js_quoted_hashes);

header("Content-Security-Policy: default-src 'none'; script-src 'self' $jsHashesStr; style-src-elem 'self'; style-src-attr 'unsafe-inline'; img-src 'self'; font-src 'self'; connect-src 'self'; manifest-src 'self'; frame-ancestors 'none'; base-uri 'none'; require-trusted-types-for 'script'; trusted-types lit-html forceInner");
header("Cross-Origin-Embedder-Policy: require-corp");
header("Cross-Origin-Opener-Policy: same-origin");
header("Cross-Origin-Resource-Policy: same-origin");
header("Permissions-Policy: accelerometer=(), ambient-light-sensor=(), autoplay=(), battery=(), bluetooth=(), camera=(), clipboard-read=(), clipboard-write=(), display-capture=(), document-domain=(), encrypted-media=(), fullscreen=(), gamepad=(), geolocation=(), gyroscope=(), hid=(), idle-detection=(), interest-cohort=(), keyboard-map=(), local-fonts=(), magnetometer=(), microphone=(), midi=(), payment=(), picture-in-picture=(), publickey-credentials-get=(), screen-wake-lock=(), serial=(), speaker-selection=(), sync-xhr=(), usb=(), xr-spatial-tracking=()");
header("Referrer-Policy: no-referrer");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 0");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anon File Upload</title>
    <link rel="stylesheet" href="/styles/index.css" integrity="<?php echo $css_unquoted_hashes[0]; ?>">
    <link rel="stylesheet" href="/mdui/mdui.css" integrity="<?php echo $css_unquoted_hashes[1]; ?>">
    <link rel="stylesheet" href="/mdui/material-icons.css" integrity="<?php echo $css_unquoted_hashes[2]; ?>">
    
    <script defer src="/js/index.js" integrity="<?php echo $js_unquoted_hashes[0]; ?>"></script>
    <script defer src="/mdui/mdui.global.js" integrity="<?php echo $js_unquoted_hashes[1]; ?>"></script>

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

        <mdui-card variant="filled" id="download-link" href="/" target="_blank" class="download-link">File uploaded
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