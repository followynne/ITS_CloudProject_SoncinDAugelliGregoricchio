# Image Hosting Site Project
## Cloud Services Final Project
### Made by [E.Soncin](https://github.com/erikasoncin), [M.D'Augelli](https://github.com/MariodAugelli97), [M.Gregoricchio](https://www.matteogregoricchio.com/)

**You can find the source code for this project at: [https://github.com/followynne/ITS_CloudProject_SoncinDAugelliGregoricchio](https://github.com/followynne/ITS_CloudProject_SoncinDAugelliGregoricchio)**

*This software is released under the [Apache License](/LICENSE), Version 2.0.*

Final Project for Cloud Service course at ([ITS-ICT Piemonte](http://www.its-ictpiemonte.it/), IBS 18-20). Teacher: [E. Zimuel](https://github.com/ezimuel).

## DESCRIPTION
This is a cloud-based photo management website. An user can register to the service and upload its photos, to host and manage them remotely.\
Site access is available only to registered user. Every user container is private - each user gets access to their unique container by Azure SAS Token. The reason for this architectural choice is to preserve photo privacy.\
If a container or blob access level is set to Public:
- a malicious user could try retrieving blobs by a simple brute-force attack, known a single blob url;
- every user could access and read a blob, if it gets its unique url.

In this site, Azure SAS Token are created on every Resource Request, with an expiration date set to 3 minutes in the future and parameters related to each user. This way 1. blobs and containers aren't accessible from external users 2. only the logged user can access its own photos 3 if a blob/container link with token is stolen, it will expiry soon and won't be useable for long for further container crawl.\
Photos are uploaded on two cloud services, the first being Azure Cloud Storage (photo and data hosting) and the latter a SQL SERVER Database (photo information and tags hosting).\
On photo upload, Azure Computer Vision service is used to analyze the photo and retrieve a tags list of it; tags are used for image search, along with photo information (retrieved from EXIF information analysis).\
If a photo contains geo-localization information, those gets used to create photo markers on a Google Maps.\
An user can share a selection of his photos on the Net by creating a custom URL. The share system is based on Azure SAS Token - every shared photo goes with its unique access token which provides Read permissions to it, set with an user-defined expiration date.

### Specs
The site has: 
- a Login page;
- a Registration page;
- a Home page, with a small selection of the last blobs uploaded;
- a Map page, with the blob geo-location;
- a Gallery page, with the Search Images function and the Share Selected Blobs function;
- a Show Single Blob page, complete of EXIF Informations and related Tags.

### Based mainly on
- [PHP](https://php.net/>), using via Composer libraries: [Azure Storage PHP](https://github.com/Azure/azure-storage-php), [HTTP_Request2](https://packagist.org/packages/pear/http_request2), [Plates](https://packagist.org/packages/league/plates), [DotEnv](https://packagist.org/packages/vlucas/phpdotenv), [PHP-DI] (http://php-di.org/)
- [Javascript](https://www.javascript.com/), using via npm libraries: [JQuery](https://jquery.com/), [Bootstrap](https://getbootstrap.com/)
- [Simple_MVC](README_MVC.md), a simplified PHP MVC Framework developed by E. Zimuel for didactic reasons.
- Azure Cloud Services
  - [Blob Storage](https://azure.microsoft.com/it-it/services/storage/blobs/)
  - [SQL Database](https://azure.microsoft.com/en-in/services/sql-database/)
  - [Linux Server](https://azure.microsoft.com/en-us/services/virtual-machines/)
  - [Computer Vision API](https://azure.microsoft.com/en-us/services/cognitive-services/computer-vision/)

## LOCAL CONFIGURATION (Windows||Linux)
### LC-1. Clone the Repo, get dependencies
First, clone this repository. Recommended on TripAdvisor, 10/10. If you want to try this image hosting project, I wouldn't skip this step!

This project requires npm and composer. To install them, please refer to each documentation. After the install, open a shell in the project root folder and execute
```
npm install
composer install
```
to download the project dependencies.

### LC-1.1 (Optional) Update the php.ini Max Filesize
I recommend to follow this step to enable users to upload bigger files (if you already did it, that's fine).
You should go inside the *php.ini* configuration file and set those attributes to your desidered value, upgrading default values (the values you can read down below are mine test specs):
```
upload_max_filesize = 25M
post_max_size = 25M
max_execution_time = 300  
```

### LC-2. Set your environment ready for SQL Server database
- on Linux => go to [first_link](https://docs.microsoft.com/it-it/sql/connect/php/installation-tutorial-linux-mac?view=sql-server-2017) and follow ALL the instructions for your distro. I link you also [this second_link](https://docs.microsoft.com/it-it/sql/connect/odbc/linux-mac/installing-the-microsoft-odbc-driver-for-sql-server?view=sql-server-2017), which you'll need to complete at step 2 of the first link. Please bear in mind to follow all the steps in the guides, even the optional ones.
PLEASE NOTE: Install the correct drivers for your php version(Ex: 7.1, 7.2, 7.3..).

To test the installation you can follow steps at the end of the first link or you can use SQL Server on shell following these [instr](https://docs.microsoft.com/it-it/sql/linux/quickstart-install-connect-ubuntu?view=sql-server-2017)

- on Windows => go to [msphpsql/releases](https://github.com/Microsoft/msphpsql/releases) and search the compatible release for your PHP version.

Download DLLs for both SQLSRV and PDO_SQLSRV (files will be similar to 'php_sqlsrv.dll' && 'php_pdo_sqlsrv.dll').

Go to [Building SQLSRV PHP Drivers for Windows](https://github.com/microsoft/msphpsql/blob/master/README.md#building-and-installing-the-drivers-on-windows) and follow instructions.
  + You'll need to load the 2 drivers in the /ext folder of your php
  + add to php.ini configuration file these two lines: "extension=php_sqlsrv.dll",  "extension=php_pdo_sqlsrv.dll" (about the lines position, search for similar syntax lines)
  + (optional) restart the web server.
  PLEASE NOTE: Based on our experience, We recommend the usage of NON-THREAD SAFE x64 drivers. 

### LC-2.1 Recover the Database from the .sql file inside config/
In your SQL Server DB, restore the Database Structure using the .sql file you can find in *config/setup_files*.
It will be used in the project to store users and images data.

### LC-3. Prepare .env file
Get an Azure Subscription to use the services listed in this project. The Services you'll need to sign for are:
- [ ] Azure Blob Storage
- [ ] Azure SQL Server Database
- [ ] Virtual Machine
- [ ] Computer Vision API

Create a file named .env; for the content use this template model:
```
CONNECTION_STRING = "<your_azure_storage_connection_string>"
DB_STRING = "<your_azure_sql_server_database_string>"
DB_USER = "<your_database_user>"
DB_PASSWORD = "<your_database_user_password>"
COMPUTERVISION_KEY = "<your_azure_computervision_key>"
```
Replace the "string_example" with the proper string/key values got from your Subscriptions. You can find an example under *config/setup_files* you can use, by renaming it to *.env* and replacing with your apikeys.

### LC-3.1 (Optional) Add Google Maps API Key
For the sake of this project, a Google Maps API Key isn't required. If the G.Maps connection string is left as provided, it will be rendered a Google Maps for Development Use.

If you'd like to include a API Key, you can insert it in the file \_map.php, line 28:58 (directly there or saving it in the .env file and then loading it via DotEnv class).

### LC-3.2 Save .env file in the correct folder
Move the .env file you just created inside folder *config/*.

### LC-4 Build node_modules static files
From project root, in a shell execute:
```
$ node_modules/gulp/bin/gulp.js
```
This command create a build of all js/css/... static files that the application requires, under *public/dist*.

### LC-Final. Test the project
Open a shell/command prompt in the project root folder and execute:
```
php -S 0.0.0.0:9999 -t public/
```
Go to http://localhost:9999 and have fun :+1:!

## LINUX SERVER CONFIGURATION
**Bear in mind: this guide is based on Debian 9, hosted on Microsoft Azure Cloud. If your hw/sw differs, changes to this guide will be required.**

### LSC-1. Fresh Start: Requirements
Right now we'll create a new Virtual Machine to host the site. Go on [portal.azure.com](portal.azure.com) and create a new Virtual Machine with your preferred OS. As specified before, I'll be using a Debian 9 Stretch OS.

Configure its options as required by your project, be sure to set a secure connection method (password/ssh auth). After the VM is deployed, please update the dnsname. From the VM Dashboard you can click on dnsname=>configure; set the DNS as static and assign a name that we'll use later.

Log in to the VM via Shell on Linux-OS or Putty on Microsoft-system.

### LSC-2. VM Requirements Config
Let's now setup the Linux environment for hosting the site. The packages required are listed here, followed by commands or links containing further install instructions:
- Git => ```apt install git```
- Node => [here](https://github.com/nodesource/distributions/blob/master/README.md)
- Composer => eg [here](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-debian-9)
- PHP => eg [7.4](https://computingforgeeks.com/how-to-install-latest-php-on-debian/), [7.3](https://www.rosehosting.com/blog/how-to-install-php-7-3-on-debian-9/)
- Apache2 => ```apt install apache2```

This isn't a complete list; please add/install other missing sub-packages where you are prompted by shell.

### LSC-2.1. Please execute step [LC-1.1](#lc-11-optional-update-the-phpini-max-filesize)

### LSC-2.2 Prepare Repository
```
cd /var/www/html/
git clone https://github.com/followynne/ITS_CloudProject_SoncinDAugelliGregoricchio
cd <repo_name>/
npm i
composer i
```

### LSC-2.3. Please execute step [LC-2, 2.1](#lc-2-set-your-environment-ready-for-sql-server-database)

### LSC-3. Create site config for Apache2
```
sudo su
nano /etc/apache2/apache2.conf
```
In the file search and update the following Directory directive with those options:
```
<Directory /var/www/>
  Options Indexes FollowSymLinks
  AllowOverride All
  Require all granted
</Directory>
```
Next:
```
cd /etc/apache2/sites-available/
nano file.conf
```
Inside file.conf copy those lines, replacing *yourdnsname* with the Dns Name you set before:
```
Listen 80
  <VirtualHost *:80>
    ServerAdmin admin@<yourdnsname>
    ServerName <yourdnsname>
    ServerAlias <yourdnsname>
    DocumentRoot /var/www/html/ITS_CloudProject_SoncinDAugelliGregoricchio/public/
    <Directory /var/www/html/ITS_CloudProject_SoncinDAugelliGregoricchio>
      AllowOverride All
      Options -Indexes
    </Directory>
    DirectoryIndex index.html index.php
    Options -Indexes
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
  </VirtualHost>
```
Lastly:
```
sudo a2ensite file.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```
### LSC-4. Execute steps [LC-3, LC-4](#lc-3-prepare-env-file)

### LSC-Final. Test the project
Connect to <dnsname.com> and start playing with your new site!

# Want Help?
If you have doubts or requests, feel write to at:\
[erika](mailto:erika.soncin@edu.itspiemonte.it)\
[mario](mailto:mario.daugelli@edu.itspiemonte.it)\
[matteo](mailto:matteo.gregoricchio@edu.itspiemonte.it)

or contact us on Github.

<p align="center">
 <img src="https://media.giphy.com/media/ckfJmpfUQaYVi/giphy.gif"/>
</p>