<?php
  /**
   * Logout
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: logout.php, v1.00 2014-10-20 10:12:05 gewa Exp $
   */
  define("_WOJO", true);
  
  require_once("init.php");
?>
<?php
  if ($auth->logged_in)
      $auth->logout();
	  
  Url::redirect(ADMINURL . "/login.php");
?>