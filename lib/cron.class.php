<?php
  /**
   * Cron Class
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: cron.class.php, v1.00 2015-12-20 18:20:24 gewa Exp $
   */

  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  class Cron
  {


      /**
       * Cron::__construct()
       * 
       * @return
       */
      public function __construct()
      {
      }


      /**
       * Cron::expireMemberships()
       * 
       * @param integer $days
       * @return
       */
      public static function expireMemberships($days = 1)
      {

          $sql = "
		  SELECT 
			u.id, CONCAT(u.fname,' ',u.lname) as fullname,
			u.email, u.membership_expire, m.id AS mid, m.title 
		  FROM
			`" . Users::mTable . "` AS u 
			LEFT JOIN `" . Content::msTable . "` AS m 
			  ON m.id = u.membership_id
		  WHERE u.active = ?
		  AND u.membership_id <> 0
		  AND u.membership_expire <= DATE_ADD(DATE(NOW()), INTERVAL $days DAY);";

          $row = App::get('Db')->pdoQuery($sql, array("y"))->results();

          return ($row) ? $row : 0;

      }


      /**
       * Cron::expireSold()
       * 
       * @return
       */
      public static function expireSold()
      {

          $sql = "
		  SELECT 
			id
		  FROM
			`" . Items::lTable . "`
		  WHERE sold = ?	
		  AND soldexpire BETWEEN DATE_SUB(NOW(), INTERVAL " . App::get('Core')->number_sold . " DAY) AND NOW()
		  AND status = ?;";

          $row = App::get('Db')->pdoQuery($sql, array(1, 1))->results();

          return ($row) ? $row : 0;

      }


      /**
       * Cron::expireListings()
       * 
       * @return
       */
      public static function expireListings()
      {
          $solddata = self::expireSold();
          if ($solddata) {
              //Process Sold Items
              $query = "UPDATE `" . Items::lTable . "` SET soldexpire = DEFAULT(soldexpire), status = CASE ";
              $idlist = '';
              foreach ($solddata as $sld) {
                  $query .= " WHEN id = " . $sld->id . " THEN status = 0";
                  $idlist .= $sld->id . ',';
              }
              $idlist = substr($idlist, 0, -1);
              $query .= "
					END
					WHERE id IN (" . $idlist . ") AND status = 1";
              App::get('Db')->pdoQuery($query);
              unset($query, $idlist, $sld);
          }

          $result = self::expireMemberships();
          if ($result) {
              //Process Listings
              $query = "UPDATE `" . Items::lTable . "` SET soldexpire = DEFAULT(soldexpire), expire = DEFAULT(expire), status = CASE ";
              $idlist = '';
              foreach ($result as $usr) {
                  $query .= " WHEN user_id = " . $usr->id . " THEN status = 0";
                  $idlist .= $usr->id . ',';
              }
              $idlist = substr($idlist, 0, -1);
              $query .= "
					END
					WHERE user_id IN (" . $idlist . ") AND status = 1";
              App::get('Db')->pdoQuery($query);

              unset($query, $idlist, $usr);

              //Process Users
              $query = "UPDATE `" . Users::mTable . "` SET membership_expire = DEFAULT(membership_expire), membership_id = CASE ";
              $idlist = '';
              foreach ($result as $usr) {
                  $query .= " WHEN id = " . $usr->id . " THEN membership_id = 0";
                  $idlist .= $usr->id . ',';
              }
              $idlist = substr($idlist, 0, -1);
              $query .= "
					END
					WHERE id IN (" . $idlist . ")";
              App::get('Db')->pdoQuery($query);

              unset($query, $idlist, $usr);

              //Process Listings Info
              $query = "UPDATE `" . Items::liTable . "` SET lstatus = CASE ";
              $idlist = '';
              foreach ($result as $usr) {
                  $val = 0;
                  $query .= " WHEN user_id = " . $usr->id . " THEN lstatus = 0";
                  $idlist .= $usr->id . ',';
              }
              $idlist = substr($idlist, 0, -1);
              $query .= "
					END
					WHERE user_id IN (" . $idlist . ") AND lstatus = 1";
              App::get('Db')->pdoQuery($query);

              unset($query, $idlist, $usr);

          }

          //Reset Counters
          if ($result or $solddata) {
              Items::doCalc();
          }

          //Send Emails
          if ($result) {
			  $numSent = 0;
              $mailer = Mailer::sendMail();
              $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100, 30));

              ob_start();
              require_once (BASEPATH . 'mailer/' . App::get('Core')->lang . '/Membership_Expire_Message.tpl.php');
              $html_message = ob_get_contents();
              ob_end_clean();

              $replacements = array();
              foreach ($result as $cols) {
                  $replacements[$cols->email] = array(
                      '[COMPANY]' => App::get("Core")->company,
                      '[LOGO]' => Utility::getLogo(),
                      '[NAME]' => $cols->fullname,
                      '[MNAME]' => $cols->title,
                      '[SITEURL]' => SITEURL,
                      '[DATE]' => date('Y'));
              }

              $decorator = new Swift_Plugins_DecoratorPlugin($replacements);
              $mailer->registerPlugin($decorator);

              $message = Swift_Message::newInstance()
						->setSubject(Lang::$word->EMN_NOTEFROM . ' ' . App::get("Core")->company)
						->setFrom(array(App::get("Core")
						->site_email => App::get("Core")->company))
						->setBody($html_message,'text/html');

              foreach ($result as $row) {
                  $message->setTo(array($row->email => $row->fullname));
                  $numSent++;
                  $mailer->send($message, $failedRecipients);
              }
              unset($row);
          }

      }
  }