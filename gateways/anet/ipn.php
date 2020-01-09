<?php
  /**
   * Auth.net IPN
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

  function ccValidate($ccn, $type)
  {
      switch ($type) {
          case "A":
              //American Express
              $pattern = "/^([34|37]{2})([0-9]{13})$/";
              return (preg_match($pattern, $ccn)) ? true : false;
              break;

          case "DI":
              //Diner's Club
              $pattern = "/^([30|36|38]{2})([0-9]{12})$/";
              return (preg_match($pattern, $ccn)) ? true : false;
              break;

          case "D":
              //Discover Card
              $pattern = "/^([6011]{4})([0-9]{12})$/";
              return (preg_match($pattern, $ccn)) ? true : false;
              break;

          case "M":
              //Mastercard
              $pattern = "/^([51|52|53|54|55]{2})([0-9]{14})$/";
              return (preg_match($pattern, $ccn)) ? true : false;
              break;

          case "V":
              //Visa
              $pattern = "/^([4]{1})([0-9]{12,15})$/";
              return (preg_match($pattern, $ccn)) ? true : false;
              break;
      }
  }

  function ccnCheck($ccn)
  {
      $ccn = preg_replace('/\D/', '', $ccn);
      $num_lenght = strlen($ccn);
      $parity = $num_lenght % 2;

      $total = 0;
      for ($i = 0; $i < $num_lenght; $i++) {
          $digit = $ccn[$i];
          if ($i % 2 == $parity) {
              $digit *= 2;
              if ($digit > 9) {
                  $digit -= 9;
              }
          }
          $total += $digit;
      }
      return ($total % 10 == 0) ? true : false;
  }


  require 'autoload.php';

  $row2 = $db->first(Content::gwTable, null, array("name" => "anet"));
  $total = Core::getCart();

  define("AUTHORIZENET_API_LOGIN_ID", $row2->extra);
  define("AUTHORIZENET_TRANSACTION_KEY", $row2->extra3);
  define("AUTHORIZENET_SANDBOX", $row2->live);
?>
<?php

  if (isset($_POST['doAnet'])) {

      Validator::checkPost('fname', Lang::$word->FNAME);
      Validator::checkPost('lname', Lang::$word->LNAME);
      Validator::checkPost('email', Lang::$word->EMAIL);
      Validator::checkPost('address', Lang::$word->ADDRESS);
      Validator::checkPost('city', Lang::$word->CITY);
      Validator::checkPost('country', Lang::$word->COUNTRY);
      Validator::checkPost('state', Lang::$word->STATE);
      Validator::checkPost('zip', Lang::$word->ZIP);

      if (!isset($_POST['cctype']))
          Message::$msgs['cctype'] = 'Please select your Credit Card Type';

      if (empty($_POST['ccn']))
          Message::$msgs['ccn'] = 'Please enter your Credit Card number';

      if (!empty($_POST['ccn']) and isset($_POST['cctype'])) {
          if (!ccValidate($_POST['ccn'], $_POST['cctype']))
              Message::$msgs['ccn'] = 'Credit Card number does not match the card type';

          if (!ccnCheck($_POST['ccn']))
              Message::$msgs['ccn'] = 'Invalid credit card number.';
      }

      if (empty($_POST['ccname']))
          Message::$msgs['ccname'] = 'Please enter name on your Credit Card';

      if (empty($_POST['cvv']))
          Message::$msgs['cvv'] = 'Please enter your 3/4 digit CVV code';

      if (empty(Message::$msgs) and $row = $db->first(Content::msTable, null, array("id" => $total->mid))) {
		  $sale = new AuthorizeNetAIM;
		  $sale->amount = $total->totalprice;
		  $sale->card_num = Validator::sanitize($_POST['ccn']);
		  $sale->exp_date = Validator::sanitize($_POST['month'] . '/' . $_POST['year']);
		  $response = $sale->authorizeAndCapture();
		  $trans_id = $response->transaction_id;
		  $staus = $response->approved;
		  $case = 1;

          switch ($staus) {
              case $case:

			  $data = array(
				  'txn_id' => $trans_id,
				  'membership_id' => $row->id,
				  'user_id' => $auth->uid,
				  'rate_amount' => $total->originalprice,
				  'tax' => $total->totaltax,
				  'coupon' => $total->coupon,
				  'total' => $total->totalprice,
				  'currency' => "USD",
				  'pp' => "Authorize.Net",
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
				  
                  Message::msgOk("Your payment was <strong>APPROVED!</strong> and you've been assigned new membership");
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
				  
                  break;

              default:
                    Message::msgError('API Error Code: ' . $response->response_reason_code . '<br>Description: ' . $response->response_reason_text);
                  break;

          }
          //echo '<pre>' . print_r($response, true) . '</pre>';

      } else
          print Message::msgStatus();

  }
?>