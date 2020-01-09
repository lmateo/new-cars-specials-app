<?php
  /**
   * Calendar Class
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: cache.class.php, v1.00 2014-04-20 18:20:24 gewa Exp $
   */

  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');


  class Calendar
  {


      const SHOW_DAYS = true;

      private $now;
      private $event = array();
      private static $instance;


      /**
       * Calendar::__construct()
       * 
       * @param mixed $date_string
       * @return
       */
      private function __construct($date_string)
      {
          $this->setDate($date_string);
      }


      /**
       * Calendar::instance()
       * 
       * @param mixed $date_string
       * @return
       */
      public static function instance($date_string = null)
      {
          if (!self::$instance) {
              self::$instance = new Calendar($date_string);
          }

          return self::$instance;
      }


      /**
       * Calendar::setDate()
       * 
       * @param mixed $date_string
       * @return
       */
      public function setDate($date_string = null)
      {
          if ($date_string) {
              $this->now = getdate(strtotime($date_string));
          } else {
              $this->now = getdate();
          }
      }


      /**
       * Calendar::addEvent()
       * 
       * @param mixed $html
       * @param mixed $start
       * @param mixed $end
       * @return
       */
      public function addEvent($html, $start, $end = null)
      {
          static $htmlCount = 0;
		  
          $start_date = strtotime($start);
          if ($end) {
              $end_date = strtotime($end);
          } else {
              $end_date = $start_date;
          }

          $working_date = $start_date;
          do {
              $tDate = getdate($working_date);
              $working_date += 86400;
              $this->event[$tDate['year']][$tDate['mon']][$tDate['mday']][$htmlCount] = $html;
          } while ($working_date < $end_date + 1);

          $htmlCount++;
      }

      /**
       * Calendar::clearEvent()
       * 
       * @return
       */
      public function clearEvent()
      {
          $this->event = array();
      }


      /**
       * Calendar::show()
       * 
       * @param bool $echo
       * @return
       */
      public function show($echo = true, $show_month = false)
      {
          if (self::SHOW_DAYS) {
              $today = (86400 * (date("N")));
              $wdays = array();
              for ($i = 0; $i < 7; $i++) {
                  $wdays[] = strftime('%a', time() - $today + ($i * 86400));
              }
          } else {
              $wdays = self::SHOW_DAYS;
          }

          self::arrayRotate($wdays, App::get("Core")->weekstart);
          $wday = date('N', mktime(0, 0, 1, $this->now['mon'], 1, $this->now['year'])) - App::get("Core")->weekstart;
          $no_days = cal_days_in_month(CAL_GREGORIAN, $this->now['mon'], $this->now['year']);
          $html = '<div class="calnav clearfix">';
		  if($show_month) {
              $html .= '<h3><span class="month">' . Utility::dodate("MMMM", $this->now['month']) . '</span><span class="year">' . $this->now['year'] . '</span></h3>';
	      }
          $html .= '<div class="calheader clearfix">';
          for ($i = 0; $i < 7; $i++) {
              $html .= '<div>' . Utility::dodate("EE", $wdays[$i]) . '</div>';
          }
          $html .= "</div>";
          $html .= "</div>";
          $html .= '<section class="section clearfix">';

          $wday = ($wday + 7) % 7;

          if ($wday == 7) {
              $wday = 0;
          } else {
              $html .= str_repeat('<div class="empty">&nbsp;</div>', $wday);
          }

          $count = $wday + 1;
          for ($i = 1; $i <= $no_days; $i++) {
              $html .= '<div' . ($i == $this->now['mday'] && $this->now['mon'] == date('n') && $this->now['year'] == date('Y') ? ' class="today"' : '') . '>';

              $datetime = mktime(0, 0, 1, $this->now['mon'], $i, $this->now['year']);

              $html .= '<time datetime="' . date('Y-m-d', $datetime) . '">' . $i . '</time>';

              $dHtml_arr = false;
              if (isset($this->event[$this->now['year']][$this->now['mon']][$i])) {
                  $dHtml_arr = $this->event[$this->now['year']][$this->now['mon']][$i];
              }

              if (is_array($dHtml_arr)) {
                  foreach ($dHtml_arr as $dHtml) {
                      $html .= '<div class="event">' . $dHtml . '</div>';
                  }
              }

              $html .= "</div>";

              if ($count > 6) {
                  $html .= "</section>\n" . ($i != $count ? '<section class="section clearfix">' : '');
				  $html .= "</section><section class=\"section clearfix\">\n";
                  $count = 0;
              }
              $count++;
          }
          $html .= ($count != 1 ? str_repeat('<div class="empty">&nbsp;</div>', 8 - $count) : '') . "</section>\n";
          if ($echo) {
              echo $html;
          }

          return $html;
      }

      /**
       * Calendar::arrayRotate()
       * 
       * @param mixed $data
       * @param mixed $steps
       * @return
       */
      private static function arrayRotate(&$data, $steps)
      {
          $count = count($data);
          if ($steps < 0) {
              $steps = $count + $steps;
          }
          $steps = $steps % $count;
          for ($i = 0; $i < $steps; $i++) {
              array_push($data, array_shift($data));
          }
      }
  }