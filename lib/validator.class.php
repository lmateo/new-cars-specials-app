<?php
  /**
   * Validator Class
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: validator.class.php, v1.00 2014-04-20 18:20:24 gewa Exp $
   */

  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');


  class Validator
  {


      private $validation_rules = array();
      public $safe = array();
      private $source = array();
	  private static $instance; 

      /**
       * Validator::__construct()
       * 
       * @return
       */
      private function __construct()
	  {
		  $this->safe = new stdClass();
		  
	  }

      /**
       * Validator::instance()
       * 
       * @return
       */
	  public static function instance(){
		  if (!self::$instance){ 
			  self::$instance = new Validator(); 
		  } 
	  
		  return self::$instance;  
	  }
	  
      /**
       * Validator::addSource()
       * 
       * @param mixed $source
       * @return
       */
      public function addSource($source)
      {
          $this->source = $source;
      }

      /**
       * Validator::run()
       * 
       * @return
       */
      public function run()
      {
          /*** set the vars ***/
          foreach (new ArrayIterator($this->validation_rules) as $var => $opt) {
              if ($opt['required'] == true) {
                  $this->is_set($var);
              }

              /*** Trim whitespace from beginning and end of variable ***/
              $this->source[$var] = trim($this->source[$var]);

              switch ($opt['type']) {
                  case 'email':
                      $this->validateEmail($var, $opt['required'], $opt['msg']);
                      if (!array_key_exists($var, Message::$msgs)) {
                          $this->sanitizeEmail($var);
                      }
                      break;

                  case 'url':
                      $this->validateUrl($var, $opt['required'], $opt['msg']);
                      if (!array_key_exists($var, Message::$msgs)) {
                          $this->sanitizeUrl($var);
                      }
                      break;

                  case 'numeric':
                      $this->validateNumeric($var, $opt['required'], $opt['min'], $opt['max'], $opt['msg']);
                      if (!array_key_exists($var, Message::$msgs)) {
                          $this->sanitizeNumeric($var);
                      }
                      break;

                  case 'string':
                      $this->validateString($var, $opt['required'], $opt['min'], $opt['max'], $opt['msg']);
                      if (!array_key_exists($var, Message::$msgs)) {
                          $this->sanitizeString($var);
                      }
                      break;
					  
                  case 'alpha':
                      $this->validateString($var, $opt['required'], $opt['min'], $opt['max'], $opt['msg']);
                      if (!array_key_exists($var, Message::$msgs)) {
                          $this->sanitizeAlpha($var);
                      }
                      break;

                  case 'date':
                      $this->validateDate($var, $opt['required'], $opt['min'], $opt['max'], $opt['msg']);
                      if (!array_key_exists($var, Message::$msgs)) {
                          $this->sanitizeString($var);
                      }
                      break;
					  
                  case 'float':
                      $this->validateFloat($var, $opt['required'], $opt['msg']);
                      if (!array_key_exists($var, Message::$msgs)) {
                          $this->sanitizeFloat($var);
                      }
                      break;

                  case 'ipv4':
                      $this->validateIpv4($var, $opt['required'], $opt['msg']);
                      if (!array_key_exists($var, Message::$msgs)) {
						  $this->safe->$var = $this->source[$var];
                      }
                      break;

                  case 'ipv6':
                      $this->validateIpv6($var, $opt['required'], $opt['msg']);
                      if (!array_key_exists($var, Message::$msgs)) {
                          $this->safe->$var = $this->source[$var];
                      }
                      break;

                  case 'bool':
                      $this->validateBool($var, $opt['required'], $opt['msg']);
                      if (!array_key_exists($var, Message::$msgs)) {
                          $this->safe->$var = (bool)$this->source[$var];
                      }
                      break;

              }
          }
      }

      /**
       * Validator::addRule()
       * 
       * @param mixed $varname
       * @param mixed $type
       * @param bool $required
       * @param integer $min
       * @param integer $max
       * @param bool $msg
       * @return
       */
      public function addRule($varname, $type, $required = true, $min = 0, $max = 0, $msg = '')
      {
          $this->validation_rules[$varname] = array(
              'type' => $type,
              'required' => $required,
              'min' => $min,
              'max' => $max,
			  'msg' => $msg,
			  );
          return $this;
      }

      /**
       * Validator::AddRules()
       * 
       *		  $rules_array = array(
       *			  'name' => array(
       *				  'type' => 'string',
       *				  'required' => true,
       *				  'min' => 30,
       *				  'max' => 50,
       *				  'msg' => $msg
       *			  ),
       *			  'age' => array(
       *				  'type' => 'numeric',
       *				  'required' => true,
       *				  'min' => 1,
       *				  'max' => 120,
       *				  'msg' => $msg
       *			  )
       *		  );
       * @param mixed $rules_array
       * @return
       */
      public function AddRules(array $rules_array)
      {
          $this->validation_rules = array_merge($this->validation_rules, $rules_array);
      }

      /**
       * Validator::is_set()
       * 
       * @param mixed $var
       * @return
       */
      private function is_set($var)
      {
          if (!isset($this->source[$var])) {
              $this->errors[$var] = $var . ' is not set';
          }
      }

      /**
       * Validator::validateIpv4()
       * 
       * @param mixed $var
       * @param bool $required
       * @return
       */
      private function validateIpv4($var, $required = false, $msg = '')
      {

          if ($required == false && strlen($this->source[$var]) == 0) {
              return true;
          }
          if (filter_var($this->source[$var], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
              Message::$msgs[$var] = ($msg) ? $msg : Lang::$word->IP_R1;
          }
      }

      /**
       * Validator::validateIpv6()
       * 
       * @param mixed $var
       * @param bool $required
       * @return
       */
      public function validateIpv6($var, $required = false, $msg = '')
      {
          if ($required == false && strlen($this->source[$var]) == 0) {
              return true;
          }

          if (filter_var($this->source[$var], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
			  Message::$msgs[$var] = ($msg) ? $msg : Lang::$word->IP_R1;
          }
      }

      /**
       * Validator::validateString()
       * 
       * @param mixed $var
	   * @param bool $required
       * @param integer $min
       * @param integer $max
	   * @param integer $msg
       * @return
       */
      private function validateString($var, $required = false, $min = 0, $max = 0, $msg = '')
      {
          if ($required == false) {
              return true;
          }

          if (isset($this->source[$var])) {
			  if (strlen($this->source[$var]) < $min) {
				  Message::$msgs[$var] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R1 . '(' . Lang::$word->MIN_R1 . ' <b>' . $min . '</b>)';
			  } elseif (strlen($this->source[$var]) > $max) {
				  Message::$msgs[$var] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R2 . '(' . Lang::$word->MAX_R1 . ' <b>' . $max . '</b>)';
			  } elseif (!is_string($this->source[$var])) {
				  Message::$msgs[$var] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R3;
			  }
          }
      }

      /**
       * Validator::validateNumeric()
       * 
       * @param mixed $var
	   * @param bool $required
       * @param integer $min
       * @param integer $max
	   * @param integer $msg
       * @return
       */
      private function validateNumeric($var, $required = false, $min = 0, $max = 0, $msg = '')
      {
          if ($required == false) {
              return true;
          }

          if (isset($this->source[$var])) {
			  if (strlen($this->source[$var]) < $min) {
				  Message::$msgs[$var] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R1 . '(' . Lang::$word->MIN_R1 . ' <b>' . $min . '</b>)';
			  } elseif (strlen($this->source[$var]) > $max) {
				  Message::$msgs[$var] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R2 . '(' . Lang::$word->MAX_R1 . ' <b>' . $max . '</b>)';
			  } elseif (!is_numeric($this->source[$var])) {
				  Message::$msgs[$var] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R5;
			  }
          }
      }

      /**
       * Validator::validateFloat()
       * 
       * @param mixed $var
	   * @param bool $required
       * @param integer $msg
       * @return
       */
	  private function validateFloat($var, $required = false, $msg = '')
      {
          if ($required == false && strlen($this->source[$var]) == 0) {
              return true;
          }
          if (filter_var($this->source[$var], FILTER_VALIDATE_FLOAT) === false) {
              Message::$msgs[$var] = ($msg) ? $msg : str_replace("[NAME]", $var, Lang::$word->NUMBER_R1);
          }
      }
	  
      /**
       * Validator::validateDate()
       * 
       * @param mixed $var
	   * @param bool $required
       * @param integer $format
       * @param integer $max
       * @return
       */
	  private function validateDate($var, $required = false, $format = 'Y-m-d H:i:s', $max = 0, $msg = '')
	  {
		  if ($required == false) {
			  return true;
		  } else {
			  if(self::dateCheck($this->source[$var], $format) == 0){
			      Message::$msgs[$var] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R4;
		       }
		  }
	  }

	  
      /**
       * Validator::validateUrl()

       * 
       * @param mixed $var
       * @param bool $required
	   * @param integer $msg
       * @return
       */
      private function validateUrl($var, $required = false, $msg = '')
      {
          if ($required == false) {
              return true;
          }
          if (filter_var($this->source[$var], FILTER_VALIDATE_URL) === false) {
			  Message::$msgs[$var] = ($msg) ? $msg : Lang::$word->URL_R1;
          }
      }

      /**
       * Validator::validateEmail()
       * 
       * @param mixed $var
       * @param bool $required
	   * @param integer $msg
       * @return
       */
      private function validateEmail($var, $required = false, $msg = '')
      {
          if ($required == false) {
              return true;
          }
          if (filter_var($this->source[$var], FILTER_VALIDATE_EMAIL) === false) {
              Message::$msgs[$var] = ($msg) ? $msg : Lang::$word->EMAIL_R3;
          }
      }

      /**
       * Validator::validateBool()
       * 
       * @param mixed $var
       * @param bool $required
	   * @param integer $msg
       * @return
       */
      private function validateBool($var, $required = false, $msg = '')
      {
          if ($required == false && strlen($this->source[$var]) == 0) {
              return true;
          }
          filter_var($this->source[$var], FILTER_VALIDATE_BOOLEAN);          {
			  Message::$msgs[$var] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R3;
          }
      }
	  
      /**
       * Validator::sanitizeEmail()
       * 
       * @param mixed $var
       * @return
       */
      public function sanitizeEmail($var)
      {
          $email = preg_replace('((?:\n|\r|\t|%0A|%0D|%08|%09)+)i', '', $this->source[$var]);
          $this->safe->$var = (string )filter_var($email, FILTER_SANITIZE_EMAIL);
      }

      /**
       * Validator::sanitizeUrl()
       * 
       * @param mixed $var
       * @return
       */
      private function sanitizeUrl($var)
      {
          $this->safe->$var = (string )filter_var($this->source[$var], FILTER_SANITIZE_URL);
      }

      /**
       * Validator::sanitizeNumeric()
       * 
       * @param mixed $var
       * @return
       */
      private function sanitizeNumeric($var)
      {
          $this->safe->$var = (int)filter_var($this->source[$var], FILTER_SANITIZE_NUMBER_INT);
      }

      /**
       * Validator::sanitizeFloat()
       * 
       * @param mixed $var
       * @return
       */
      private function sanitizeFloat($var)
      {
          $this->safe->$var = filter_var($this->source[$var], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      }
	  
      /**
       * Validator::sanitizeString()
       * 
       * @param mixed $var
       * @return
       */
      private function sanitizeString($var)
      {
          $this->safe->$var = (string )filter_var($this->source[$var], FILTER_SANITIZE_STRING);
      }

      /**
       * Validator::sanitizeAlpha()
       * 
       * @param mixed $var
       * @return
       */
      private function sanitizeAlpha($var)
      {
          $this->safe->$var = self::sanitize($this->source[$var], "alpha");
      }
	  
      /**
       * Validator::sanitize()
       * 
       * @param mixed $data
       * @param string $type
       * @param bool $trim
       * @return
       */
      public static function sanitize($data, $type = 'default', $trim = false)
      {
          switch ($type) {
              case "string":
                  return filter_var($data, FILTER_SANITIZE_STRING);
                  break;

              case "search":
			      $data = str_replace(array('_', '%'), array('', ''), $data);
                  return filter_var($data, FILTER_SANITIZE_STRING);
                  break;
				  
              case "email":
                  return filter_var($data, FILTER_SANITIZE_EMAIL);
                  break;

              case "url":
                  return filter_var($data, FILTER_SANITIZE_URL);
                  break;

              case "alpha":
                  return preg_replace('/[^A-Za-z]/', '', $data);
                  break;

              case "alphanumeric":
                  return preg_replace('/[^A-Za-z0-9]/', '', $data);
                  break;
				  
              case "time":
			  case "digits":
                  return preg_replace('/[^0-9]/', '', $data);
                  break;

              case "int":
                  return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
                  break;

              case "float":
                  return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                  break;

              case "db":
                  return preg_replace('/[^A-Za-z0-9_\-]/', '', $data);
                  break;

              case "default":
                  $data = filter_var($data, FILTER_SANITIZE_STRING);
                  $data = trim($data);
                  $data = stripslashes($data);
                  $data = strip_tags($data);
                  $data = str_replace(array(
                      'â€˜',
                      'â€™',
                      'â€œ',
                      'â€�'), array(
                      "'",
                      "'",
                      '"',
                      '"'), $data);
                  if ($trim)
                      $data = substr($data, 0, $trim);
                  return $data;
                  break;
          }
      }

	  /**
	   * Validator::compareFloatNumbers()
	   * 
	   * @param mixed $float1
	   * @param mixed $float2
	   * @param string $operator
	   * @return
	   */
	  public static function compareFloatNumbers($float1, $float2, $operator='=')  
	  {  
		  // Check numbers to 5 digits of precision  
		  $epsilon = 0.00001;  
			
		  $float1 = (float)$float1;  
		  $float2 = (float)$float2;  
			
		  switch ($operator)  
		  {  
			  // equal  
			  case "=":  
			  case "eq":  
				  if (abs($float1 - $float2) < $epsilon) {  
					  return true;  
				  }  
				  break;    
			  // less than  
			  case "<":  
			  case "lt":  
				  if (abs($float1 - $float2) < $epsilon) {  
					  return false;  
				  } else {  
					  if ($float1 < $float2) {  
						  return true;  
					  }  
				  }  
				  break;    
			  // less than or equal  
			  case "<=":  
			  case "lte":  
				  if (self::compareFloatNumbers($float1, $float2, '<') || self::compareFloatNumbers($float1, $float2, '=')) {  
					  return true;  
				  }  
				  break;    
			  // greater than  
			  case ">":  
			  case "gt":  
				  if (abs($float1 - $float2) < $epsilon) {  
					  return false;  
				  } else {  
					  if ($float1 > $float2) {  
						  return true;  
					  }  
				  }  
				  break;    
			  // greater than or equal  
			  case ">=":  
			  case "gte":  
				  if (self::compareFloatNumbers($float1, $float2, '>') || self::compareFloatNumbers($float1, $float2, '=')) {  
					  return true;  
				  }  
				  break;    
			
			  case "<>":  
			  case "!=":  
			  case "ne":  
				  if (abs($float1 - $float2) > $epsilon) {  
					  return true;  
				  }  
				  break;    
			  default:  
				  die("Unknown operator '".$operator."' in compareFloatNumbers()");    
		  }  
			
		  return false;  
	  } 
  
      /**
       * Validator::truncate()
       * 
       * @param mixed $string
       * @param mixed $length
       * @param bool $ellipsis
       * @return
       */
      public static function truncate($string, $length, $ellipsis = true)
      {
          $wide = mb_strlen(preg_replace('/[^A-Z0-9_@#%$&]/', '', $string));
          $length = round($length - $wide * 0.2);
          $clean_string = preg_replace('/&[^;]+;/', '-', $string);
          if (mb_strlen($clean_string) <= $length)
              return $string;
          $difference = $length - mb_strlen($clean_string);
          $result = mb_substr($string, 0, $difference);
          if ($result != $string and $ellipsis) {
              $result = self::add_ellipsis($result);
          }
          return $result;
      }

      /**
       * Validator::add_ellipsis()
       * 
       * @param mixed $string
       * @return
       */
      public static function add_ellipsis($string)
      {
          $string = mb_substr($string, 0, mb_strlen($string) - 3);
          return trim(preg_replace('/ .{1,3}$/', '', $string)) . '...';
      }

      /**
       * Validator::alphaBits()
       * 
       * @param bool $all
       * @param mixed $vars
       * @param string $class
       * @return
       */
      public static function alphaBits($all = false, $vars, $class = "small pagination menu")
      {
          if (!empty($_SERVER['QUERY_STRING'])) {
              $parts = explode("/", $_SERVER['QUERY_STRING']);
              $vars = str_replace(" ", "", $vars);
              $c_vars = explode(",", $vars);
              $newParts = array();
              foreach ($parts as $val) {
                  $val_parts = explode("=", $val);
                  if (!in_array($val_parts[0], $c_vars)) {
                      array_push($newParts, $val);
                  }
              }
              if (count($newParts) != 0) {
                  $qs = "/" . implode("/", $newParts);
              } else {
                  return false;
              }

              $html = '';
              $charset = explode(",", Lang::$word->CHARSET);
              $html .= "<div class=\"wojo $class\">\n";
              foreach ($charset as $key) {
                  $active = ($key == self::get('letter')) ? ' active' : null;
                  $html .= "<a class=\"item$active\" href=\"" . $all . "?letter=" . $key . "\">" . $key . "</a>\n";
              }
              $viewAll = ($all === false) ? self::phpself() : $all;
              $active = ($key == !self::get('letter')) ? ' active' : null;
              $html .= "<a class=\"item$active\" href=\"" . $viewAll . "\">" . Lang::$word->ALL . "</a>\n";
              $html .= "</div>\n";
              unset($key);

              return $html;
          } else {
              return false;
          }
      }
      
      /**
       * Validator::alphaBitsLeter()
       * query string has no (?)
       * @param bool $all
       * @param mixed $vars
       * @param string $class
       * @param with no (?) on letter
       * @return
  */
      public static function alphaBitsLetter($all = false, $vars, $class = "small pagination menu")
      {
      	if (!empty($_SERVER['QUERY_STRING'])) {
      		$parts = explode("/", $_SERVER['QUERY_STRING']);
      		$vars = str_replace(" ", "", $vars);
      		$c_vars = explode(",", $vars);
      		$newParts = array();
      		foreach ($parts as $val) {
      			$val_parts = explode("=", $val);
      			if (!in_array($val_parts[0], $c_vars)) {
      				array_push($newParts, $val);
      			}
      		}
      		if (count($newParts) != 0) {
      			$qs = "/" . implode("/", $newParts);
      		} else {
      			return false;
      		}
      
      		$html = '';
      		$charset = explode(",", Lang::$word->CHARSET);
      		$html .= "<div class=\"wojo $class\">\n";
      		foreach ($charset as $key) {
      			$active = ($key == self::get('letter')) ? ' active' : null;
      			$html .= "<a class=\"item$active\" href=\"" . $all . "letter=" . $key . "\">" . $key . "</a>\n";
      		}
      		$viewAll = ($all === false) ? self::phpself() : $all;
      		$active = ($key == !self::get('letter')) ? ' active' : null;
      		$html .= "<a class=\"item$active\" href=\"" . $viewAll . "\">" . Lang::$word->ALL . "</a>\n";
      		$html .= "</div>\n";
      		unset($key);
      
      		return $html;
      	} else {
      		return false;
      	}
      }

      /**
       * Validator::isValidEmail()
       * 
       * @param mixed $email
       * @return
       */
      public static function isValidEmail($email)
      {
          if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			  return true;
		  } else
			  return false;

      }

      /**
       * Validator::isValidIP()
       * 
       * @param mixed $ip
       * @return
       */
      public static function isValidIP($ip)
      {
          if (filter_var($ip, FILTER_VALIDATE_IP)) {
			  return true;
		  } else
			  return false;

      }

      /**
       * Validator::cleanOut()
       * 
       * @param mixed $text
       * @return
       */
      public static function cleanOut($data)
      {

          $data = strtr($data, array(
              '\r\n' => "",
              '\r' => "",
              '\n' => ""));
          $data = html_entity_decode($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
          return stripslashes($data);
      }

      /**
       * Validator::dateCheck()
       * 
       * @param mixed $date
       * @param mixed $format
       * @return
       */
	  public static function dateCheck($date, $format)
	  {
		  $d = DateTime::createFromFormat($format, $date);
		  return $d && $d->format($format) == $date;
	  }

      /**
       * Validator::getChecked()
       * 
       * @param mixed $row
       * @param mixed $status
       * @return
       */
      public static function getChecked($row, $status)
      {
          if ($row == $status) {
              echo "checked=\"checked\"";
          }
      }

      /**
       * Validator::post()
       * 
       * @param mixed $var
       * @return
       */
      public static function post($var)
      {
          if (isset($_POST[$var]))
              return $_POST[$var];
      }

      /**
       * Validator::notEmptyPost()
       * 
       * @param mixed $var
       * @return
       */
      public static function notEmptyPost($var)
      {
          if (!empty($_POST[$var]))
              return $_POST[$var];
      }
	  
      /**
       * Validator::get()
       * 
       * @param mixed $var
       * @return
       */
      public static function get($var)
      {
          if (isset($_GET[$var]))
              return $_GET[$var];
      }

      /**
       * Validator::notEmptyGet()
       * 
       * @param mixed $var
       * @return
       */
      public static function notEmptyGet($var)
      {
          if (!empty($_GET[$var]))
              return $_GET[$var];
      }
	  
	  /**
	   * Validator::has()
	   * 
	   * @param mixed $value
	   * @param mixed $string
	   * @return
	   */
	  public static function has($value, $string = '-/-')
	  {
		  return ($value) ? $value : $string;
	  }
	  
      /**
       * Validator::phpself()
       * 
       * @return
       */
      public static function phpself()
      {
          return htmlspecialchars($_SERVER['PHP_SELF']);
      }

      /**
       * Validator::checkPost()
       * 
       * @param mixed $index
       * @param mixed $msg
       * @return
       */
      public static function checkPost($index, $msg)
      {

          if (empty($_POST[$index]))
              Message::$msgs[$index] = $msg;
      }

      /**
       * Validator::checkSetPost()
       * 
       * @param mixed $index
       * @param mixed $msg
       * @return
       */
      public static function checkSetPost($index, $msg)
      {

          if (!isset($_POST[$index]))
              Message::$msgs[$index] = $msg;
      }


  }