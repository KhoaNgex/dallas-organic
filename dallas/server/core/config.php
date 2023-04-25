<?php

if ($_SERVER['SERVER_NAME'] == 'localhost') {
	/** database config **/
	define('DBNAME', 'dallasorganic');
	define('DBHOST', 'localhost');
	define('DBUSER', 'root');
	define('DBPORT', '3307');
	define('DBPASS', '');
	define('DBDRIVER', '');

	define('ROOT', 'http://localhost/dallas/public');

} else {
	/** database config **/
	define('DBNAME', 'dallasorganic');
	define('DBHOST', 'localhost');
	define('DBUSER', 'root');
	define('DBPASS', '');
	define('DBPORT', '3307');
	define('DBDRIVER', '');

	define('ROOT', 'https://www.yourwebsite.com');

}

define('APP_NAME', "My Webiste");
define('APP_DESC', "Best website on the planet");

/** true means show errors **/
define('DEBUG', true);