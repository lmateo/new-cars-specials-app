<?php 
	/** 
	* Configuration

	* @package Wojo Framework
	* @author wojoscripts.com
	* @copyright 2017
	* @version Id: config.ini.php, v1.00 2017-08-07 01:19:59 gewa Exp $
	*/
 
	 if (!defined("_WOJO")) 
     die('Direct access to this location is not allowed.');
 
	/** 
	* Database Constants - these constants refer to 
	* the database configuration settings. 
	*/
	 define('DB_SERVER', 'localhost'); 
	 define('DB_USER', 'quirkspe_ncsuser'); 
	 define('DB_PASS', '(OTiJdM%.JUs'); 
	 define('DB_DATABASE', 'quirkspe_inventory');
	 define('DB_DRIVER', 'mysql');
 
	 define('INSTALL_KEY', '46rrpx1kleiNo5Dr'); 
 
	/** 
	* Show Debugger Console. 
	* Display errors in console view. Not recomended for live site. true/false 
	*/
	 define('DEBUG', false);
?>