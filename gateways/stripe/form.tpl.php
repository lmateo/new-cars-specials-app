<?php
  /**
   * Stripe Form
   *
   * @package Membership Manager Pro
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: form.tpl.php, v3.00 2015-03-20 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
	  $cart = Core::getCart();
?>
<div class="wojo segment">
  <form method="post" id="stripe_form" class="wojo inverted form">
    <div class="field">
      <label><?php echo Lang::$word->STR_CCN;?></label>
      <label class="input">
        <input type="text" autocomplete="off" name="card-number" placeholder="<?php echo Lang::$word->STR_CCN;?>">
      </label>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->STR_CCV;?></label>
        <label class="input">
          <input type="text" autocomplete="off" name="card-cvc" placeholder="<?php echo Lang::$word->STR_CCV;?>">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STR_CEXM;?></label>
        <label class="input">
          <input type="text" autocomplete="off" name="card-expiry-month" placeholder="MM">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STR_CEXY;?></label>
        <label class="input">
          <input type="text" autocomplete="off" name="card-expiry-year" placeholder="YYYY">
        </label>
      </div>
    </div>
    <div class="clearfix">
      <button class="wojo secondary button" id="dostripe" name="dostripe" type="button"><?php echo Lang::$word->SUBMITP;?></button>
    </div>
    <input type="hidden" name="amount" value="<?php echo $cart->totalprice;?>" />
    <input type="hidden" name="item_name" value="<?php echo $row->title;?>" />
    <input type="hidden" name="item_number" value="<?php echo $row->id;?>" />
    <input type="hidden" name="currency_code" value="<?php echo ($grows->extra2) ? $grows->extra2 : $core->currency;?>" />
    <input type="hidden" name="processStripePayment" value="1" />
  </form>
</div>
<div id="smsgholder"></div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
    $('#dostripe').on('click', function() {
        $("#stripe_form").addClass('loading');
        var str = $("#stripe_form").serialize();
        $.ajax({
            type: "post",
            dataType: 'json',
            url: SITEURL + "/gateways/stripe/ipn.php",
            data: str,
            success: function(json) {
                $("#stripe_form").removeClass('loading');
                if (json.type == "success") {
					$('#smsgholder').html(json.message);
                    setTimeout(function() {
							window.location.href = '<?php echo Url::doUrl(URL_ACCOUNT);?>';
                        },
                        4000);
                } else {
                    $("#smsgholder").html(json.message);
                }
            }
        });
        return false;
    });
});
// ]]>
</script>