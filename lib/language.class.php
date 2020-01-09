<?php

  /**
   * Language
   * 
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: lang.class.php, v 1.00 2014-01-10 21:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');


  final class Lang
  {
      const langdir = "lang/";
      public static $language;
      public static $word = array();
      public static $lang;


      /**
       * Lang::__construct()
       * 
       * @return
       */
      public function __construct()
      {
          self::get();
      }

      /**
       * Lang::get()
       * 
       * @return
       */
      private static function get()
      {
          if (isset($_COOKIE['LANG_PMP'])) {
              $sel_lang = Validator::sanitize($_COOKIE['LANG_PMP'], "string", 2);
              $vlang = self::fetchLanguage($sel_lang);
              if (in_array($sel_lang, $vlang)) {
                  Core::$language = $sel_lang;
              } else {
                  Core::$language = App::get("Core")->lang;
              }
              if (file_exists(BASEPATH . self::langdir . Core::$language . "/lang.xml")) {
                  self::$word = self::set(BASEPATH . self::langdir . Core::$language . "/lang.xml", Core::$language);
              } else {
                  self::$word = self::set(BASEPATH . self::langdir . App::get("Core")->lang . "/lang.xml", App::get("Core")->lang);
              }
          } else {
              Core::$language = App::get("Core")->lang;
              self::$word = self::set(BASEPATH . self::langdir . App::get("Core")->lang . "/lang.xml", App::get("Core")->lang);

          }
          self::$lang = Core::$language;
          return self::$word;
      }

      /**
       * Lang::set()
       * 
       * @return
       */
      private static function set($lang)
      {
          $xmlel = simplexml_load_file($lang);
          $data = new stdClass();
          foreach ($xmlel as $pkey) {
              $key = (string )$pkey['data'];
              $data->$key = (string )str_replace(array('\'', '"'), array("&apos;", "&quot;"), $pkey);
          }

          return $data;
      }

      /**
       * Lang::getSections()
       * 
       * @return
       */
      public static function getSections()
      {
          $xmlel = simplexml_load_file(BASEPATH . self::langdir . Core::$language . "/lang.xml");
          $query = '/language/phrase[not(@section = preceding-sibling::phrase/@section)]/@section';

          foreach ($xmlel->xpath($query) as $text) {
              $sections[] = (string )$text;
          }

          return $sections;
      }

      /**
       * Lang::fetchLanguage()
       * 
       * @return
       */
      public static function fetchLanguage()
      {
          $lang_array = '';
          $directory = BASEPATH . self::langdir;
          if (!is_dir($directory)) {
              return false;
          } else {
              $lang_array = glob($directory . "*", GLOB_ONLYDIR);
              $lang_array = str_replace($directory, "", $lang_array);

          }

          return $lang_array;
      }

      /**
       * Lang:::langIcon()
       * 
       * @return
       */
      public static function langIcon()
      {
          return "<div class=\"wojo primary tiny button\">" . strtoupper(Core::$language) . "</div>";
      }
  }