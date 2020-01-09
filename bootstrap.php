<?php
  /**
   * Bootstrap Class
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: bootstrap.php, v1.00 2014-03-05 10:12:05 gewa Exp $
   */

  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');


  define('BASE', realpath(dirname(__file__)) . '/lib') . '/';
  define('DS', DIRECTORY_SEPARATOR);

  class Bootstrap
  {
      private static $__loader;


      /**
       * Bootstrap::__construct()
       * 
       * @return
       */
      private function __construct()
      {
          spl_autoload_register(array($this, 'autoLoad'));
      }


      /**
       * Bootstrap::init()
       * 
       * @return
       */
      public static function init()
      {
          if (self::$__loader == null) {
              self::$__loader = new self();
          }

          return self::$__loader;
      }


      /**
       * Bootstrap::autoLoad()
       * 
       * @param mixed $class
       * @return
       */
      public function autoLoad($class)
      {
          $exts = array('.php', '.class.php');

          spl_autoload_extensions("'" . implode(',', $exts) . "'");
          set_include_path(get_include_path() . PATH_SEPARATOR . BASE);

          foreach ($exts as $ext) {
              if (is_readable($path = BASE . strtolower($class . $ext))) {
                  require_once $path;
                  return true;
              }
          }
          self::recursiveAutoLoad($class, BASE);
      }


      /**
       * Bootstrap::recursiveAutoLoad()
       * 
       * @param mixed $class
       * @param mixed $path
       * @return
       */
      private static function recursiveAutoLoad($class, $path)
      {
          if (is_dir($path)) {
              if (($handle = opendir($path)) !== false) {
                  while (($resource = readdir($handle)) !== false) {
                      if (($resource == '..') or ($resource == '.')) {
                          continue;
                      }

                      if (is_dir($dir = $path . DS . $resource)) {
                          //self::recursiveAutoLoad($class, $dir);
						  continue;
                      } else
                          if (is_readable($file = $path . DS . $resource)) {
                              require_once $file;
                          }
                  }
                  closedir($handle);
              }
          }
      }
  }