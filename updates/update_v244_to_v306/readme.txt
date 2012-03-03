How to update:

NOTE: upgrade processing may take up to some minutes


1. Make backup of all your files and database.

2. Copy these files from current package to server:
'index.php' file
'.htaccess' file
'modules' folder
'themes' folder
'updates' folder
'system' folder except 'system/config/database.php' file

3. 'system/config/config.php' file has changed in this version, so you must replace it with new file and then re-enter your domain settings.
Typically here are 2 config options you need to change in this file:
$config['base_url'] and $config['subdirectory']

So if your site located on such url
http://www.example.com/subdir/
in config.php set
$config['base_url']	= "http://www.example.com/";
$config['subdirectory']	= "subdir/";


4. in index.php disable warnings render:
error_reporting(E_ERROR);

5. enter such url in browser http://example.com/subdir/update/update_v244_to_v305

6. in index.php enable warnings render:
error_reporting(E_ALL);

7. Remove 'modules/contactus/' and 'modules/locations/' folders, these modules now are in core functions and installed automatically

8. make new folder 'tmp' with 777 access attributes in your 'users_content' folder

9. press 'Synchronize users content' button at system settings page in admin panel
