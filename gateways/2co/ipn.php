<?php

  /**
   * 2CO IPN
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: ipn.php, v3.00 2015-12-08 10:12:05 gewa Exp $
   */
  define("_WOJO", true);
  require_once ("../../init.php");

  if (!$auth->is_User())
      exit;

  ini_set('log_errors', true);
  ini_set('error_log', dirname(__file__) . '/ipn_errors.log');

  if (isset($_POST['token'])) {
      $pp = $db->first(Content::gwTable, null, array("name" => "2co"));
	  require_once ("lib/Twocheckout.php");

      Twocheckout::privateKey($pp->extra2); //Private Key
      Twocheckout::sellerId($pp->extra); // 2Checkout Account Number
      Twocheckout::sandbox($pp->live ? false : true); // Set to false for production accounts.
      Twocheckout::verifySSL($pp->live); // Set to false for production accounts.

      if ($row = $db->first(Content::msTable, null, array("id" => intval($_POST['item_number'])))) {
          $total = Core::getCart($auth->uid);
          try {
              $charge = Twocheckout_Charge::auth(array(
                  "merchantOrderId" => time() . $_POST['item_number'],
                  "token" => $_POST['token'],
                  "currency" => $core->currency,
                  "total" => $total->totalprice,
                  "billingAddr" => array(
                      "name" => $_POST['fname'] . ' ' . $_POST['lname'],
                      "addrLine1" => $_POST['address'],
                      "city" => $_POST['city'],
                      "state" => $_POST['state'],
                      "zipCode" => $_POST['zip'],
                      "country" => $_POST['country'],
                      "email" => $auth->email)
					  ));

              if ($charge['response']['responseCode'] == 'APPROVED') {
                  $data = array(
                      'txn_id' => $charge['response']['orderNumber'],
                      'membership_id' => $row->id,
                      'user_id' => $auth->uid,
                      'rate_amount' => $total->originalprice,
                      'tax' => $total->totaltax,
                      'coupon' => $total->coupon,
                      'total' => $total->totalprice,
                      'currency' => $charge['response']['currencyCode'],
                      'pp' => "2CO",
                      'ip' => Validator::sanitize($_SERVER['REMOTE_ADDR'], "string"),
                      'created' => Db::toDate(),
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
                      'currency' => $charge['response']['currencyCode'],
                      'created' => Db::toDate(),
                      );
                  $db->insert(Content::inTable, $xdata);

                  $udata = array('membership_id' => $row->id, 'membership_expire' => $user->calculateDays($row->id));

                  $db->update(Users::mTable, $udata, array("id" => $auth->uid));
                  App::get('Session')->set('membership_id', $row->id);

				  $json['type'] = 'success';
				  $json['message'] = Message::msgSingleOk(Lang::$word->STR_POK, false);
				  print json_encode($json);
			  
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
                      Utility::formatMoney($total->total, true),
                      $row->title,
                      date('Y'),
                      App::get("Core")->company,
                      SITEURL), $uhtml_message);

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

              }
          }
          catch (Twocheckout_Error $e) {
			  $json['type'] = 'error';
			  $json['message'] = Message::msgStatus($e->getMessage());
			  print json_encode($json);
          }
      } else {
          $json['type'] = 'error';
          $json['message'] = Message::msgStatus("Invalid ID detected");
          print json_encode($json);
          exit;
      }
  }