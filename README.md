# Image Hosting Site Project
## Cloud Services Final Project
### Made by [E.Soncin](https://github.com/erikasoncin), [M.D'Augelli](https://github.com/MariodAugelli97), [M.Gregoricchio](https://www.matteogregoricchio.com/)
Description:
//TODO//
Final Project for Cloud Service course at ([ITS-ICT Piemonte](http://www.its-ictpiemonte.it/), IBS 18-20). Teacher: [E. Zimuel](https://github.com/ezimuel).
Based on:
- [PHP](https://php.net/>), using via Composer libraries: [Azure Storage PHP](https://github.com/Azure/azure-storage-php), [HTTP_Request2](https://packagist.org/packages/pear/http_request2), [Plates](https://packagist.org/packages/league/plates), [DotEnv](https://packagist.org/packages/vlucas/phpdotenv)
- [Javascript](https://www.javascript.com/), using via npm libraries: [JQuery](https://jquery.com/), [Bootstrap](https://getbootstrap.com/)
- Azure Cloud Services
  - [Blob Storage](https://azure.microsoft.com/it-it/services/storage/blobs/)
  - [SQL Database](https://azure.microsoft.com/en-in/services/sql-database/)
  - [Linux Server](https://azure.microsoft.com/en-us/services/virtual-machines/)
  - [Computer Vision API](https://azure.microsoft.com/en-us/services/cognitive-services/computer-vision/)

##INSTALL LOCALLY (Windows||Linux)
### 1. Clone the Repo, get dependencies
First, clone this repository. Recommended on TripAdvisor, 10/10. If you want to try this image hosting project, I wouldn't skip this step!

This project requires npm and composer. To install them, please refer to each documentation. After the install, open a shell in the project root folder and execute
```
npm install
composer install
```
to download the project dependencies.

### 1.1 (Optional) Update the php.ini Max Filesize
I recommend to follow this step to enable users to upload bigger files (if you already did it, that's fine).
You should go inside the php.ini configuration file and set those attributes to your desidered value, upgrading default values (the values you can read down below are mine test specs):
```
upload_max_filesize = 250M
post_max_size = 250M
max_execution_time = 300  
```

### 2. Set your environment ready for SQL Server database
- on Linux => go to [first_link](https://docs.microsoft.com/it-it/sql/connect/php/installation-tutorial-linux-mac?view=sql-server-2017) and follow ALL the instructions for your distro. I link you also [second_link](https://docs.microsoft.com/it-it/sql/connect/odbc/linux-mac/installing-the-microsoft-odbc-driver-for-sql-server?view=sql-server-2017), which you'll need to complete at step 2. Please bear in mind, follow all the steps in the guide, even optional ones.
To test the installation you can follow steps at the end of the first link or you can use SQL Server on shell following these [instr](https://docs.microsoft.com/it-it/sql/linux/quickstart-install-connect-ubuntu?view=sql-server-2017)

- on Windows => go to [msphpsql/releases](https://github.com/Microsoft/msphpsql/releases) and search the compatible release for your PHP version. Download DLLs for both SQLSRV and PDO_SQLSRV (files will be similar to 'php_sqlsrv.dll' && 'php_pdo_sqlsrv.dll').
Go to [Building SQLSRV PHP Drivers for Windows](https://github.com/microsoft/msphpsql/blob/master/README.md#building-and-installing-the-drivers-on-windows) and follow instructions.
  + You'll need to load the 2 drivers in the /ext folder of your php
  + add to php.ini configuration file these two lines: "extension=php_sqlsrv.dll",  "extension=php_pdo_sqlsrv.dll" (about the lines position, search for similar syntax lines)
  + (optional) restart the web server.

#### 2.1 Create a Database from the .bak file
In your SQL Server DB, retrieve the Database Model from the .bak (or .sql) file you can find in the root of this repository.
It will be used in the project to store users and images data.

### 3. Prepare .env file
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
Replace the "string_example" with the proper string/key values got from your Subscriptions.

### 3.1 (Optional) Add Google Maps API Key
For the sake of this project, a Google Maps API Key isn't required. If the G.Maps connection string is left as provided, it will be rendered a Google Maps for Development Use. If you'd like to include a API Key, you can insert it in the file \_map.php, line 28:58 (directly there or saving it in the .env file).

### 3.2 Save .env file to private directory
//TODO: complete .env file; where to put .env file, both for server and localhosting//

### 4. Set internal project elements
//TODO :: gestioneorigin, server host...//
Modify these files as instructed.
- /php/GetJsonBlobs.php: line xx, set [...]
- /php/CreateShareableLink.php: line xx, set [...]

### 5. Test the project
Open a shell/command prompt in the project root folder and execute:
```
php -S 0.0.0.0:9999
```
Go to http://localhost:9999/start.php and have fun :+1:!

## SETUP IN LINUX SERVER
### follow step 1-? from [Local Setup](https://github.com/followynne/ITS_CloudProject_SoncinDAugelliGregoricchio#install-locally-windows-linux)
//TODO also the correct link//
### put prj in server root
//TODO//

# Help Needed?
Write at _______ //TODO//
