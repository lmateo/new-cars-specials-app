<?php

  /**
   * PayFast IPN
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: ipn.php, 2014-08-30 21:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  define("_PIPN", true);

  ini_set('log_errors', true);
  ini_set('error_log', dirname(__file__) . '/ipn_errors.log');

  include_once dirname(__file__) . '/pf.inc.php';

  if (isset($_POST['payment_status'])) {
      require_once ("../../init.php");

      $pf = $db->first(Content::gwTable, null, array("name" => "payfast"));
      $pfHost = ($pf->live) ? 'https://www.payfast.co.za' : 'https://sandbox.payfast.co.za';
      $error = false;

      pflog('ITN received from payfast.co.za');
      if (!pfValidIP($_SERVER['REMOTE_ADDR'])) {
          pflog('REMOTE_IP mismatch: ');
          $error = true;
          return false;
      }

      $data = pfGetData();

      pflog('POST received from payfast.co.za: ' . print_r($data, true));

      if ($data === false) {
          pflog('POST is empty: ' . print_r($data, true));
          $error = true;
          return false;
      }

      if (!pfValidSignature($data, $pf->extra3)) {
          pflog('Signature mismatch on POST');
          $error = true;
          return false;
      }

      pflog('Signature OK');

      $itnPostData = array();
      $itnPostDataValuePairs = array();

      foreach ($_POST as $key => $value) {
          if ($key == 'signature')
              continue;

          $value = urlencode(stripslashes($value));
          $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value);
          $itnPostDataValuePairs[] = "$key=$value";
      }

      $itnVerifyRequest = implode('&', $itnPostDataValuePairs);
      if (!pfValidData($pfHost, $itnVerifyRequest, "$pfHost/eng/query/validate")) {
          pflog("ITN mismatch for $itnVerifyRequest\n");
          pflog('ITN not OK');
          $error = true;
          return false;
      }

      pflog('ITN OK');
      pflog("ITN verified for $itnVerifyRequest\n");

      if ($error == false and $_POST['payment_status'] == "COMPLETE") {
          $user_id = intval($_POST['custom_int1']);
          $mc_gross = $_POST['amount_gross'];
          $membership_id = $_POST['m_payment_id'];
          $txn_id = $_POST['pf_payment_id'];

          $total = Core::getCart($user_id);
          $v1 = Validator::compareFloatNumbers($mc_gross, $total->totalprice, "=");

          if ($v1 == true) {
              $row = $db->first(Content::msTable, null, array("id" => intval($membership_id)));
              $usr = $db->first(Users::mTable, null, array("id" => intval($user_id)));

              $data = array(
                  'txn_id' => $txn_id,
                  'membership_id' => $row->id,
                  'user_id' => $usr->id,
                  'rate_amount' => $total->originalprice,
                  'tax' => $total->totaltax,
                  'coupon' => $total->coupon,
                  'total' => $total->totalprice,
                  'ip' => Validator::sanitize($_SERVER['REMOTE_ADDR'], "string"),
                  'created' => Db::toDate(),
                  'pp' => "PayFast",
                  'currency' => Validator::sanitize($mb_currency, "string"),
                  'status' => 1);

              $last_id = $db->insert(Content::txTable, $data)->getLastInsertId();

              $xdata = array(
                  'txn_id' => $last_id,
                  'invid' => date('Ymd') . $last_id,
                  'user_id' => $auth->uid,
                  'item' => $row->title,
                  'tax' => $total->tax,
                  'totaltax' => $total->totaltax,
                  'coupon' => $total->coupon,
                  'total' => $total->total,
                  'originalprice' => $total->originalprice,
                  'totalprice' => $total->totalprice,
                  'currency' => $data['currency'],
                  'created' => Db::toDate(),
                  );
              $db->insert(Content::inTable, $xdata);

              $udata = array('membership_id' => $row->id, 'membership_expire' => $user->calculateDays($row->id));
              $db->update(Users::mTable, $udata, array("id" => (int)$user_id));


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
                  Utility::formatMoney($mc_gross, true),
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
              pflog("Email Notification [Admin] sent successfuly");

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
                  Utility::formatMoney($mc_gross, true),
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
              pflog("Email Notification [User] sent successfuly");
			  
			  /* == Add Activity == */
			  $acdata = array(
				  'user_id' => $usr->id,
				  'type' => "membership",
				  'title' => $row->title,
				  'username' => $usr->username,
				  'fname' => $usr->fname,
				  'lname' => $usr->lname,
				  );
			  $db->insert(Users::acTable, $acdata);
          }

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