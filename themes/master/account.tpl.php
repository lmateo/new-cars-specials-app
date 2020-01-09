<?php
  /**
   * 404
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: account.tpl.php, v1.00 2015-08-05 10:16:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if (!$auth->is_User())
      Url::redirect(Url::doUrl(URL_LOGIN));
	  
  $membershipdata = $content->getMemberships(true);
  $mrow = $user->getUserPackage();
  $invdata = $user->getInvoices();
?>
<div class="wojo-grid">
  <div class="wojo secondary segment">
    <div class="wojo huge fitted inverted header">
      <div class="content"> <?php echo Lang::$word->HOME_SUB12;?>
        <p class="subheader"><?php echo Lang::$word->HOME_SUB12P;?></p>
      </div>
    </div>
    <div id="userMenu">
      <div class="wojo labeled right icon fluid dropdown button"> <i class="angle down icon"></i> <span class="text"><?php echo Lang::$word->PACKAGES;?></span>
        <div class="menu"> 
        <a class="item"><i class="icon note"></i><?php echo Lang::$word->PACKAGES;?></a> 
        <a href="<?php echo Url::doUrl(URL_MYLISTINGS);?>" class="item"><i class="icon car"></i><?php echo Lang::$word->LISTINGS;?></a> 
        <a href="<?php echo Url::doUrl(URL_ADDLISTING);?>" class="item"><i class="icon plus"></i><?php echo Lang::$word->LST_ADD;?></a> 
        <a href="<?php echo Url::doUrl(URL_MYSETTINGS);?>" class="item"><i class="icon cog"></i><?php echo Lang::$word->SETTINGS;?></a> 
        <a href="<?php echo Url::doUrl(URL_MYREVIEWS);?>" class="item"><i class="icon badge"></i><?php echo Lang::$word->SRW_ADD;?></a> </div>
      </div>
    </div>
  </div>
  <div class="wojo secondary bg">
    <div class="padding wojo tab item" id="packages">
      <?php if($membershipdata):?>
      <p class="wojo basic message"><?php echo Lang::$word->HOME_SUB13P;?></p>
      <div class="columns gutters">
        <div class="screen-50 tablet-50 phone-100">
          <div class="wojo primary segment">
            <div class="header"><?php echo Lang::$word->M_CPACKAGE;?></div>
            <div class="content"><?php echo $mrow->membership_id ? $mrow->title . ' <small>(' . $mrow->total . ' ' . Lang::$word->LISTINGS . ')</small>' : "--/--";?></div>
          </div>
        </div>
        <div class="screen-50 tablet-50 phone-100">
          <div class="wojo primary segment">
            <div class="header"><?php echo Lang::$word->MSM_VALIDTO;?></div>
            <div class="content"><?php echo $mrow->membership_id ? Utility::dodate("long_date", $mrow->membership_expire) : "--/--";?></div>
          </div>
        </div>
      </div>
      <div class="columns gutters">
        <?php foreach ($membershipdata as $i => $prow):?>
        <?php $color = array("ac7ecc","d28c85","abd78c","94d6dc","52bbb2","8458c2","c85f68","a2ce66", "ac7ecc","d28c85","abd78c","94d6dc","52bbb2","8458c2");?>
        <div class="screen-25 tablet-50 phone-100">
          <div class="wojo divided card<?php echo $prow->id == $auth->membership_id ? ' active' : null;?>">
            <h3 class="header" style="border-color:#<?php echo $color[$i];?>"><?php echo $prow->title;?> </h3>
            <div class="item">
              <div class="intro"><i class="icon calendar"></i></div>
              <div class="data"><?php echo $prow->days . ' ' . Utility::getPeriod($prow->period);?></div>
            </div>
            <div class="item">
              <div class="intro"><i class="icon bookmark"></i></div>
              <div class="data"><?php echo Lang::$word->FEATURED;?> <?php echo $prow->featured ? '<i class="icon positive check"></i>' : '<i class="icon negative ban"></i>';?></div>
            </div>
            <div class="item">
              <div class="intro"><i class="icon car"></i></div>
              <div class="data"><?php echo Lang::$word->LISTINGS;?> <span class="wojo label"><?php echo $prow->listings;?></span></div>
            </div>
            <div class="item">
              <div class="intro"><i class="icon money bag"></i></div>
              <div class="data"><?php echo Utility::formatMoney($prow->price, true);?></div>
            </div>
            <div class="item eq">
              <div class="intro"><i class="icon pencil"></i></div>
              <div class="data"><?php echo Validator::cleanOut($prow->description);?></div>
            </div>
            <div class="actions">
              <div class="item">
                <div class="intro"><i class="icon long arrow right"></i></div>
                <div class="data"> <a class="add-cart" data-id="<?php echo $prow->id;?>" data-price="<?php echo $prow->price;?>"><?php echo ($prow->price <> 0) ? Lang::$word->M_BUY : Lang::$word->M_ACTIVATE;?></a> </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach;?>
      </div>
      <?php if($invdata):?>
      <div class="wojo tertiary segment">
        <div class="header"><a onclick="$('#invoices').slideToggle();" class="push-right"><i class="icon chevron down"></i></a>Invoices</div>
        <table class="wojo divided table hide-all" id="invoices">
          <thead>
            <tr>
              <th>#</th>
              <th><?php echo Lang::$word->AMOUNT;?></th>
              <th><?php echo Lang::$word->TRX_PAYDATE;?></th>
              <th><?php echo Lang::$word->ACTIONS;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($invdata as $irow):?>
            <tr>
              <td><?php echo $irow->invid;?></td>
              <td><?php echo Utility::formatMoney($irow->totalprice, true);?></td>
              <td><?php echo Utility::doDate("long_date", $irow->created);?></td>
              <td><a href="<?php echo SITEURL;?>/ajax/user.php?doInvoice&amp;id=<?php echo $irow->txn_id;?>" data-content="<?php echo Lang::$word->VIEWDOWN;?>"><i class="rounded download icon link"></i></a></td>
            </tr>
            <?php endforeach;?>
            <?php unset($irow);?>
          </tbody>
        </table>
      </div>
      <?php endif;?>
      <div id="show-gateway"></div>
      <?php endif;?>
    </div>
  </div>
</div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
    $("body").on("click", "a.add-cart", function() {
        var id = $(this).data('id');
        price = $(this).data('price');
        $.ajax({
            type: "POST",
			dataType:'json',
            url: SITEURL + "/ajax/user.php",
            data: {
                addtocart: 1,
                id: id,
                price: price
            },
            success: function(json) {
                $("#show-gateway").html(json.message);
				$('html, body').animate({
					scrollTop: $("#show-gateway").offset().top
				}, 1000);
            }
        });
        return false;
    });
    $("body").on("click", "input[name='gateway']", function() {
        var id = $(this).prop('value');
		var mid = $(this).data('gateway');
        $.ajax({
            type: "GET",
			dataType:'json',
            url: SITEURL + "/ajax/user.php",
            data: {
                loadGateway: 1,
                id: id,
				mid: mid
            },
            success: function(json) {
				$("#gdata").html(json.message);
				$('html, body').animate({
					scrollTop: $("#gdata").offset().top
				}, 1000);
            }
        });
    });
    $("body").on("click", "#cinput", function() {
        var id = $(this).data('id');
        var $code = $("input[name=coupon]");
        if (!$code.val()) {
            $code.closest('div').addClass('error');
        } else {
            $.ajax({
                type: "get",
                dataType: 'json',
                url: SITEURL + "/ajax/user.php",
                data: {
                    doCoupon: 1,
                    id: id,
                    code: $code.val()
                },
                success: function(json) {
                    if (json.type == "success") {
                        $code.closest('div').removeClass('error');
                        $(".totaltax").html(json.tax);
                        $(".totalamt").html(json.gtotal);
                        $(".disc").html(json.disc);
                        $(".disc").parent().addClass('active');
                    } else {
                        $code.closest('div').addClass('error');
                    }
                }

            });
        }
        return false;
    });
});
// ]]>
</script>