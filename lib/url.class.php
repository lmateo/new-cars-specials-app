<?php
  /**
   * Url Class
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: url.class.php, v1.00 2014-04-20 18:20:24 gewa Exp $
   */

  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  class Url
  {

      /**
       * Url::__construct()
       * 
       * @return
       */
      public function __construct()
      {
      }


      /**
       * Url::redirect()
       * 
       * @param mixed $location
       * @return
       */
      public static function redirect($location)
      {
          if (!headers_sent()) {
              header('Location: ' . $location);
              exit;
          } else
              echo '<script type="text/javascript">';
          echo 'window.location.href="' . $location . '";';
          echo '</script>';
          echo '<noscript>';
          echo '<meta http-equiv="refresh" content="0;url=' . $location . '" />';
          echo '</noscript>';
      }

      /**
       * Url::protocol()
       * 
       * @return
       */
      public static function protocol()
      {
          if (isset($_SERVER['HTTPS'])) {
              $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
          } elseif(isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'){
			  $protocol = 'https';
		  } else {
              $protocol = 'http';
          }

          return $protocol;
      }

      /**
       * Url::adminUrl()
       * 
       * @param mixed $section
       * @param bool $action
       * @param bool $id
       * @param bool $pars
       * @return
       */
      public static function adminUrl($section, $action = false, $id = false, $pars = false)
      {
          $act = ($action) ? $action . '/' : null;
          $aid = ($id) ? $id : null;
          $param = ($pars) ? $pars : null;

          return ADMINURL . '/' . $section . '/' . $act . $aid . $param;
      }

      /**
       * Url::adminAction()
       * 
       * @param mixed $part
       * @param mixed $action
       * @return
       */
      public static function adminAction($part, $action)
      {
          $result = (isset(App::get("Core")->_url[$part])) ? true : false;

          return $result;
      }

      /**
       * Url::getIP()
       * 
       * @return
       */
	  public static function getIP() {
		  $ipaddress = '';
		  if (getenv('HTTP_CLIENT_IP'))
			  $ipaddress = getenv('HTTP_CLIENT_IP');
		  else if(getenv('HTTP_X_FORWARDED_FOR'))
			  $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		  else if(getenv('HTTP_X_FORWARDED'))
			  $ipaddress = getenv('HTTP_X_FORWARDED');
		  else if(getenv('HTTP_FORWARDED_FOR'))
			  $ipaddress = getenv('HTTP_FORWARDED_FOR');
		  else if(getenv('HTTP_FORWARDED'))
			 $ipaddress = getenv('HTTP_FORWARDED');
		  else if(getenv('REMOTE_ADDR'))
			  $ipaddress = getenv('REMOTE_ADDR');
		  else
			  $ipaddress = 'UNKNOWN';
			  
		  return $ipaddress;
	  }
      /**
       * Url::doUrl()
       * 
       * @param mixed $section
	   * @param bool $vals
       * @param bool $pars
       * @return
       */
      public static function doUrl($section, $vals = '', $pars = '')
      {
          switch($section) {
			  case URL_ITEM :  
			    return SITEURL . '/' . URL_ITEM . '/' . $vals . '/' . $pars;
			  break;

			  case URL_SELLER : 
			    return SITEURL . '/' . URL_SELLER . '/' . $vals . '/' . $pars;
			  break;
			  
			  case URL_BRAND : 
			    return SITEURL . '/' . URL_BRAND . '/' . $vals . '/' . $pars;
			  break;

			  case URL_BRANDS : 
			    return SITEURL . '/' . URL_BRANDS . '/' . $pars;
			  break;

			  case URL_BODY : 
				return SITEURL . '/' . URL_BODY . '/' . $vals . '/' . $pars;
			  break;
			  
			  case URL_PAGE : 
			    return SITEURL . '/' . URL_PAGE . '/' . $vals . '/' . $pars;
			  break;

			  case URL_SEARCH : 
			    return SITEURL . '/' . URL_SEARCH . '/' . $pars;
			  break;

			  case URL_LISTINGS : 
			    return SITEURL . '/' . URL_LISTINGS . '/' . $pars;
			  break;
			  
			  case URL_LOGIN : 
			    return SITEURL . '/' . URL_LOGIN . '/' . $pars;
			  break;

			  case URL_ACCOUNT : 
			    return SITEURL . '/' . URL_ACCOUNT . '/' . $pars;
			  break;

			  case URL_MYLISTINGS : 
			    return SITEURL . '/' . URL_MYLISTINGS . '/' . $pars;
			  break;

			  case URL_MYSETTINGS : 
			    return SITEURL . '/' . URL_MYSETTINGS . '/' . $pars;
			  break;

			  case URL_ADDLISTING : 
			    return SITEURL . '/' . URL_ADDLISTING . '/' . $pars;
			  break;
			  
			  case URL_MYLOCATIONS : 
			    return SITEURL . '/' . URL_MYLOCATIONS . '/' . $pars;
			  break;

			  case URL_MYREVIEWS : 
			    return SITEURL . '/' . URL_MYREVIEWS . '/' . $pars;
			  break;
			  
			  case URL_EDIT : 
			    return SITEURL . '/' . URL_EDIT . '/' . $pars;
			  break;
			    
			  case URL_REGISTER : 
			    return SITEURL . '/' . URL_REGISTER . '/' . $pars;
			  break;
		  }
      }
	  
      /**
       * Url::doSeo()
       * 
       * @param mixed $string
       * @return $string
       */
      public static function doSeo($string, $maxlen = 0)
      {
          $newStringTab = array();
          $string = Validator::cleanOut($string);
          $string = strtolower(self::doChars($string));
          $stringTab = str_split($string);
          $numbers = array(
              "0",
              "1",
              "2",
              "3",
              "4",
              "5",
              "6",
              "7",
              "8",
              "9",
              "-");

          foreach ($stringTab as $letter) {
              if (in_array($letter, range("a", "z")) || in_array($letter, $numbers)) {
                  $newStringTab[] = $letter;
              } elseif ($letter == " ") {
                  $newStringTab[] = "-";
              }

          }

          if (count($newStringTab)) {
              $newString = implode($newStringTab);
              if ($maxlen > 0) {
                  $newString = substr($newString, 0, $maxlen);
              }

              $newString = self::remDupes('--', '-', $newString);
          } else {
              $newString = '';
          }

          return $newString;

      }

      /**
       * Url::sortItems()
       * 
	   * @param mixed $url
	   * @param mixed $action
       * @return
       */
      public static function sortItems($url, $action)
      {
          if(isset($_GET[$action])) {
			  $data = explode("/", $_GET[$action]);
			  if(count($data == 2)) {
				  $result = ($data[1] == "DESC") ? "ASC" : "DESC";
				  return $url . "?$action=" . $data[0] . "/" . $result;
			  }
		  }
      }
      
      /**
       * Url::sortItemsView()
       *
       * @param mixed $url
       * @param mixed $action
       * @return
       */
      public static function sortItemsView($url, $action)
      {
      	if(isset($_GET[$action])) {
      		$data = explode("/", $_GET[$action]);
      		if(count($data == 2)) {
      			$result = ($data[1] == "DESC") ? "ASC" : "DESC";
      			return $url . "&$action=" . $data[0] . "/" . $result;
      		}
      	}
      }
	  
      /**
       * Url::setActive()
       * 
	   * @param mixed $action
	   * @param mixed $name
       * @return
       */
      public static function setActive($action, $name)
      {
          if(isset($_GET[$action])) {
			  $data = explode("/", $_GET[$action]);
			  if(count($data == 2)) {
				  return ($data[0] == $name) ? " active" : "";
			  }
		  } elseif(empty($_GET[$action]) and $name == false){
			  return " active";
		  }
      }
	  
      /**
       * Url::buildUrl()
       * 
	   * @param mixed $key
	   * @param mixed $value
	   * @param mixed $option
       * @return
       */
	  public static function buildUrl($key, $value, $option = "filter")
	  {
		  $parts = parse_url($_SERVER['REQUEST_URI']);
		  if (isset($parts['query'])) {
			  parse_str($parts['query'], $qs);
		  } else {
			  $qs = array();
		  }
		  $qs[$option] = "true";
		  $qs[$key] = $value;
		  return "?" . $parts['query'] = http_build_query($qs);
	  }
  
      /**
       * Url::remDupes()
       * 
       * @param mixed $sSearch
       * @param mixed $sReplace
       * @param mixed $sSubject
       * @return
       */
      private static function remDupes($sSearch, $sReplace, $sSubject)
      {
          $i = 0;
          do {

              $sSubject = str_replace($sSearch, $sReplace, $sSubject);
              $pos = strpos($sSubject, $sSearch);

              $i++;
              if ($i > 100) {
                  die('remDupes() loop error');
              }

          } while ($pos !== false);

          return $sSubject;
      }

      /**
       * Url::doChars()
       * 
       * @param mixed $string
       * @return
       */
      private static function doChars($string)
      {
          //cyrylic transcription
          $cyrylicFrom = array(
              'Ð�',
              'Ð‘',
              'Ð’',
              'Ð“',
              'Ð”',
              'Ð•',
              'Ð�',
              'Ð–',
              'Ð—',
              'Ð˜',
              'Ð™',
              'Ðš',
              'Ð›',
              'Ðœ',
              'Ð�',
              'Ðž',
              'ÐŸ',
              'Ð ',
              'Ð¡',
              'Ð¢',
              'Ð£',
              'Ð¤',
              'Ð¥',
              'Ð¦',
              'Ð§',
              'Ð¨',
              'Ð©',
              'Ðª',
              'Ð«',
              'Ð¬',
              'Ð­',
              'Ð®',
              'Ð¯',
              'Ð°',
              'Ð±',
              'Ð²',
              'Ð³',
              'Ð´',
              'Ðµ',
              'Ñ‘',
              'Ð¶',
              'Ð·',
              'Ð¸',
              'Ð¹',
              'Ðº',
              'Ð»',
              'Ð¼',
              'Ð½',
              'Ð¾',
              'Ð¿',
              'Ñ€',
              'Ñ�',
              'Ñ‚',
              'Ñƒ',
              'Ñ„',
              'Ñ…',
              'Ñ†',
              'Ñ‡',
              'Ñˆ',
              'Ñ‰',
              'ÑŠ',
              'Ñ‹',
              'ÑŒ',
              'Ñ�',
              'ÑŽ',
              'Ñ�');
          $cyrylicTo = array(
              'A',
              'B',
              'V',
              'G',
              'D',
              'Ie',
              'Io',
              'Z',
              'Z',
              'I',
              'J',
              'K',
              'L',
              'M',
              'N',
              'O',
              'P',
              'R',
              'S',
              'T',
              'U',
              'F',
              'Ch',
              'C',
              'Tch',
              'Sh',
              'Shtch',
              '',
              'Y',
              '',
              'E',
              'Iu',
              'Ia',
              'a',
              'b',
              'v',
              'g',
              'd',
              'ie',
              'io',
              'z',
              'z',
              'i',
              'j',
              'k',
              'l',
              'm',
              'n',
              'o',
              'p',
              'r',
              's',
              't',
              'u',
              'f',
              'ch',
              'c',
              'tch',
              'sh',
              'shtch',
              '',
              'y',
              '',
              'e',
              'iu',
              'ia');

          $from = array(
              "Ã�",
              "Ã€",
              "Ã‚",
              "Ã„",
              "Ä‚",
              "Ä€",
              "Ãƒ",
              "Ã…",
              "Ä„",
              "Ã†",
              "Ä†",
              "ÄŠ",
              "Äˆ",
              "ÄŒ",
              "Ã‡",
              "ÄŽ",
              "Ä�",
              "Ã�",
              "Ã‰",
              "Ãˆ",
              "Ä–",
              "ÃŠ",
              "Ã‹",
              "Äš",
              "Ä’",
              "Ä˜",
              "Æ�",
              "Ä ",
              "Äœ",
              "Äž",
              "Ä¢",
              "Ã¡",
              "Ã ",
              "Ã¢",
              "Ã¤",
              "Äƒ",
              "Ä�",
              "Ã£",
              "Ã¥",
              "Ä…",
              "Ã¦",
              "Ä‡",
              "Ä‹",
              "Ä‰",
              "Ä�",
              "Ã§",
              "Ä�",
              "Ä‘",
              "Ã°",
              "Ã©",
              "Ã¨",
              "Ä—",
              "Ãª",
              "Ã«",
              "Ä›",
              "Ä“",
              "Ä™",
              "É™",
              "Ä¡",
              "Ä�",
              "ÄŸ",
              "Ä£",
              "Ä¤",
              "Ä¦",
              "I",
              "Ã�",
              "ÃŒ",
              "Ä°",
              "ÃŽ",
              "Ã�",
              "Äª",
              "Ä®",
              "Ä²",
              "Ä´",
              "Ä¶",
              "Ä»",
              "Å�",
              "Åƒ",
              "Å‡",
              "Ã‘",
              "Å…",
              "Ã“",
              "Ã’",
              "Ã”",
              "Ã–",
              "Ã•",
              "Å�",
              "Ã˜",
              "Æ ",
              "Å’",
              "Ä¥",
              "Ä§",
              "Ä±",
              "Ã­",
              "Ã¬",
              "i",
              "Ã®",
              "Ã¯",
              "Ä«",
              "Ä¯",
              "Ä³",
              "Äµ",
              "Ä·",
              "Ä¼",
              "Å‚",
              "Å„",
              "Åˆ",
              "Ã±",
              "Å†",
              "Ã³",
              "Ã²",
              "Ã´",
              "Ã¶",
              "Ãµ",
              "Å‘",
              "Ã¸",
              "Æ¡",
              "Å“",
              "Å”",
              "Å˜",
              "Åš",
              "Åœ",
              "Å ",
              "Åž",
              "Å¤",
              "Å¢",
              "Ãž",
              "Ãš",
              "Ã™",
              "Ã›",
              "Ãœ",
              "Å¬",
              "Åª",
              "Å®",
              "Å²",
              "Å°",
              "Æ¯",
              "Å´",
              "Ã�",
              "Å¶",
              "Å¸",
              "Å¹",
              "Å»",
              "Å½",
              "Å•",
              "Å™",
              "Å›",
              "Å�",
              "Å¡",
              "ÅŸ",
              "ÃŸ",
              "Å¥",
              "Å£",
              "Ã¾",
              "Ãº",
              "Ã¹",
              "Ã»",
              "Ã¼",
              "Å­",
              "Å«",
              "Å¯",
              "Å³",
              "Å±",
              "Æ°",
              "Åµ",
              "Ã½",
              "Å·",
              "Ã¿",
              "Åº",
              "Å¼",
              "Å¾");
          $to = array(
              "A",
              "A",
              "A",
              "A",
              "A",
              "A",
              "A",
              "A",
              "A",
              "AE",
              "C",
              "C",
              "C",
              "C",
              "C",
              "D",
              "D",
              "D",
              "E",
              "E",
              "E",
              "E",
              "E",
              "E",
              "E",
              "E",
              "G",
              "G",
              "G",
              "G",
              "G",
              "a",
              "a",
              "a",
              "a",
              "a",
              "a",
              "a",
              "a",
              "a",
              "ae",
              "c",
              "c",
              "c",
              "c",
              "c",
              "d",
              "d",
              "d",
              "e",
              "e",
              "e",
              "e",
              "e",
              "e",
              "e",
              "e",
              "g",
              "g",
              "g",
              "g",
              "g",
              "H",
              "H",
              "I",
              "I",
              "I",
              "I",
              "I",
              "I",
              "I",
              "I",
              "IJ",
              "J",
              "K",
              "L",
              "L",
              "N",
              "N",
              "N",
              "N",
              "O",
              "O",
              "O",
              "O",
              "O",
              "O",
              "O",
              "O",
              "CE",
              "h",
              "h",
              "i",
              "i",
              "i",
              "i",
              "i",
              "i",
              "i",
              "i",
              "ij",
              "j",
              "k",
              "l",
              "l",
              "n",
              "n",
              "n",
              "n",
              "o",
              "o",
              "o",
              "o",
              "o",
              "o",
              "o",
              "o",
              "o",
              "R",
              "R",
              "S",
              "S",
              "S",
              "S",
              "T",
              "T",
              "T",
              "U",
              "U",
              "U",
              "U",
              "U",
              "U",
              "U",
              "U",
              "U",
              "U",
              "W",
              "Y",
              "Y",
              "Y",
              "Z",
              "Z",
              "Z",
              "r",
              "r",
              "s",
              "s",
              "s",
              "s",
              "B",
              "t",
              "t",
              "b",
              "u",
              "u",
              "u",
              "u",
              "u",
              "u",
              "u",
              "u",
              "u",
              "u",
              "w",
              "y",
              "y",
              "y",
              "z",
              "z",
              "z");
          $from = array_merge($from, $cyrylicFrom);
          $to = array_merge($to, $cyrylicTo);

          $newstring = str_replace($from, $to, $string);
          return $newstring;
      }

      /**
       * Url::isPartSet()
       * 
       * @param mixed $part
       * @param mixed $page
       * @return
       */
      public static function isPartSet($part, $page)
      {
          return (isset(App::get("Core")->_url[$part]) and in_array(App::get("Core")->_url[$part], $page)) ? true : false;

      }

      /**
       * Url::getAction()
       * 
       * @return
       */
      public static function getAction()
      {
          if (isset(App::get("Core")->_url[2])) {
              $action = ((string )App::get("Core")->_url[2]) ? (string )App::get("Core")->_url[2] : false;
              $action = Validator::sanitize($action, "string");

              if ($action == false) {
                  Message::invalid("Action Method" . $action);
              } else
                  return $action;
          }
      }

      /**
       * Url::getOption()
       * 
       * @return
       */
      public static function getOption()
      {
          if (isset(App::get("Core")->_url[1])) {
              $action = ((string )App::get("Core")->_url[1]) ? (string )App::get("Core")->_url[1] : false;
              $action = Validator::sanitize($action, "string");

              if ($action == false) {
                  Message::invalid("Option Method" . $action);
              } else
                  return $action;
          }
      }
  }