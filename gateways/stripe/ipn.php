<?php
  /**
   * Stripe IPN
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: ipn.php, v3.00 2015-03-08 10:12:05 gewa Exp $
   */
  define("_WOJO", true);
  require_once ("../../init.php");

  if (!$auth->is_User())
      exit;  
	  
  ini_set('log_errors', true);
  ini_set('error_log', dirname(__file__) . '/ipn_errors.log');

  if (isset($_POST['processStripePayment'])) {
	  require_once (dirname(__file__) . '/lib/Stripe.php');
	  
	  $key = $db->first(Content::gwTable, null, array("name" => "stripe"));
      $stripe = array("secret_key" => $key->extra, "publishable_key" => $key->extra3);
      Stripe::setApiKey($stripe['secret_key']);
	  
      try {
          $charge = Stripe_Charge::create(array(
              "amount" => round($_POST['amount'] * 100, 0), // amount in cents, again
              "currency" => $_POST['currency_code'],
              "card" => array(
                  "number" => $_POST['card-number'],
                  "exp_month" => $_POST['card-expiry-month'],
                  "exp_year" => $_POST['card-expiry-year'],
                  "cvc" => $_POST['card-cvc'],
                  ),
              "description" => $_POST['item_name']));
			  
          $json = json_decode($charge);
          $amount_charged = Utility::formatNumber($json->amount / 100);
		  //Debug::addMessage('params', 'stripe', $json, "session");

		  /* == Payment Completed == */
		  $row = $db->first(Content::msTable, null, array("id" => intval($_POST['item_number'])));
		  $total = Core::getCart($auth->uid);
		  if ($row and Validator::compareFloatNumbers($amount_charged, $total->totalprice, "=")) {
			  $data = array(
				  'txn_id' => $json->balance_transaction,
				  'membership_id' => $row->id,
				  'user_id' => $auth->uid,
				  'rate_amount' => $total->originalprice,
				  'tax' => $total->totaltax,
				  'coupon' => $total->coupon,
				  'total' => $total->totalprice,
				  'currency' => strtoupper($json->currency),
				  'pp' => "Stripe",
				  'ip' => Validator::sanitize($_SERVER['REMOTE_ADDR'], "string"),
				  'created' => Db::toDate(),
				  'status' => 1
				  );
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
					  'currency' => strtoupper($json->currency),
					  'created' => Db::toDate(),
					  );
				  $db->insert(Content::inTable, $xdata);
				  
                  $udata = array(
                      'membership_id' => $row->id,
                      'membership_expire' => $user->calculateDays($row->id)
					  );

                  $db->update(Users::mTable, $udata, array("id" => $auth->uid));
				  App::get('Session')->set('membership_id', $row->id);
				  App::get('Session')->set('membership_expire', $user->calculateDays($row->id));


			  $jn['type'] = 'success';
			  $jn['message'] = Message::msgSingleOk(Lang::$word->STR_POK, false);
			  print json_encode($jn);
		  
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
				  $auth->name,
				  Utility::formatMoney($amount_charged, true),
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
				  $auth->name,
				  Utility::formatMoney($amount_charged, true),
				  $row->title,
				  date('Y'),
				  App::get("Core")->company,
				  SITEURL), $uhtml_message
				  );
				  
			  $umailer = Mailer::sendMail();
			  $umessage = Swift_Message::newInstance()
						->setSubject(Lang::$word->STR_SPCOK)
						->setTo(array($auth->email => $auth->name))
						->setFrom(array($core->site_email => $core->company))
						->setBody($ubody, 'text/html');
			  $umailer->send($umessage);
					  
			  $db->delete(Items::xTable, array("uid" => $auth->uid));
			  
			  /* == Add Activity == */
			  $acdata = array(
				  'user_id' => $auth->uid,
				  'type' => "membership",
				  'title' => $row->title,
				  'username' => $auth->username,
				  'fname' => $auth->fname,
				  'lname' => $auth->lname,
				  );
			  $db->insert(Users::acTable, $acdata);

		  } else {
			$json['type'] = 'error';
			$json['message'] = "Invalid Transaction detected";
			print json_encode($json);  
		  }
      }
      catch (Stripe_CardError $e) {
          //$json = json_decode($e);
          $body = $e->getJsonBody();
          $err = $body['error'];
          $json['type'] = 'error';
          Message::$msgs['status'] = 'Status is: ' . $e->getHttpStatus() . "\n";
          Message::$msgs['type'] = 'Type is: ' . $err['type'] . "\n";
          Message::$msgs['code'] = 'Code is: ' . $err['code'] . "\n";
          Message::$msgs['param'] = 'Param is: ' . $err['param'] . "\n";
          Message::$msgs['msg'] = 'Message is: ' . $err['message'] . "\n";
          $json['message'] = Message::msgStatus();
          print json_encode($json);
		  
		  
      }
      catch (Stripe_InvalidRequestError $e) {}
      catch (Stripe_AuthenticationError $e) {}
      catch (Stripe_ApiConnectionError $e) {}
      catch (Stripe_Error $e) {}
      catch (exception $e) {}
  }
?>