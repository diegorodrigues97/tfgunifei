<?php


/*
|--------------------------------------------------------------------------
| @File: config
|--------------------------------------------------------------------------
|
| @FileFunction: config every address
|
| @ClassAuthor: Diego Rodrigues da Silva
| @ClassAuthorEmail: diegorodrigues97@yahoo.com.br
|
*/

/*
|--------------------------------------------------------------------------
| @Section: check if exists HTTPs
|--------------------------------------------------------------------------
*/

if($_SERVER['SERVER_PORT'] != '443' && isset($_SERVER['HTTPS']) == "on")
{
header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
exit();
}

/*
|--------------------------------------------------------------------------
| @Section: user sessions
|--------------------------------------------------------------------------
|
*/

session_start();


/*
|--------------------------------------------------------------------------
| @Section: folder and files adresses
|--------------------------------------------------------------------------
|
*/

//Folders
define("ADS_ROOT", __DIR__ . DIRECTORY_SEPARATOR);
define("ADS_CREDENTIALS", __DIR__ . DIRECTORY_SEPARATOR . 'Credentials' . DIRECTORY_SEPARATOR);
define("ADS_CORE", ADS_ROOT . 'Core' . DIRECTORY_SEPARATOR);
define("ADS_DATABASE", ADS_CORE . 'Database' . DIRECTORY_SEPARATOR);
define("ADS_DATABASE_SCRIPTS", ADS_DATABASE . 'scripts' . DIRECTORY_SEPARATOR);
define("ADS_VIEWS",  ADS_ROOT . 'Views' . DIRECTORY_SEPARATOR);
define("ADS_TEMPLATES",  ADS_ROOT . 'Templates' . DIRECTORY_SEPARATOR);
define("ADS_CONTROLLERS",  ADS_ROOT . 'Controllers' . DIRECTORY_SEPARATOR);
define("ADS_CLASSES",  ADS_ROOT . 'Classes' . DIRECTORY_SEPARATOR);
define("ADS_BACKUP",  ADS_ROOT . 'Backup' . DIRECTORY_SEPARATOR);
define("ADS_PUBLIC_HTML",  ADS_ROOT . 'public_html' . DIRECTORY_SEPARATOR);
define("ADS_USERS",  ADS_PUBLIC_HTML . 'files' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR);
define("ADS_FILES",  ADS_PUBLIC_HTML . 'files' . DIRECTORY_SEPARATOR);
define("ADS_TEMPORARY", ADS_FILES . 'temporary');

//Files
define("ADS_FILE_REQUEST", ADS_PUBLIC_HTML  . 'request.php');
define("ADS_FILE_INDEX",  ADS_PUBLIC_HTML . 'index.php');
define("ADS_FILE_UP", ADS_PUBLIC_HTML . 'up.php');
define("COMPOSER_AUTOLOAD", ADS_ROOT . "vendor" . DIRECTORY_SEPARATOR . "autoload.php");


//Others
define("APP_NAME", getAppConfig()['app_name']);

/*
|--------------------------------------------------------------------------
| @Section: Environment Variables
|--------------------------------------------------------------------------
|
*/

 if(empty(getenv("APP_FILE_CONFIG")))
 {
   putenv("APP_FILE_CONFIG=" . ADS_ROOT . "config.php");
 }


/*
|--------------------------------------------------------------------------
| @Section: Application Settings
|--------------------------------------------------------------------------
|
*/

date_default_timezone_set(getAppConfig()['app_timezona']);

/*
|--------------------------------------------------------------------------
| @Section: autoload
|--------------------------------------------------------------------------
|
*/

/*
spl_autoload_register(function($nameClass){

  $nameClass = str_replace("\\", DIRECTORY_SEPARATOR, $nameClass);

	if(file_exists(ADS_ROOT . $nameClass . ".php") === true )
  {
		require_once ADS_ROOT . $nameClass . ".php";
	}
});
*/

require_once(COMPOSER_AUTOLOAD);

/*
|--------------------------------------------------------------------------
| @Section: auxiliary functions
|--------------------------------------------------------------------------
|
*/

function getAppConfig()
{
  $params = json_decode(file_get_contents(ADS_ROOT . "config.json"),true);
  if(json_last_error() !== JSON_ERROR_NONE)
  {
    throw new \Exception("Error on File Config", 1);
  }
  return $params;
}

/*
|--------------------------------------------------------------------------
| @Section: PHP function compatibility
|--------------------------------------------------------------------------
|
*/
