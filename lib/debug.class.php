<?php
  /**
   * Class Debug
   *
   * package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: debug.class.php, v1.00 2014-10-20 18:20:24 gewa Exp $
   */

  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  class Debug
  {
      private static $_startTime;
      private static $_endTime;
      private static $_arrGeneral = array();
      private static $_arrParams = array();
      private static $_arrWarnings = array();
      private static $_arrErrors = array();
      private static $_arrQueries = array();


      /**
       * Debug::init()
       * 
       * @return
       */
      public static function run()
      {
          if (!DEBUG)
              return false;

          self::$_startTime = self::_getFormattedMicrotime();
      }

      /**
       * Debug::addMessage()
       * 
       * @param string $type
       * @param string $key
       * @param string $val
       * @param string $storeType
       * @return
       */
      public static function addMessage($type = 'params', $key = '', $val = '', $storeType = '')
      {
          if (!DEBUG)
              return false;

          if ($storeType == 'session') {
			  $_SESSION['debug-' . $type][$key] = $val;
          }
		  
		  switch($type) {
			  case "general" :
			     self::$_arrGeneral[$key][] = $val;
			  break;

			  case "params" :
			     self::$_arrParams[$key] = $val;
			  break;
			  
			  case "errors" :
			     self::$_arrErrors[$key][] = $val;
			  break;
			  
			  case "warnings" :
			     self::$_arrWarnings[$key][] = $val;
			  break; 
			  
			  case "queries" :
			     self::$_arrQueries[$key][] = $val;
			  break; 
		  }

      }

      /**
       * Debug::getMessage()
       * 
       * @param string $type
       * @param string $key
       * @return
       */
      public static function getMessage($type = 'params', $key = '')
      {
          $output = '';

          if ($type == 'errors')
              $output = isset(self::$_arrErrors[$key]) ? self::$_arrErrors[$key] : '';

          return $output;
      }

      /**
       * Debug::displayInfo()
       * 
       * @return
       */
      public static function displayInfo()
      {
          if (!DEBUG)
              return false;

          self::$_endTime = self::_getFormattedMicrotime();

          $nl = "\n";
		  if(App::get('Session')->isExists('debug-warnings')) {
			$warncount = count(App::get('Session')->get('debug-warnings'));
			$warnings = count(self::$_arrWarnings);
			$twarn = ($warncount + $warnings);
		  } else {
			  $twarn = count(self::$_arrWarnings);
		  }
		  if(App::get('Session')->isExists('debug-errors')) {
			  $errcount = count(App::get('Session')->get('debug-errors'));
			  $errors = count(self::$_arrErrors);
			  $terr = ($errcount + $errors);
		  } else {
			  $terr = count(self::$_arrErrors);
		  }

		  if(App::get('Session')->isExists('debug-params')) {
			  $parcount = count(App::get('Session')->get('debug-params'));
			  $params = count(self::$_arrParams);
			  $tpar = ($parcount + $params);
		  } else {
			  $tpar = count(self::$_arrParams);
		  }
		  
          echo $nl . '
		<div id="debug-panel">
		<fieldset>
		<legend id="debug-panel-legend">
			<b style="color:#222">Debug</b>:&nbsp;
			<a id="debugArrowExpand" class="debugArrow" style="display:;" href="javascript:void(0)" title="Expand" onclick="javascript:appTabsMiddle()"><i class="icon triangle up"></i></a>
			<a id="debugArrowCollapse" class="debugArrow" style="display:none;" href="javascript:void(0)" title="Collapse" onclick="javascript:appTabsMinimize()"><i class="icon triangle down"></i></a>
			<a id="debugArrowMaximize" class="debugArrow" style="display:;" href="javascript:void(0)" title="Maximize" onclick="javascript:appTabsMaximize()"><i class="icon checkbox empty"></i></a>
			<a id="debugArrowMinimize" class="debugArrow" style="display:none;" href="javascript:void(0)" title="Minimize" onclick="javascript:appTabsMiddle()"><i class="icon checkbox checked"></i></a>
			<span>
				&nbsp;<a id="tabGeneral" href="javascript:void(\'General\')" onclick="javascript:appExpandTabs(\'auto\', \'General\')">General</a> &nbsp;|&nbsp;
				&nbsp;<a id="tabParams" href="javascript:void(\'Params\')" onclick="javascript:appExpandTabs(\'auto\', \'Params\')">Params (' . count(self::$_arrParams) . ')</a> &nbsp;|&nbsp;
				&nbsp;<a id="tabWarnings" href="javascript:void(\'Warnings\')" onclick="javascript:appExpandTabs(\'auto\', \'Warnings\')">Warnings (' . $twarn . ')</a> &nbsp;|&nbsp;
				&nbsp;<a id="tabErrors" href="javascript:void(\'Errors\')" onclick="javascript:appExpandTabs(\'auto\', \'Errors\')">Errors (' . $terr . ')</a> &nbsp;|&nbsp;
				&nbsp;<a id="tabQueries" href="javascript:void(\'Queries\')" onclick="javascript:appExpandTabs(\'auto\', \'Queries\')">SQL Queries (' . count(self::$_arrQueries) . ')</a>
				<a class="clear_session" data-content="Clear Log"><i class="icon close"></i></a>
			</span>				
		</legend>
		<div id="contentGeneral" style="display:none;padding:10px;height:200px;overflow-y:auto;">
			Total execution time: ' . round((float)self::$_endTime - (float)self::$_startTime, 6) . ' sec.<br>
			Framework ' . App::get('Core')->wojon . ' v' . App::get('Core')->wojov . '<br>';
		  if (function_exists('memory_get_usage') && ($usage = memory_get_usage()) != '') {
			  echo "Memory Usage ".File::getSize($usage).'<br>';
		  }
			
          if (count(self::$_arrGeneral) > 0) {
              echo '<pre>';
              print_r(self::$_arrGeneral);
              echo '</pre>';
          }
          echo 'POST:';
          echo '<pre style="white-space:pre-wrap;">';
          if (isset($_POST))
              print_r($_POST);
          echo '</pre>';

          echo 'GET:';
          echo '<pre style="white-space:pre-wrap;">';
          if (isset($_GET))
              print_r($_GET);
          echo '</pre>';
		  
          echo '</div>
	
		<div id="contentParams" style="display:none;padding:10px;height:200px;overflow-y:auto;">';
          if (count(self::$_arrParams) > 0) {
              echo '<pre>';
              print_r(self::$_arrParams);
              echo '</pre><br>';
          }
          if (App::get('Session')->isExists('debug-params')) {
				echo '<pre>';
				print_r(App::get('Session')->get('debug-params'));
				echo '</pre>';
          }
          echo '</div>
	
		<div id="contentWarnings" style="display:none;padding:10px;height:200px;overflow-y:auto;">';
          if (count(self::$_arrWarnings) > 0) {
				echo '<pre>';
				print_r(self::$_arrWarnings);
				echo '</pre>';
          }
          if (App::get('Session')->isExists('debug-warnings')) {
				echo '<pre>';
				print_r(App::get('Session')->get('debug-warnings'));
				echo '</pre>';
          }
          echo '</div>
	
		<div id="contentErrors" style="display:none;padding:10px;height:200px;overflow-y:auto;">';
          if (count(self::$_arrErrors) > 0) {
				echo '<pre>';
				print_r(self::$_arrErrors);
				echo '</pre>';
          }
          if (App::get('Session')->isExists('debug-errors')) {
				echo '<pre>';
				print_r(App::get('Session')->get('debug-errors'));
				echo '</pre>';
          }
          echo '</div>
	
		<div id="contentQueries" style="display:none;padding:10px;height:200px;overflow-y:auto;">';
          if (count(self::$_arrQueries) > 0) {
              foreach (self::$_arrQueries as $msgKey => $msgVal) {
                  echo $msgKey . '<br>';
                  echo $msgVal[0] . '<br><br>';
              }
          }
          if (App::get('Session')->isExists('debug-queries')) {
				echo '<pre>';
				print_r(App::get('Session')->get('debug-queries'));
				echo '</pre>';
          }
          echo '</div>
	
		</fieldset>
		</div>';

          $debugBarState = isset($_COOKIE['debugBarState']) ? $_COOKIE['debugBarState'] : 'min';
          if ($debugBarState == 'max') {
              print '<script type="text/javascript">appTabsMaximize();</script>';
          } elseif ($debugBarState == 'middle') {
              print '<script type="text/javascript">appTabsMiddle();</script>';
		  } else {
			  print '<script type="text/javascript">appTabsMinimize();</script>';
		  }
      }
	  
      /**
       * Debug::pre()
       * 
       * @param string $data
       * @return
       */
      public static function pre($data)
      {
          print '<pre>' . print_r($data, true) . '</pre>';
      }
	  
      /**
       * Debug::_getFormattedMicrotime()
       * 
       * @return
       */
      private static function _getFormattedMicrotime()
      {
          if (!DEBUG)
              return false;

          list($usec, $sec) = explode(' ', microtime());
          return ((float)$usec + (float)$sec);
      }

  }