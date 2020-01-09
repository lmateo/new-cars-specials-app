<?php
  /**
   * PayFast Form
   *
   * @package Car Dealr Pro
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: form.tpl.php, v3.00 2015-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  $cart = Core::getCart();
?>
<?php $url = ($grows->live) ? 'www.payfast.co.za' : 'sandbox.payfast.co.za';?>
<div class="wojo segment content-center">
  <form action="https://<?php echo $url;?>/eng/process" method="post" id="pf_form" name="pf_form">
    <input type="image" src="<?php echo SITEURL.'/gateways/payfast/payfast_big.png';?>" name="submit" title="Pay With PayFast" alt="" onclick="document.pf_form.submit();"/>
    <?php
      $html = '';
      $string = '';
      
      $array = array(
          'merchant_id' => $grows->extra,
          'merchant_key' => $grows->extra2,
          'return_url' => Url::doUrl(URL_ACCOUNT),
          'cancel_url' => Url::doUrl(URL_ACCOUNT),
          'notify_url' => SITEURL . '/gateways/' . $grows->dir . '/ipn.php',
		  'name_first' => Auth::$userdata->fname,
		  'name_last' => Auth::$userdata->lname,
          'email_address' => $auth->email,
          'm_payment_id' => $row->id,
          'amount' => $cart->totalprice,
          'item_name' => $row->title,
          'item_description' => $row->description,
          'custom_int1' => $auth->uid,
          );
    
      foreach ($array as $k => $v) {
          $html .= '<input type="hidden" name="' . $k . '" value="' . $v . '" />';
          $string .= $k . '=' . urlencode($v) . '&';
      }
      $string = substr($string, 0, -1);
      $sig = md5($string);
      $html .= '<input type="hidden" name="signature" value="' . $sig . '" />';
    
      print $html;
    ?>
  </form>
</div>