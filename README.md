# Anon File Upload
## A tool for uploading/downloading files anonymously with client-side encryption
<!-- DESCRIPTION -->
## Description:

Anonymous file upload offers several key benefits. Primarily, it ensures user privacy and data security by allowing individuals to share files without revealing their identity, reducing the risk of personal information being exposed or misused. This feature is particularly valuable for whistleblowers, journalists, or anyone needing to share sensitive information without fear of retaliation. Additionally, it simplifies the process for users who do not want to go through the hassle of creating an account or logging in, enhancing convenience and user experience.

<!-- FEATURES -->
## Features:

- Stores uploaded file for 360 days
- Rate limiting
- Built in PHP
- Docker support

<!-- QUICKSTART -->
## Quickstart:

```
git clone https://github.com/umutcamliyurt/Anon-File-Upload
cd Anon-File-Upload/
docker build -t anon_file_upload .
docker run -d -p 80:80 -v .:/var/www/html --name anon_file_upload anon_file_upload
```
- Open [http://localhost/](http://localhost/)

## Technical details:

- AES-256-GCM for encryption
- Key is not sent to server

<!-- REQUIREMENTS -->
## Requirements:

- PHP
- Tor (for hosting onionsite)
- Apache or Nginx web server

<!-- SCREENSHOTS -->
### Screenshots:

![screenshot](image.png)

![screenshot2](image2.png)

<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE` for more information.

## Donate to support development of this project!

**Monero(XMR):** 88a68f2oEPdiHiPTmCc3ap5CmXsPc33kXJoWVCZMPTgWFoAhhuicJLufdF1zcbaXhrL3sXaXcyjaTaTtcG1CskB4Jc9yyLV

**Bitcoin(BTC):** bc1qn42pv68l6erl7vsh3ay00z8j0qvg3jrg2fnqv9