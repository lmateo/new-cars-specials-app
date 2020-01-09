<?php
  /**
   * Class App
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: app.class.php, v1.00 2014-10-20 18:20:24 gewa Exp $
   */

  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');


  abstract class App
  {
      static $objects = array();


      /**
       * App::get()
       * 
       * @param mixed $name
       * @return
       */
      public static function get($name)
      {
          return isset(self::$objects[$name]) ? self::$objects[$name] : null;
      }

      /**
       * App::set()
       * 
       * @param mixed $name
       * @param mixed $object
       * @return
       */
      public static function set($name, $object)
      {
          self::$objects[$name] = $object;
      }

  }