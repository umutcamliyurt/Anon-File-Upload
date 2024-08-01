async function generateKey() {
    return window.crypto.subtle.generateKey(
        {
            name: "AES-GCM",
            length: 256,
        },
        true,
        ["encrypt", "decrypt"]
    );
}

async function encryptFile(file, key) {
    const iv = window.crypto.getRandomValues(new Uint8Array(12));
    const algorithm = {
        name: "AES-GCM",
        iv: iv
    };

    const fileBuffer = await file.arrayBuffer();
    const encryptedBuffer = await window.crypto.subtle.encrypt(
        algorithm,
        key,
        fileBuffer
    );

    return { iv, encryptedBuffer };
}

async function exportKey(key) {
    const exported = await window.crypto.subtle.exportKey(
        "raw",
        key
    );
    return Array.from(new Uint8Array(exported)).map(b => b.toString(16).padStart(2, '0')).join('');
}

function sanitizeFilename(filename) {
    return filename.replace(/[^A-Za-z0-9_\-\.]/g, '_');
}

function showCard(text, href)  {
    document.getElementById('download-link').style.display = 'block';
    document.getElementById('download-link').textContent = text;
    if(href) {
        document.getElementById('download-link').href = href;
    }
}

document.getElementById('upload-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const fileInput = document.getElementById('file-input');
    if (fileInput.files.length === 0) {
        alert('Please select a file.');
        return;
    }

    const file = fileInput.files[0];
    const key = await generateKey();
    const { iv, encryptedBuffer } = await encryptFile(file, key);
    const exportedKey = await exportKey(key);
    const formData = new FormData();
    formData.append('file', new Blob([iv, encryptedBuffer], { type: file.type }), file.name);

    const progressBar = document.getElementById('upload-progress');
    progressBar.style.display = 'block'; // Show progress bar before upload starts

    const uploadUrl = 'upload.php';

    // Using XMLHttpRequest to monitor upload progress
    const xhr = new XMLHttpRequest();
    xhr.open('POST', uploadUrl, true);

    // Update progress bar on progress event
    xhr.upload.addEventListener('progress', function (event) {
        if (event.lengthComputable) {
            const percentComplete = (event.loaded / event.total) * 100;
            progressBar.value = percentComplete;
        }
    });

    xhr.onload = async function () {
        if (xhr.status === 200) {
            if(xhr.responseText.endsWith(sanitizeFilename(file.name))) {
                const fileName = xhr.responseText;
                const downloadLink = `${window.location.origin}/downloader.php#${exportedKey}-${fileName}`;
                showCard('File uploaded successfully. Download link: ' + downloadLink, downloadLink);
            } else {
                showCard('File upload failed. Response code: ' + xhr.status + ' | Response text: ' + xhr.responseText);
            }
            progressBar.style.display = 'none';
        } else {
            showCard('File upload failed. Response code: ' + xhr.status + ' | Response text: ' + xhr.responseText);
        }
        progressBar.style.display = 'none';
    };

    xhr.onerror = function () {
        showCard('File upload failed. Response code: ' + xhr.status + ' | Response text: ' + xhr.responseText);
        progressBar.style.display = 'none'; // Hide progress bar on error
    };

    xhr.send(formData);
});