<?php
  /**
   * Utility Class
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: session.class.php, v1.00 2014-10-20 18:20:24 gewa Exp $
   */

  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');


  class Utility
  {

      /**
       * Utility::__construct()
       * 
       * @return
       */
      function __construct()
      {
      }

      /**
       * Utility::status()
       * 
       * @param mixed $status
       * @param mixed $id
       * @return
       */
      public static function status($status, $id)
      {
          switch ($status) {
              case "y":
                  $display = '<span class="wojo positive label">' . Lang::$word->ACTIVE . '</span>';
                  break;

              case "n":
                  $display = '<a data-id="' . $id . '" class="wojo primary label">' . Lang::$word->INACTIVE . '</a>';
                  break;

              case "t":
                  $display = '<span class="wojo black label">' . Lang::$word->PENDING . '</span>';
                  break;

              case "b":
                  $display = '<span class="wojo negative label">' . Lang::$word->BANNED . '</span>';
                  break;
          }

          return $display;
      }

      /**
       * Utility::isActive()
       * 
       * @param mixed $id
       * @return
       */
      public static function isActive($id)
      {
          if ($id == 1) {
              $display = '<i class="rounded icon positive check">';
          } else {
              $display = '<i class="rounded icon negative ban">';
          }

          return $display;
      }

      /**
       * Utility::isPublished()
       * 
       * @param mixed $id
       * @return
       */
      public static function isPublished($id)
      {
          if ($id == 1) {
              $display = '<i class="rounded eicon positive check link">';
          } else {
              $display = '<i class="rounded eicon negative clock link">';
          }

          return $display;
      }

      /**
       * Utility::randName()
       * 
       * @param mixed $char
       * @return
       */
      public static function randName($char = 6)
      {
          $code = '';
          for ($x = 0; $x < $char; $x++) {
              $code .= '-' . substr(strtoupper(sha1(rand(0, 999999999999999))), 2, $char);
          }
          $code = substr($code, 1);
          return $code;
      }

      /**
       * Utility::randNumbers()
       * 
       * @param int $digits
       * @return
       */
      public static function randNumbers($digits = 7)
      {
          $min = pow(10, $digits - 1);
          $max = pow(10, $digits) - 1;
          return mt_rand($min, $max);
      }

      /**
       * Utility::getLogo()
       * 
       * @return
       */
      public static function getLogo()
      {
          if (App::get("Core")->logo) {
              $logo = '<img src="' . UPLOADURL . App::get("Core")->logo . '" alt="' . App::get("Core")->company . '" style="border:0"/>';
          } else {
              $logo = App::get("Core")->company;
          }

          return $logo;
      }

      /**
       * Utility::compareDates()
       * 
       * @param mixed $date1
       * @param mixed $date2
       * @return
       */
      public static function compareDates($date1, $date2)
      {
          $date1 = new DateTime($date1);
          $date2 = new DateTime($date2);

          return ($date1 > $date2) ? true : false;
      }

      /**
       * Utility::doDate()
       * 
       * @param mixed $format
       * @param mixed $date
       * @return
       */
      public static function doDate($format, $date)
      {
          $cal = IntlCalendar::fromDateTime($date);
          if ($format == "long_date" or $format == "short_date") {
              return IntlDateFormatter::formatObject($cal, App::get("Core")->$format);
          } else {
              return IntlDateFormatter::formatObject($cal, $format);
          }
      }
      
      

      /**
       * Utility::doTime()
       * 
       * @param mixed $time
       * @return
       */
      public static function doTime($time)
      {

          $cal = IntlCalendar::fromDateTime($time);
          return IntlDateFormatter::formatObject($cal, App::get("Core")->time_format);
      }

      /**
       * Utility::getShortDate()
       * 
       * @param bool $selected
       * @return
       */
      public static function getShortDate($selected = false)
      {

          $cal = IntlCalendar::fromDateTime(date('Y-m-d H:i:s'));
          $arr = array(
              'MM-dd-yyyy' => IntlDateFormatter::formatObject($cal, 'MM-dd-yyyy'),
              'd-MM-YYYY' => IntlDateFormatter::formatObject($cal, 'd-MM-YYYY'),
              'MM-d-yy' => IntlDateFormatter::formatObject($cal, 'MM-d-yy'),
              'd-MM-yy' => IntlDateFormatter::formatObject($cal, 'd-MM-yy'),
              'dd MMM yyyy' => IntlDateFormatter::formatObject($cal, 'dd MMM yyyy'));

          $shortdate = '';
          foreach ($arr as $key => $val) {
              if ($key == $selected) {
                  $shortdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $shortdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $shortdate;
      }

      /**
       * Utility::getLongDate()
       * 
       * @param bool $selected
       * @return
       */
      public static function getLongDate($selected = false)
      {

          $cal = IntlCalendar::fromDateTime(date('Y-m-d H:i:s'));
          $arr = array(
              'MMMM dd, yyyy hh:mm a' => IntlDateFormatter::formatObject($cal, 'MMMM dd, yyyy hh:mm a'),
              'dd MMMM yyyy hh:mm a' => IntlDateFormatter::formatObject($cal, 'dd MMMM yyyy hh:mm a'),
              'MMMM dd, yyyy' => IntlDateFormatter::formatObject($cal, 'MMMM dd, yyyy'),
              'dd MMMM, yyyy' => IntlDateFormatter::formatObject($cal, 'dd MMMM, yyyy'),
              'EEEE dd MMMM yyyy' => IntlDateFormatter::formatObject($cal, 'EEEE dd MMMM yyyy'),
              'EEEE dd MMMM yyyy HH:mm' => IntlDateFormatter::formatObject($cal, 'EEEE dd MMMM yyyy HH:mm'),
              'EE dd, MMM. yyyy' => IntlDateFormatter::formatObject($cal, 'EE dd, MMM. yyyy'));

          $longdate = '';
          foreach ($arr as $key => $val) {
              if ($key == $selected) {
                  $longdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $longdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $longdate;
      }

      /**
       * Utility::formatMoney()
       * 
       * @param bool $selected
       * @return
       */
      public static function formatMoney($amount, $decimal = false)
      {
          $fmt = new NumberFormatter(App::get('Core')->locale, NumberFormatter::CURRENCY);
          if (!$decimal) {
              $fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, App::get('Core')->currency);
              $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
          }
          return $fmt->formatCurrency($amount, App::get('Core')->currency);
      }

      /**
       * Utility::currencySymbol()
       * 
       * @param bool $selected
       * @return
       */
      public static function currencySymbol()
      {
          $fmt = new NumberFormatter(App::get('Core')->locale, NumberFormatter::CURRENCY);
		  $fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, App::get('Core')->currency);
		  
		  return $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
      }
	  
      /**
       * Utility::formatNumber()
       * 
       * @param bool $selected
       * @return
       */
      public static function formatNumber($number)
      {
		  $fmt = new NumberFormatter(App::get('Core')->locale, NumberFormatter::DECIMAL);
		  return$fmt->format($number);
      }

      /**
       * Utility::getTimeFormat()
       * 
       * @param bool $selected
       * @return
       */
      public static function getTimeFormat($selected = false)
      {
          $cal = IntlCalendar::fromDateTime(date('H:i:s'));
          $arr = array(
              'hh:mm a' => IntlDateFormatter::formatObject($cal, 'hh:mm a'),
              'HH:mm' => IntlDateFormatter::formatObject($cal, 'HH:mm'),
              );

          $longdate = '';
          foreach ($arr as $key => $val) {
              if ($key == $selected) {
                  $longdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $longdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $longdate;
      }

      /**
       * Utility::weekList()
       * 
       * @param bool $list
       * @param bool $long
       * @param bool $selected
       * @return
       */
      public static function weekList($list = true, $long = true, $selected = false)
      {
          $fmt = new IntlDateFormatter(App::get('Core')->locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
          $data = array();

          ($long) ? $fmt->setPattern('EEEE') : $fmt->setPattern('EE');

          for ($i = 0; $i <= 6; $i++) {
              $data[] = $fmt->format(mktime(0, 0, 0, 0, $i, 1970));
          }

          $html = '';
          if ($list) {
              foreach ($data as $key => $val) {
                  $html .= "<option value=\"$key\"";
                  $html .= ($key == $selected) ? ' selected="selected"' : '';
                  $html .= ">$val</option>\n";
              }
          } else {
              $html .= '"' . implode('","', $data) . '"';
          }

          unset($val);
          return $html;
      }

      /**
       * Utility::getPeriod()
       * 
       * @param bool $value
       * @return
       */
      public static function getPeriod($value)
      {
          switch ($value) {
              case "D":
                  return Lang::$word->_DAY;
                  break;
              case "W":
                  return Lang::$word->_WEEK;
                  break;
              case "M":
                  return Lang::$word->_MONTH;
                  break;
              case "Y":
                  return Lang::$word->_YEAR;
                  break;
          }
      }

      /**
       * Utility::getMembershipPeriod()
       * 
       * @param bool $sel
       * @return
       */
      public static function getMembershipPeriod($sel = false)
      {
          $arr = array(
              'D' => Lang::$word->_DAYS,
              'W' => Lang::$word->_WEEKS,
              'M' => Lang::$word->_MONTHS,
              'Y' => Lang::$word->_YEARS);

          $html = '';
          foreach ($arr as $key => $val) {
              if ($key == $sel) {
                  $html .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $html .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $html;
      }

      /**
       * Utility::NumberOfDays()
       * 
       * @param bool $days
	   * eg: +10 day, -1 week
       * @return
       */
      public static function NumberOfDays($days)
      {
		  $date = new DateTime();
		  $date->modify($days);
		  
		  return $date->format('Y-m-d H:i:s');
      }

      /**
       * Utility::today()
       * 
       * @return
       */
      public static function today()
      {
		  $date = new DateTime();
		  
		  return $date->format('Y-m-d H:i:s');
      }
	  
      /**
       * Utility::timesince()
       * 
       * @param bool $datetime
       * @return
       */
      public static function timesince($datetime)
      {

          $today = time();
          $createdday = strtotime($datetime);
          $datediff = abs($today - $createdday);
          $difftext = "";
          $years = floor($datediff / (365 * 60 * 60 * 24));
          $months = floor(($datediff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
          $days = floor(($datediff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
          $hours = floor($datediff / 3600);
          $minutes = floor($datediff / 60);
          $seconds = floor($datediff);
          //year checker
          if ($difftext == "") {
              if ($years > 1)
                  $difftext = $years . " " . Lang::$word->_YEARS . " " . Lang::$word->AGO;
              elseif ($years == 1)
                  $difftext = $years . " " . Lang::$word->_YEAR . " " . Lang::$word->AGO;
          }
          //month checker
          if ($difftext == "") {
              if ($months > 1)
                  $difftext = $months . " " . Lang::$word->_MONTHS . " " . Lang::$word->AGO;
              elseif ($months == 1)
                  $difftext = $months . " " . Lang::$word->_MONTH . " " . Lang::$word->AGO;
          }
          //month checker
          if ($difftext == "") {
              if ($days > 1)
                  $difftext = $days . " " . Lang::$word->_DAYS . " " . Lang::$word->AGO;
              elseif ($days == 1)
                  $difftext = $days . " " . Lang::$word->_DAY . " " . Lang::$word->AGO;
          }
          //hour checker
          if ($difftext == "") {
              if ($hours > 1)
                  $difftext = $hours . " " . Lang::$word->_HOURS . " " . Lang::$word->AGO;
              elseif ($hours == 1)
                  $difftext = $hours . " " . Lang::$word->_HOUR . " " . Lang::$word->AGO;
          }
          //minutes checker
          if ($difftext == "") {
              if ($minutes > 1)
                  $difftext = $minutes . " " . Lang::$word->_MINUTES . " " . Lang::$word->AGO;
              elseif ($minutes == 1)
                  $difftext = $minutes . " " . Lang::$word->_MINUTE . " " . Lang::$word->AGO;
          }
          //seconds checker
          if ($difftext == "") {
              if ($seconds > 1)
                  $difftext = $seconds . " " . Lang::$word->_SECONDS . " " . Lang::$word->AGO;
              elseif ($seconds == 1)
                  $difftext = $seconds . " " . Lang::$word->_SECOND . " " . Lang::$word->AGO;
          }
          return $difftext;

      }

      /**
       * Utility::monthList()
       * 
       * @param bool $list
       * @param bool $long
       * @param bool $selected
       * @return
       */
      public static function monthList($list = true, $long = true, $selected = false)
      {
          $selected = is_null(Validator::get('month')) ? strftime('%m') : Validator::get('month');

          $fmt = new IntlDateFormatter(App::get('Core')->locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
          $data = array();

          ($long) ? $fmt->setPattern('MMMM') : $fmt->setPattern('MMM');

          for ($i = 1; $i <= 12; $i++) {
              $data[] = $fmt->format(mktime(0, 0, 0, $i, 1, 1970));
          }

          $html = '';
          if ($list) {
              foreach ($data as $key => $val) {
                  $html .= "<option value=\"$key\"";
                  $html .= ($key == $selected) ? ' selected="selected"' : '';
                  $html .= ">$val</option>\n";
              }
          } else {
              $html .= '"' . implode('","', $data) . '"';
          }
          unset($val);
          return $html;
      }

      /**
       * Utility::loopOptions()
       * 
       * @param mixed $array
       * @return
       */
      public static function loopOptions($array, $key, $value, $selected = false)
      {
          $html = '';
          if (is_array($array)) {
              foreach ($array as $row) {
                  $html .= "<option value=\"" . $row->$key . "\"";
                  $html .= ($row->$key == $selected) ? ' selected="selected"' : '';
                  $html .= ">" . $row->$value . "</option>\n";
              }
              return $html;
          }
          return false;
      }
      
      /**
       * Utility::loopOptions()
       *
       * @param mixed $array
       * @return
       */
      public static function loopOptionsvalue($array, $key, $value, $selected = false)
      {
      	$html = '';
      	if (is_array($array)) {
      		foreach ($array as $row) {
      			$html .= "<option value=\"" . $row->$value . "\"";
      			$html .= ($row->$value == $selected) ? ' selected="selected"' : '';
      			$html .= ">" . $row->$value . "</option>\n";
      		}
      		return $html;
      	}
      	return false;
      }
      
      /**
       * Utility::loopOptionsmultivalue()
       *
       * @param mixed $array
       * @return
       */
      public static function loopOptionsmultivalue($array, $key, $value, $selected = false)
      {
      	$html = '';
      	if (is_array($array)) {
      		foreach ($array as $row) {
      			$html .= "<option value=\"" . $row->$key . "\"";
      			$html .= ($row->$key == $selected) ? ' selected="selected"' : '';
      			$html .= ">" . $row->$value . "</option>\n";
      		}
      		return $html;
      	}
      	return false;
      }

      /**
       * Utility::loopOptionsSimple()
       * 
       * @param array $array
       * @param bool $selected
       * @return
       */
      public static function loopOptionsSimple($array, $selected = false)
      {
          $html = '';
          if (is_array($array)) {
              foreach ($array as $row) {
                  $html .= "<option value=\"" . $row . "\"";
                  $html .= ($row == $selected) ? ' selected="selected"' : '';
                  $html .= ">" . $row . "</option>\n";
              }
              return $html;
          }
          return false;
      }

      /**
       * Utility::groupToLoop()
       * 
       * @param array $array
       * @param str $key
       * @return
       */
      public static function groupToLoop($array, $key)
      {
          $result = array();
          if (is_array($array)) {
              foreach ($array as $val) {
                  $itemName = $val->{$key};
                  if (!array_key_exists($itemName, $result)) {
                      $result[$itemName] = array();
                  }
                  $result[$itemName][] = $val;
              }
          }

          return $result;
      }

	  /**
	   * Utility::implodeFields()
	   * 
	   * @param mixed $array
	   * @return
	   */
	  public static function implodeFields($array, $sep = ',')
	  {
          if (is_array($array)) {
			  $result = array();
			  foreach ($array as $row) {
				  if ($row != '') {
					  array_push($result, Validator::sanitize($row));
				  }
			  }
			  return implode($sep, $result);
          }
		  return false;
	  }

      /**
       * Utility::unserialToArray()
       * 
       * @param array $array
       * @return
       */
      public static function unserialToArray($array)
      {
          if ($array) {
              $data = unserialize($array);
              return $data;
          }
          return false;
      }

      /**
       * Utility::jSonToArray()
       * 
       * @param array $string
       * @return
       */
      public static function jSonToArray($string)
      {
          if ($string) {
              $data = json_decode($string);
              return $data;
          }
          return false;
      }
	  
	  /**
	   * Utility::doRange()
	   * 
	   * @param mixed $min
	   * @param mixed $max
	   * @param mixed $step
	   * @return
	   */
	  public static function doRange($min, $max, $step, $selected = false)
	  {
		  $html = '';
          foreach (range($min, $max, $step) as $number) {
			  $html .= "<option value=\"" . $number . "\"";
			  $html .= ($number == $selected) ? ' selected="selected"' : '';
			  $html .= ">" . $number . "</option>\n";
          }
		  
		  return $html;
	  }
	  
      /**
       * Utility::sayHello()
       * 
       * @return
       */
      public static function sayHello()
      {
          $welcome = Lang::$word->HI . " ";
          if (date("H") < 12) {
              $welcome .= Lang::$word->HI_M;
          } else
              if (date('H') > 11 && date("H") < 18) {
                  $welcome .= Lang::$word->HI_A;
              } else
                  if (date('H') > 17) {
                      $welcome .= Lang::$word->HI_E;
                  }

          return $welcome;
      }
      
      /**
       * Utility::changeVehicleImg()
       *
       * @return
       */
      public static function changeVehicleImg()
      {
      	$carimg = "";
      	if (date("H") < 12) {
      		$carimg .= "<img src='../uploads/multicarimg/gmcmulticarimg.png' alt=''>";
      	} else
      		if (date('H') > 11 && date("H") < 18) {
      			$carimg .= "<img src='../uploads/multicarimg/fordmulticarimg.png' alt=''>";
      		} else
      			if (date('H') > 17) {
      				$carimg .= "<img src='../uploads/multicarimg/chevroletmulticarimg.png' alt=''>";
      			}
      
      		return $carimg;
      }

      /**
       * Utility::getTimezones()
       * 
       * @return
       */
      public static function getTimezones()
      {
          $data = '';
          $tzone = DateTimeZone::listIdentifiers();
          foreach ($tzone as $zone) {
              $selected = ($zone == App::get('Core')->dtz) ? ' selected="selected"' : '';
              $data .= '<option value="' . $zone . '"' . $selected . '>' . $zone . '</option>';
          }
          return $data;
      }
      
      /**
       * Utility::clean_specialchar()
       *
       * @return
       */
      
      public static function clean_specialchar($string)
      {
      	
      	// Replace other special chars
      	
      	$specialCharacters = array(
      			
      			'&' => '',
      			'?' => '',
      			'+' => '',
      			//'=' => '',
      			'§' => '',
      			
      	);
      
      	while (list($character, $replacement) = each($specialCharacters)) {
      		$string = utf8_decode($string);
      		$string = str_replace($character, '' . $replacement . '', $string);
      	}
      
      	// Remove all remaining other unknown characters
      	$string = preg_replace('/[^\P{C}\n]+/u', "" , $string);
      	
      	
      
      	return $string;
      }

      /**
       * Utility::localeList()
       * 
       * @param bool $selected
       * @return
       */
      public static function localeList($selected = false)
      {
          $data = array(
              'aa_DJ' => 'Afar (Djibouti)',
              'aa_ER' => 'Afar (Eritrea)',
              'aa_ET' => 'Afar (Ethiopia)',
              'af_ZA' => 'Afrikaans (South Africa)',
              'sq_AL' => 'Albanian (Albania)',
              'sq_MK' => 'Albanian (Macedonia)',
              'am_ET' => 'Amharic (Ethiopia)',
              'ar_DZ' => 'Arabic (Algeria)',
              'ar_BH' => 'Arabic (Bahrain)',
              'ar_EG' => 'Arabic (Egypt)',
              'ar_IN' => 'Arabic (India)',
              'ar_IQ' => 'Arabic (Iraq)',
              'ar_JO' => 'Arabic (Jordan)',
              'ar_KW' => 'Arabic (Kuwait)',
              'ar_LB' => 'Arabic (Lebanon)',
              'ar_LY' => 'Arabic (Libya)',
              'ar_MA' => 'Arabic (Morocco)',
              'ar_OM' => 'Arabic (Oman)',
              'ar_QA' => 'Arabic (Qatar)',
              'ar_SA' => 'Arabic (Saudi Arabia)',
              'ar_SD' => 'Arabic (Sudan)',
              'ar_SY' => 'Arabic (Syria)',
              'ar_TN' => 'Arabic (Tunisia)',
              'ar_AE' => 'Arabic (United Arab Emirates)',
              'ar_YE' => 'Arabic (Yemen)',
              'an_ES' => 'Aragonese (Spain)',
              'hy_AM' => 'Armenian (Armenia)',
              'as_IN' => 'Assamese (India)',
              'ast_ES' => 'Asturian (Spain)',
              'az_AZ' => 'Azerbaijani (Azerbaijan)',
              'az_TR' => 'Azerbaijani (Turkey)',
              'eu_FR' => 'Basque (France)',
              'eu_ES' => 'Basque (Spain)',
              'be_BY' => 'Belarusian (Belarus)',
              'bem_ZM' => 'Bemba (Zambia)',
              'bn_BD' => 'Bengali (Bangladesh)',
              'bn_IN' => 'Bengali (India)',
              'ber_DZ' => 'Berber (Algeria)',
              'ber_MA' => 'Berber (Morocco)',
              'byn_ER' => 'Blin (Eritrea)',
              'bs_BA' => 'Bosnian (Bosnia and Herzegovina)',
              'br_FR' => 'Breton (France)',
              'bg_BG' => 'Bulgarian (Bulgaria)',
              'my_MM' => 'Burmese (Myanmar [Burma])',
              'ca_AD' => 'Catalan (Andorra)',
              'ca_FR' => 'Catalan (France)',
              'ca_IT' => 'Catalan (Italy)',
              'ca_ES' => 'Catalan (Spain)',
              'zh_CN' => 'Chinese (China)',
              'zh_HK' => 'Chinese (Hong Kong SAR China)',
              'zh_SG' => 'Chinese (Singapore)',
              'zh_TW' => 'Chinese (Taiwan)',
              'cv_RU' => 'Chuvash (Russia)',
              'kw_GB' => 'Cornish (United Kingdom)',
              'crh_UA' => 'Crimean Turkish (Ukraine)',
              'hr_HR' => 'Croatian (Croatia)',
              'cs_CZ' => 'Czech (Czech Republic)',
              'da_DK' => 'Danish (Denmark)',
              'dv_MV' => 'Divehi (Maldives)',
              'nl_AW' => 'Dutch (Aruba)',
              'nl_BE' => 'Dutch (Belgium)',
              'nl_NL' => 'Dutch (Netherlands)',
              'dz_BT' => 'Dzongkha (Bhutan)',
              'en_AG' => 'English (Antigua and Barbuda)',
              'en_AU' => 'English (Australia)',
              'en_BW' => 'English (Botswana)',
              'en_CA' => 'English (Canada)',
              'en_DK' => 'English (Denmark)',
              'en_HK' => 'English (Hong Kong SAR China)',
              'en_IN' => 'English (India)',
              'en_IE' => 'English (Ireland)',
              'en_NZ' => 'English (New Zealand)',
              'en_NG' => 'English (Nigeria)',
              'en_PH' => 'English (Philippines)',
              'en_SG' => 'English (Singapore)',
              'en_ZA' => 'English (South Africa)',
              'en_GB' => 'English (United Kingdom)',
              'en_US' => 'English (United States)',
              'en_ZM' => 'English (Zambia)',
              'en_ZW' => 'English (Zimbabwe)',
              'eo' => 'Esperanto',
              'et_EE' => 'Estonian (Estonia)',
              'fo_FO' => 'Faroese (Faroe Islands)',
              'fil_PH' => 'Filipino (Philippines)',
              'fi_FI' => 'Finnish (Finland)',
              'fr_BE' => 'French (Belgium)',
              'fr_CA' => 'French (Canada)',
              'fr_FR' => 'French (France)',
              'fr_LU' => 'French (Luxembourg)',
              'fr_CH' => 'French (Switzerland)',
              'fur_IT' => 'Friulian (Italy)',
              'ff_SN' => 'Fulah (Senegal)',
              'gl_ES' => 'Galician (Spain)',
              'lg_UG' => 'Ganda (Uganda)',
              'gez_ER' => 'Geez (Eritrea)',
              'gez_ET' => 'Geez (Ethiopia)',
              'ka_GE' => 'Georgian (Georgia)',
              'de_AT' => 'German (Austria)',
              'de_BE' => 'German (Belgium)',
              'de_DE' => 'German (Germany)',
              'de_LI' => 'German (Liechtenstein)',
              'de_LU' => 'German (Luxembourg)',
              'de_CH' => 'German (Switzerland)',
              'el_CY' => 'Greek (Cyprus)',
              'el_GR' => 'Greek (Greece)',
              'gu_IN' => 'Gujarati (India)',
              'ht_HT' => 'Haitian (Haiti)',
              'ha_NG' => 'Hausa (Nigeria)',
              'iw_IL' => 'Hebrew (Israel)',
              'he_IL' => 'Hebrew (Israel)',
              'hi_IN' => 'Hindi (India)',
              'hu_HU' => 'Hungarian (Hungary)',
              'is_IS' => 'Icelandic (Iceland)',
              'ig_NG' => 'Igbo (Nigeria)',
              'id_ID' => 'Indonesian (Indonesia)',
              'ia' => 'Interlingua',
              'iu_CA' => 'Inuktitut (Canada)',
              'ik_CA' => 'Inupiaq (Canada)',
              'ga_IE' => 'Irish (Ireland)',
              'it_IT' => 'Italian (Italy)',
              'it_CH' => 'Italian (Switzerland)',
              'ja_JP' => 'Japanese (Japan)',
              'kl_GL' => 'Kalaallisut (Greenland)',
              'kn_IN' => 'Kannada (India)',
              'ks_IN' => 'Kashmiri (India)',
              'csb_PL' => 'Kashubian (Poland)',
              'kk_KZ' => 'Kazakh (Kazakhstan)',
              'km_KH' => 'Khmer (Cambodia)',
              'rw_RW' => 'Kinyarwanda (Rwanda)',
              'ky_KG' => 'Kirghiz (Kyrgyzstan)',
              'kok_IN' => 'Konkani (India)',
              'ko_KR' => 'Korean (South Korea)',
              'ku_TR' => 'Kurdish (Turkey)',
              'lo_LA' => 'Lao (Laos)',
              'lv_LV' => 'Latvian (Latvia)',
              'li_BE' => 'Limburgish (Belgium)',
              'li_NL' => 'Limburgish (Netherlands)',
              'lt_LT' => 'Lithuanian (Lithuania)',
              'nds_DE' => 'Low German (Germany)',
              'nds_NL' => 'Low German (Netherlands)',
              'mk_MK' => 'Macedonian (Macedonia)',
              'mai_IN' => 'Maithili (India)',
              'mg_MG' => 'Malagasy (Madagascar)',
              'ms_MY' => 'Malay (Malaysia)',
              'ml_IN' => 'Malayalam (India)',
              'mt_MT' => 'Maltese (Malta)',
              'gv_GB' => 'Manx (United Kingdom)',
              'mi_NZ' => 'Maori (New Zealand)',
              'mr_IN' => 'Marathi (India)',
              'mn_MN' => 'Mongolian (Mongolia)',
              'ne_NP' => 'Nepali (Nepal)',
              'se_NO' => 'Northern Sami (Norway)',
              'nso_ZA' => 'Northern Sotho (South Africa)',
              'nb_NO' => 'Norwegian BokmÃ¥l (Norway)',
              'nn_NO' => 'Norwegian Nynorsk (Norway)',
              'oc_FR' => 'Occitan (France)',
              'or_IN' => 'Oriya (India)',
              'om_ET' => 'Oromo (Ethiopia)',
              'om_KE' => 'Oromo (Kenya)',
              'os_RU' => 'Ossetic (Russia)',
              'pap_AN' => 'Papiamento (Netherlands Antilles)',
              'ps_AF' => 'Pashto (Afghanistan)',
              'fa_IR' => 'Persian (Iran)',
              'pl_PL' => 'Polish (Poland)',
              'pt_BR' => 'Portuguese (Brazil)',
              'pt_PT' => 'Portuguese (Portugal)',
              'pa_IN' => 'Punjabi (India)',
              'pa_PK' => 'Punjabi (Pakistan)',
              'ro_RO' => 'Romanian (Romania)',
              'ru_RU' => 'Russian (Russia)',
              'ru_UA' => 'Russian (Ukraine)',
              'sa_IN' => 'Sanskrit (India)',
              'sc_IT' => 'Sardinian (Italy)',
              'gd_GB' => 'Scottish Gaelic (United Kingdom)',
              'sr_ME' => 'Serbian (Montenegro)',
              'sr_RS' => 'Serbian (Cyrillic )',
              'sr_LAT' => 'Serbian (Latin)',
              'sid_ET' => 'Sidamo (Ethiopia)',
              'sd_IN' => 'Sindhi (India)',
              'si_LK' => 'Sinhala (Sri Lanka)',
              'sk_SK' => 'Slovak (Slovakia)',
              'sl_SI' => 'Slovenian (Slovenia)',
              'so_DJ' => 'Somali (Djibouti)',
              'so_ET' => 'Somali (Ethiopia)',
              'so_KE' => 'Somali (Kenya)',
              'so_SO' => 'Somali (Somalia)',
              'nr_ZA' => 'South Ndebele (South Africa)',
              'st_ZA' => 'Southern Sotho (South Africa)',
              'es_AR' => 'Spanish (Argentina)',
              'es_BO' => 'Spanish (Bolivia)',
              'es_CL' => 'Spanish (Chile)',
              'es_CO' => 'Spanish (Colombia)',
              'es_CR' => 'Spanish (Costa Rica)',
              'es_DO' => 'Spanish (Dominican Republic)',
              'es_EC' => 'Spanish (Ecuador)',
              'es_SV' => 'Spanish (El Salvador)',
              'es_GT' => 'Spanish (Guatemala)',
              'es_HN' => 'Spanish (Honduras)',
              'es_MX' => 'Spanish (Mexico)',
              'es_NI' => 'Spanish (Nicaragua)',
              'es_PA' => 'Spanish (Panama)',
              'es_PY' => 'Spanish (Paraguay)',
              'es_PE' => 'Spanish (Peru)',
              'es_ES' => 'Spanish (Spain)',
              'es_US' => 'Spanish (United States)',
              'es_UY' => 'Spanish (Uruguay)',
              'es_VE' => 'Spanish (Venezuela)',
              'sw_KE' => 'Swahili (Kenya)',
              'sw_TZ' => 'Swahili (Tanzania)',
              'ss_ZA' => 'Swati (South Africa)',
              'sv_FI' => 'Swedish (Finland)',
              'sv_SE' => 'Swedish (Sweden)',
              'tl_PH' => 'Tagalog (Philippines)',
              'tg_TJ' => 'Tajik (Tajikistan)',
              'ta_IN' => 'Tamil (India)',
              'tt_RU' => 'Tatar (Russia)',
              'te_IN' => 'Telugu (India)',
              'th_TH' => 'Thai (Thailand)',
              'bo_CN' => 'Tibetan (China)',
              'bo_IN' => 'Tibetan (India)',
              'tig_ER' => 'Tigre (Eritrea)',
              'ti_ER' => 'Tigrinya (Eritrea)',
              'ti_ET' => 'Tigrinya (Ethiopia)',
              'ts_ZA' => 'Tsonga (South Africa)',
              'tn_ZA' => 'Tswana (South Africa)',
              'tr_CY' => 'Turkish (Cyprus)',
              'tr_TR' => 'Turkish (Turkey)',
              'tk_TM' => 'Turkmen (Turkmenistan)',
              'ug_CN' => 'Uighur (China)',
              'uk_UA' => 'Ukrainian (Ukraine)',
              'hsb_DE' => 'Upper Sorbian (Germany)',
              'ur_PK' => 'Urdu (Pakistan)',
              'uz_UZ' => 'Uzbek (Uzbekistan)',
              've_ZA' => 'Venda (South Africa)',
              'vi_VN' => 'Vietnamese (Vietnam)',
              'wa_BE' => 'Walloon (Belgium)',
              'cy_GB' => 'Welsh (United Kingdom)',
              'fy_DE' => 'Western Frisian (Germany)',
              'fy_NL' => 'Western Frisian (Netherlands)',
              'wo_SN' => 'Wolof (Senegal)',
              'xh_ZA' => 'Xhosa (South Africa)',
              'yi_US' => 'Yiddish (United States)',
              'yo_NG' => 'Yoruba (Nigeria)',
              'zu_ZA' => 'Zulu (South Africa)');

          $html = '';
          foreach ($data as $key => $val) {
              if ($key == $selected) {
                  $html .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $html .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $html;
      }
  }