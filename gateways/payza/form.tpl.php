<?php
  /**
   * Payza Form
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
<?php $url = ($grows->live) ? 'secure.payza.com/checkout' : 'sandbox.Payza.com/sandbox/payprocess.aspx';?>
<div class="wojo segment content-center">
  <form action="https://<?php echo $url;?>" method="post" class="xform" id="ap_form" name="ap_form">
    <input type="image" src="<?php echo SITEURL . '/gateways/payza/payza_big.png';?>" name="submit" style="vertical-align:middle;border:0;width:171px;margin-right:10px" title="Pay With Payza" alt="" onclick="document.ap_form.submit();"/>
    <input type="hidden" name="ap_purchasetype" value="item"/>
    <input type="hidden" name="ap_merchant" value="<?php echo $grows->extra;?>" />
    <input type="hidden" name="ap_returnurl" value="<?php echo Url::doUrl(URL_ACCOUNT);?>" />
    <input type="hidden" name="ap_currency" value="<?php echo ($grows->extra2) ? $grows->extra2 : $core->currency;?>" />
    <input type="hidden" name="apc_1" value="<?php echo $auth->uid.'_'.$auth->sesid;?>" />
    <input type="hidden" name="ap_itemname" value="<?php echo $row->title;?>" />
    <input type="hidden" name="ap_itemcode" value="<?php echo $row->id;?>" />
    <input type="hidden" name="ap_amount" value="<?php echo $cart->totalprice;?>" />
  </form>
</div>