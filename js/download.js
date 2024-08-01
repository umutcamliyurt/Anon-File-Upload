async function importKey(hexKey) {
    const keyBuffer = new Uint8Array(hexKey.match(/.{1,2}/g).map(byte => parseInt(byte, 16)));
    return window.crypto.subtle.importKey(
        "raw",
        keyBuffer,
        { name: "AES-GCM" },
        true,
        ["decrypt"]
    );
}

async function decryptFile(encryptedBuffer, iv, key) {
    const algorithm = {
        name: "AES-GCM",
        iv: iv
    };
    return window.crypto.subtle.decrypt(
        algorithm,
        key,
        encryptedBuffer
    );
}

async function downloadDecryptedFile(fileName, decryptedBuffer) {
    const blob = new Blob([decryptedBuffer]);
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = fileName;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

async function startDecryption() {
    const hash = window.location.hash.substring(1);
    const parts = hash.split('-');
    const fileName = parts.slice(1).join('-');  // Join all parts except the first one which is the key
    const keyHex = parts[0];

    if (!keyHex || !fileName) {
        document.getElementById('message').textContent = 'Invalid download link.';
        return;
    }

    try {
        const response = await fetch('download.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ file: fileName })
        });

        if (!response.ok) {
            console.log(response);
            document.getElementById('message').textContent = 'File download failed.';
            const uploadAgainButton = document.createElement('mdui-button');
            uploadAgainButton.href = '/';
            uploadAgainButton.textContent = 'Upload Again';
            document.getElementById('message').appendChild(document.createElement('br'));
            document.getElementById('message').appendChild(uploadAgainButton);
            return;
        }

        const encryptedBlob = await response.blob();
        const arrayBuffer = await encryptedBlob.arrayBuffer();
        const iv = new Uint8Array(arrayBuffer.slice(0, 12));
        const encryptedBuffer = arrayBuffer.slice(12);
        const key = await importKey(keyHex);
        const decryptedBuffer = await decryptFile(encryptedBuffer, iv, key);

        await downloadDecryptedFile(fileName, decryptedBuffer);
        document.getElementById('message').textContent = 'File decrypted and downloaded.';
        const uploadAgainButton = document.createElement('mdui-button');
        uploadAgainButton.href = '/';
        uploadAgainButton.textContent = 'Upload Again';
        document.getElementById('message').appendChild(document.createElement('br'));
        document.getElementById('message').appendChild(uploadAgainButton);
    } catch (error) {
        document.getElementById('message').textContent = 'An error occurred: ' + error.message;
    }
}

window.onload = startDecryption;