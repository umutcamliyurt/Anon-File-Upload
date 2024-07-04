import '/mdui/mdui.esm.js';

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
            const fileName = xhr.responseText;
            const downloadLink = `${window.location.origin}/download.html#${exportedKey}-${fileName}`;

            // Display the download link on the page
            document.getElementById('download-link').style.display = 'block';
            document.getElementById('download-link').href = downloadLink;
            document.getElementById('download-link').textContent = 'File uploaded successfully. Download link: ' + downloadLink;

            // Hide progress bar after upload completes
            progressBar.style.display = 'none';
        } else {
            alert('File upload failed.');
            progressBar.style.display = 'none'; // Hide progress bar on error
        }
    };

    xhr.onerror = function () {
        alert('File upload failed. Please try again.');
        progressBar.style.display = 'none'; // Hide progress bar on error
    };

    xhr.send(formData);
});