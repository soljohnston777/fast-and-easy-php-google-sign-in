# Google PHP API Client Examples
A simple Sign In example using Google PHP API Client.


Create a Folder in your htdocs or www folder:

sign_in


Copy all the files there:

client_secrets.json
composer.json
composer.lock
google.php



## Steps

1. Create a Google Developer account on [Google Developer Console](https://console.developers.google.com).

2. Create an App on [Google Developer Console - Create Project ](https://console.developers.google.com/projectcreate).

3. Create an OAuth Client ID on [Google Developer Console - Create Client ID ](https://console.developers.google.com/apis/credentials/oauthclient).
3.1 Select - Web Application
3.2 Put in your Authorized redirect URIs as:

    http://localhost/sign_in/google.php
    https://localhost/sign_in/google.php


3.3 Download this file when creating your Credential:

    client_secrets.json


4. Install Google PHP API Client through composer.
go to the Go to your htdocs or www folder then to the folder:
cd sign_in/

```cmd
composer install
```

5. Put in your App's CLIENT_ID and CLIENT_SECRET (and project_id) in the file if you didn't download it

    client_secrets.json


{"web":{"client_id":"client_id.apps.googleusercontent.com",
    "project_id":"login-123456",
    "auth_uri":"https://accounts.google.com/o/oauth2/auth",
    "token_uri":"https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url":"https://www.googleapis.com/oauth2/v1/certs",
    "client_secret":"client_secret",
    "redirect_uris":["http://localhost/sign_in/google.php"]}}


6. Start your PHP server if it isn't already running.


7. Access the web page going to:
http://localhost/sign_in/google.php
