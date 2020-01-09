<?php
  /**
   * Paypal Form
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: form.tpl.php, v1.00 2015-10-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
	  $cart = Core::getCart();
?>
<?php $url = ($grows->live) ? 'www.paypal.com' : 'www.sandbox.paypal.com';?>
<div class="wojo segment content-center">
  <form action="https://<?php echo $url;?>/cgi-bin/webscr" method="post" id="pp_form" name="pp_form">
    <input type="image" src="<?php echo SITEURL . '/gateways/paypal/paypal_big.png';?>" name="submit" title="Pay With Paypal" alt="" onclick="document.pp_form.submit();"/>
    <input type="hidden" name="cmd" value="_xclick" />
    <input type="hidden" name="amount" value="<?php echo $cart->totalprice;?>" />
    <input type="hidden" name="business" value="<?php echo $grows->extra;?>" />
    <input type="hidden" name="item_name" value="<?php echo $row->title;?>" />
    <input type="hidden" name="item_number" value="<?php echo $row->id . '_' . $auth->uid;?>" />
    <input type="hidden" name="return" value="<?php echo Url::doUrl(URL_ACCOUNT);?>" />
    <input type="hidden" name="rm" value="2" />
    <input type="hidden" name="notify_url" value="<?php echo SITEURL . '/gateways/' . $grows->dir;?>/ipn.php" />
    <input type="hidden" name="cancel_return" value="<?php echo Url::doUrl(URL_ACCOUNT);?>" />
    <input type="hidden" name="no_note" value="1" />
    <input type="hidden" name="currency_code" value="<?php echo ($grows->extra2) ? $grows->extra2 : $core->currency;?>" />
  </form>
</div>