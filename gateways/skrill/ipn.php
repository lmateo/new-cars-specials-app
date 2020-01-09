<?php
  /**
   * Skrill IPN
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: ipn.php, v3.00 2015-03-08 10:12:05 gewa Exp $
   */
  define("_WOJO", true);
  require_once("../../init.php");
  
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
  
  /* Check for mandatory fields */
  $r_fields = array(
		'status', 
		'md5sig', 
		'merchant_id', 
		'pay_to_email', 
		'mb_amount', 
		'mb_transaction_id', 
		'currency', 
		'amount', 
		'transaction_id', 
		'pay_from_email', 
		'mb_currency'
  );
  $skrill = $db->first(Content::gwTable, null, array("name" => "skrill"));
  
  foreach ($r_fields as $f)
      if (!isset($_POST[$f]))
          die();
  
  /* Check for MD5 signature */
  $md5 = strtoupper(md5($_POST['merchant_id'] . $_POST['transaction_id'] . strtoupper(md5($skrill->extra3)) . $_POST['mb_amount'] . $_POST['mb_currency'] . $_POST['status']));
  if ($md5 != $_POST['md5sig'])
      die();
  
  if (intval($_POST['status']) == 2) {
      $mb_currency = $_POST['mb_currency'];
	  $mc_gross = $_POST['amount'];
	  $txn_id = $_POST['mb_transaction_id'];

      list($membership_id, $user_id) = explode("_", $_POST['custom']);

	  $row = $db->first(Content::msTable, null, array("id" => intval($membership_id)));
	  $usr = $db->first(Users::mTable, null, array("id" => intval($user_id)));
	  $total = Core::getCart(intval($user_id));
      
      $data = array(
			'txn_id' => $txn_id, 
			'membership_id' => $row->id, 
			'user_id' => (int)$user_id, 
			'rate_amount' => $total->originalprice,
			'tax' => $total->totaltax,
			'coupon' => $total->coupon,
			'total' => $total->totalprice,
			'ip' => Validator::sanitize($_SERVER['REMOTE_ADDR'], "string"), 
			'created' => Db::toDate(), 
			'pp' => "Skrill", 
			'currency' => Validator::sanitize($mb_currency, "string"), 
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
		  'currency' => $data['currency'],
		  'created' => Db::toDate(),
		  );
	  $db->insert(Content::inTable, $xdata);
				  
	  $udata = array(
		  'membership_id' => $row->id,
		  'membership_expire' => $user->calculateDays($row->id)
		  );
      
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
?>