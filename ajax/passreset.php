<?php
  /**
   * Admin Password Reset
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: passreset.php, v1.00 2014-10-05 10:12:05 gewa Exp $
   */
  define("_WOJO", true);
  require_once("../init.php");

if(isset($_POST['passReset'])):
	$user->adminPassReset();
endif;