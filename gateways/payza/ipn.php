<?php

  /**
   * Payza IPN
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: ipn.php, v3.00 2015-03-08 10:12:05 gewa Exp $
   */
  define("_WOJO", true);
  require_once ("../../init.php");


  ini_set('log_errors', true);
  ini_set('error_log', dirname(__file__) . '/ipn_errors.log');

  /* only for debuggin purpose. Create logfile.txt and chmot to 0777
  ob_start();
  echo '<pre>';
  print_r($_POST);
  echo '</pre>';
  $logInfo = ob_get_contents();
  ob_end_clean();
  
  $file = fopen('logfile.txt', 'a');
  fwrite($file, $logInfo);
  fclose($file);
  */

  $ap_code = $db->first(Content::gwTable, null, array("name" => "payza"));

  /* Check for Valid Ipn Code */
  if ($ap_code->extra3 != $_POST['ap_securitycode'])
      die();

  if (preg_match('/Success/', $_POST['ap_status'])) {

      $payer_email = $_POST['ap_custemailaddress'];
      $receiver_email = $_POST['ap_merchant'];
      $ap_currency = $_POST['ap_currency'];
      $mc_gross = $_POST['ap_totalamount'];
      $txn_id = $_POST['ap_referencenumber'];
      $id = intval($_POST['ap_itemcode']);
	  list($user_id, $sesid) = explode("_", $_POST['apc_1']);

      $row = $db->first(Content::msTable, null, array("id" => $id));
      $usr = $db->first(Users::mTable, null, array("id" => intval($user_id)));
      $total = Core::getCart($usr->id);

      if ($row and Validator::compareFloatNumbers($mc_gross, $total->totalprice, "=")) {
          $data = array(
              'txn_id' => $txn_id,
              'membership_id' => $row->id,
              'user_id' => $usr->id,
              'rate_amount' => $total->originalprice,
              'tax' => $total->totaltax,
              'coupon' => $total->coupon,
              'total' => $total->totalprice,
              'currency' => strtoupper($ap_currency),
              'pp' => "Payza",
              'ip' => Validator::sanitize($_SERVER['REMOTE_ADDR'], "string"),
              'created' => Db::toDate(),
              'status' => 1);
          $last_id = $db->insert(Content::txTable, $data)->getLastInsertId();

          $xdata = array(
              'txn_id' => $last_id,
              'invid' => date('Ymd') . $last_id,
              'user_id' => $usr->id,
              'item' => $row->title,
              'tax' => $total->tax,
              'totaltax' => $total->totaltax,
              'coupon' => $total->coupon,
              'total' => $total->total,
              'originalprice' => $total->originalprice,
              'totalprice' => $total->totalprice,
              'currency' => strtoupper($ap_currency),
              'created' => Db::toDate(),
              );
          $db->insert(Content::inTable, $xdata);

          $udata = array('membership_id' => $row->id, 'membership_expire' => $user->calculateDays($row->id));
          $db->update(Users::mTable, $udata, array("id" => $usr->id));


          /* == Notify Administrator == */
          ob_start();
          require_once (BASEPATH . 'mailer/' . App::get('Core')->lang . '/Payment_Completed_Admin.tpl.php');
          $html_message = ob_get_contents();
          ob_end_clean();

          $body = str_replace(array(
              '[LOGO]',
              '[USER]',
              '[PRICE]',
              '[PACKAGE]',
              '[DATE]',
              '[COMPANY]',
              '[SITEURL]'), array(
              Utility::getLogo(),
              $usr->fname . ' ' . $usr->lname,
              Utility::formatMoney($amount_charged, true),
              $row->title,
              date('Y'),
              App::get("Core")->company,
              SITEURL), $html_message);

          $mailer = Mailer::sendMail();
          $message = Swift_Message::newInstance()
					->setSubject(Lang::$word->STR_SPCOK)
					->setTo(array($core->site_email => $core->company))
					->setFrom(array($core->site_email => $core->company))
					->setBody($body, 'text/html');

          $mailer->send($message);

          /* == Notify User == */
          ob_start();
          require_once (BASEPATH . 'mailer/' . App::get('Core')->lang . '/Payment_Completed_User.tpl.php');
          $uhtml_message = ob_get_contents();
          ob_end_clean();

          $ubody = str_replace(array(
              '[LOGO]',
              '[NAME]',
              '[PRICE]',
              '[PACKAGE]',
              '[DATE]',
              '[COMPANY]',
              '[SITEURL]'), array(
              Utility::getLogo(),
              $usr->fname . ' ' . $usr->lname,
              Utility::formatMoney($amount_charged, true),
              $row->title,
              date('Y'),
              App::get("Core")->company,
              SITEURL), $uhtml_message);

          $umailer = Mailer::sendMail();
          $umessage = Swift_Message::newInstance()
					->setSubject(Lang::$word->STR_SPCOK)
					->setTo(array($usr->email => $usr->fname . ' ' . $usr->lname))
					->setFrom(array($core->site_email => $core->company))
					->setBody($ubody, 'text/html');
          $umailer->send($umessage);

          $db->delete(Items::xTable, array("uid" => $usr->id));

      } else {
          /* == Failed or Pending Transaction == */
          ob_start();
          require_once (BASEPATH . 'mailer/' . App::get('Core')->lang . '/Payment_Failed.tpl.php');
          $html_message = ob_get_contents();
          ob_end_clean();

          $body = str_replace(array(
              '[LOGO]',
              '[STATUS]',
              '[AMOUNT]',
              '[PP]',
              '[IP]',
              '[DATE]',
              '[COMPANY]',
              '[SITEURL]'), array(
              Utility::getLogo(),
              "Failed",
              "Skrill",
              Utility::formatMoney($_POST['amount'], true),
              $_SERVER['REMOTE_ADDR'],
              date('Y'),
              App::get("Core")->company,
              SITEURL), $html_message);

          $mailer = Mailer::sendMail();
          $message = Swift_Message::newInstance()
					->setSubject(Lang::$word->STR_SPCER)
					->setTo(array($core->site_email => $core->company))
					->setFrom(array($core->site_email => $core->company))
					->setBody($body, 'text/html');

          $mailer->send($message);
      }
  }
?>