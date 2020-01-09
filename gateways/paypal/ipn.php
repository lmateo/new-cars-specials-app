<?php
  /**
   * PayPal IPN
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: ipn.php,<?php echo  2010-08-10 21:12:05 gewa Exp $
   */
  define("_WOJO", true);

  ini_set('log_errors', true);
  ini_set('error_log', dirname(__file__) . '/ipn_errors.log');

  if (isset($_POST['payment_status'])) {
      require_once ("../../init.php");
      require_once ("class_pp.php");

	  $pp = $db->first(Content::gwTable, null, array("name" => "paypal"));

      $listener = new IpnListener();
      $listener->use_live = $pp->live;
      $listener->use_ssl = false;
      $listener->use_curl = true;

      try {
          $listener->requirePostMethod();
          $ppver = $listener->processIpn();
      }
      catch (exception $e) {
		  error_log('Process IPN failed: ' . $e->getMessage() . " [".$_SERVER['REMOTE_ADDR']."] \n" . $listener->getResponse(), 3, "pp_errorlog.log");
          exit(0);
      }

      $payment_status = $_POST['payment_status'];
      $receiver_email = $_POST['receiver_email'];
	  $mc_currency = $_POST['mc_currency'];
      list($membership_id, $user_id) = explode("_", $_POST['item_number']);
      $mc_gross = $_POST['mc_gross'];
      $txn_id = $_POST['txn_id'];

	  $row = $db->first(Content::msTable, null, array("id" => intval($membership_id)));
	  $usr = $db->first(Users::mTable, null, array("id" => intval($user_id)));
	  $total = Core::getCart($usr->id);

      if ($ppver) {
          if ($_POST['payment_status'] == 'Completed') {
			  if ($row and Validator::compareFloatNumbers($mc_gross, $total->totalprice, "=") and $receiver_email == $pp->extra) {
				  $data = array(
					  'txn_id' => $txn_id,
					  'membership_id' => $row->id,
					  'user_id' => $usr->id,
					  'rate_amount' => $total->originalprice,
					  'tax' => $total->totaltax,
					  'coupon' => $total->coupon,
					  'total' => $total->totalprice,
					  'currency' => strtoupper($mc_currency),
					  'pp' => "PayPal",
					  'ip' => Validator::sanitize($_SERVER['REMOTE_ADDR'], "string"),
					  'created' => Db::toDate(),
					  'status' => 1
					  );
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
					  'currency' => strtoupper($mc_currency),
					  'created' => Db::toDate(),
					  );
				  $db->insert(Content::inTable, $xdata);
				  
                  $udata = array(
                      'membership_id' => $row->id,
                      'membership_expire' => $user->calculateDays($row->id)
					  );

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
					  Utility::formatMoney($mc_gross, true),
					  $row->title,
					  date('Y'),
					  App::get("Core")->company,
					  SITEURL), $html_message
					  );
					  
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
					  Utility::formatMoney($mc_gross, true),
					  $row->title,
					  date('Y'),
					  App::get("Core")->company,
					  SITEURL), $uhtml_message
					  );
					  
				  $umailer = Mailer::sendMail();
				  $umessage = Swift_Message::newInstance()
							->setSubject(Lang::$word->STR_SPCOK)
							->setTo(array($usr->email => $usr->fname . ' ' . $usr->lname))
							->setFrom(array($core->site_email => $core->company))
							->setBody($ubody, 'text/html');
				  $umailer->send($umessage);
						  
				  $db->delete(Items::xTable, array("uid" => $usr->id));
				  
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
              /* == Failed Transaction= = */
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
				  SITEURL), $html_message
				  );
				  
			  $mailer = Mailer::sendMail();
			  $message = Swift_Message::newInstance()
						->setSubject(Lang::$word->STR_SPCER)
						->setTo(array($core->site_email => $core->company))
						->setFrom(array($core->site_email => $core->company))
						->setBody($body, 'text/html');
		
			  $mailer->send($message);

          }
      }
  }
?>