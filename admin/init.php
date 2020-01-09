<?php
  /**
   * Init
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: init.php, v1.00 2014-10-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  if (substr(PHP_OS, 0, 3) == "WIN") {
      $BASEPATH = str_replace("admin\\init.php", "", realpath(__FILE__));
  } else {
      $BASEPATH = str_replace("admin/init.php", "", realpath(__FILE__));
  }
  define("BASEPATH", $BASEPATH);
  
  $configFile = BASEPATH . "lib/config.ini.php";
  if (file_exists($configFile)) {
      require_once($configFile);
  } else {
      header("Location: setup/");
  }
  
  require_once(BASEPATH . "bootstrap.php");
  Bootstrap::init();
  
  App::set('Session',new Session());

  App::set('Db',new Db());
  $db = App::get("Db");
  
  App::set('Core',new Core());
  $core = App::get("Core");
  
  App::set('Lang',new Lang());
  
  App::set('Auth',new Auth());
  $auth = App::get("Auth");
  
  App::set('Users',new Users());
  $user = App::get("Users");

  App::set('Stats',new Stats());
  $stats = App::get("Stats");

  App::set('Content',new Content());
  $content = App::get("Content");

  App::set('Items',new Items());
  $items = App::get("Items");
  
  App::set('wSpecials',new wSpecials());
  $wSpecials = App::get("wSpecials");
  
  $pager = Paginator::instance();
  App::set('wError',new wError());
  
  Filter::run();
  Debug::run();
  
  $dir = (App::get("Core")->site_dir) ? '/' . App::get("Core")->site_dir : '';
  $url = preg_replace("#/+#", "/", $_SERVER['HTTP_HOST'] . $dir);
  $site_url = Url::protocol() . "://" . $url;
  
  define("SITEURL", $site_url);
  define("ADMINURL", $site_url."/admin");
  define("UPLOADS", BASEPATH . "uploads/");
  define("UPLOADURL", SITEURL . "/uploads/");
  
  define("THEME", BASEPATH . "admin/assets");
  define("THEMEU", SITEURL . "/admin/assets");
  define('SITENEWCARSCSVURL', "http://qcsv.quirkcars.biz/csv-import-export/");

  /* Url Slugs*/
  define("URL_LOGIN", "login");
  define("URL_REGISTER", "register");
  define("URL_ACCOUNT", "account");
  define("URL_MYLISTINGS", "mylistings");
  define("URL_MYSETTINGS", "mysettings");
  define("URL_MYLOCATIONS", "mylocations");
  define("URL_ADDLISTING", "add");
  define("URL_EDIT", "edit");
  define("URL_ADD", "add");
  define("URL_PAGE", "page");
  define("URL_LISTINGS", "listings");
  define("URL_SEARCH", "search");
  define("URL_BODY", "body");
  define("URL_BRAND", "make");
  define("URL_BRANDS", "brands");
  define("URL_ITEM", "listing");
  define("URL_SELLER", "seller");
  
  Locale::setDefault($core->locale);
  $exclude = array("index", "controller", "helper", "login", "footer", "header", "crumbs", "init", "logout", "main", "print" ,"printws");

  if (count($core->_url) > 4) {
	  Url::redirect(ADMINURL . '/' . $core->_url[1] . '/');
  }