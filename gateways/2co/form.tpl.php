<?php
  /**
   * 2CO Form
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: form.tpl.php v1.00 2015-10-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo segment">
  <form method="post" action="<?php echo SITEURL . '/gateways/2co/ipn.php';?>" id="myCCForm" name="myCCForm">
    <div class="wojo inverted form">
      <div class="field">
        <label><?php echo Lang::$word->STR_CCN;?></label>
        <label class="input">
          <input id="ccNo" type="text" autocomplete="off"  placeholder="<?php echo Lang::$word->STR_CCN;?>">
        </label>
      </div>
      <div class="three fields">
        <div class="field">
          <label><?php echo Lang::$word->STR_CCV;?></label>
          <label class="input">
            <input id="cvv" type="text" autocomplete="off"  placeholder="<?php echo Lang::$word->STR_CCV;?>">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->STR_CEXM;?></label>
          <label class="input">
            <input id="expMonth" type="text" autocomplete="off" placeholder="MM">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->STR_CEXY;?></label>
          <label class="input">
            <input id="expYear" type="text" autocomplete="off" placeholder="YYYY">
          </label>
        </div>
      </div>
      <div class="two fields">
        <div class="field">
          <label><?php echo Lang::$word->FNAME;?></label>
          <label class="input"> <i class="icon-append icon asterisk"></i>
            <input type="text" name="fname" value="<?php echo Auth::$userdata->fname;?>" placeholder="First Name">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LNAME;?></label>
          <label class="input"> <i class="icon-append icon asterisk"></i>
            <input type="text" name="lname" value="<?php echo Auth::$userdata->lname;?>" placeholder="Last Name">
          </label>
        </div>
      </div>
      <div class="three fields">
        <div class="field">
          <label><?php echo Lang::$word->ADDRESS;?></label>
          <label class="input"> <i class="icon-append icon asterisk"></i>
            <input type="text" name="address" value="<?php echo Auth::$userdata->address;?>" placeholder="Address">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CITY;?></label>
          <label class="input"> <i class="icon-append icon asterisk"></i>
            <input type="text" name="city" value="<?php echo Auth::$userdata->city;?>" placeholder="City">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->COUNTRY;?></label>
          <select name="country">
            <option value="">-- <?php echo Lang::$word->CNT_SELECT;?> --</option>
            <?php echo Utility::loopOptions($content->getCountryList(), "abbr", "name", Auth::$userdata->country);?>
          </select>
        </div>
      </div>
      <div class="three fields">
        <div class="field">
          <label><?php echo Lang::$word->EMAIL;?></label>
          <label class="input"> <i class="icon-append icon asterisk"></i>
            <input type="text" name="email" value="<?php echo $auth->email;?>" readonly placeholder="Email Address">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->ZIP;?></label>
          <label class="input"> <i class="icon-append icon asterisk"></i>
            <input type="text" name="zip" value="<?php echo Auth::$userdata->zip;?>" placeholder="Zip/Postal Code">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->STATE;?></label>
          <select name="state" id="state">
            <option value="">--- Select State/Province ---</option>
            <option value="AB">Alberta</option>
            <option value="BC">British Columbia</option>
            <option value="MB">Manitoba</option>
            <option value="NB">New Brunswick</option>
            <option value="NF">Newfoundland</option>
            <option value="NT">Northwest Territories</option>
            <option value="NS">Nova Scotia</option>
            <option value="NVT">Nunavut</option>
            <option value="ON">Ontario</option>
            <option value="PE">Prince Edward Island</option>
            <option value="QC">Quebec</option>
            <option value="SK">Saskatchewan</option>
            <option value="YK">Yukon</option>
            <option value="AL">Alabama</option>
            <option value="AK">Alaska</option>
            <option value="AZ">Arizona</option>
            <option value="AR">Arkansas</option>
            <option value="BVI">British Virgin Islands</option>
            <option value="CA">California</option>
            <option value="CO">Colorado</option>
            <option value="CT">Connecticut</option>
            <option value="DE">Delaware</option>
            <option value="FL">Florida</option>
            <option value="GA">Georgia</option>
            <option value="GU">Guam</option>
            <option value="HI">Hawaii</option>
            <option value="ID">Idaho</option>
            <option value="IL">Illinois</option>
            <option value="IN">Indiana</option>
            <option value="IA">Iowa</option>
            <option value="KS">Kansas</option>
            <option value="KY">Kentucky</option>
            <option value="LA">Louisiana</option>
            <option value="ME">Maine</option>
            <option value="MP">Mariana Islands</option>
            <option value="MPI">Mariana Islands (Pacific)</option>
            <option value="MD">Maryland</option>
            <option value="MA">Massachusetts</option>
            <option value="MI">Michigan</option>
            <option value="MN">Minnesota</option>
            <option value="MS">Mississippi</option>
            <option value="MO">Missouri</option>
            <option value="MT">Montana</option>
            <option value="NE">Nebraska</option>
            <option value="NV">Nevada</option>
            <option value="NH">New Hampshire</option>
            <option value="NJ">New Jersey</option>
            <option value="NM">New Mexico</option>
            <option value="NY">New York</option>
            <option value="NC">North Carolina</option>
            <option value="ND">North Dakota</option>
            <option value="OH">Ohio</option>
            <option value="OK">Oklahoma</option>
            <option value="OR">Oregon</option>
            <option value="PA">Pennsylvania</option>
            <option value="PR">Puerto Rico</option>
            <option value="RI">Rhode Island</option>
            <option value="SC">South Carolina</option>
            <option value="SD">South Dakota</option>
            <option value="TN">Tennessee</option>
            <option value="TX">Texas</option>
            <option value="UT">Utah</option>
            <option value="VT">Vermont</option>
            <option value="USVI">VI  U.S. Virgin Islands</option>
            <option value="VA">Virginia</option>
            <option value="WA">Washington</option>
            <option value="DC">Washington, D.C.</option>
            <option value="WV">West Virginia</option>
            <option value="WI">Wisconsin</option>
            <option value="WY">Wyoming</option>
            <option value="N/A">Other</option>
          </select>
        </div>
      </div>
      <div class="clearfix">
        <button class="wojo secondary button" name="payment_status" type="submit"><?php echo Lang::$word->SUBMITP;?></button>
      </div>
    </div>
    <input id="token" name="token" type="hidden" value="">
    <input type="hidden" id="item_number" name="item_number" value="<?php echo $row->id;?>" />
  </form>
</div>
<div id="coResult"></div>
<script type="text/javascript">
  $("select").selecter();
  // Called when token created successfully.
  var successCallback = function(data) {
	  $('#myCCForm').addClass('loading');
      $.ajax({
          url: $('#myCCForm').attr('action'),
          type: $('#myCCForm').attr('method'),
		  dataType: 'json',
          data: {
              token: data.response.token.token,
			  fname: $("input[name='fname']").val(),
			  lname: $("input[name='lname']").val(),
			  address: $("input[name='address']").val(),
			  city: $("input[name='city']").val(),
			  state: $("select[name='state']").val(),
			  zip: $("input[name='zip']").val(),
			  country: $("select[name='country']").val(),
			  item_number: $('#item_number').val()
          },
          success: function(json) {
			  if (json.type == "success") {
				  $('#coResult').html(json.message);
				  setTimeout(function() {
						  window.location.href = '<?php echo Url::doUrl(URL_ACCOUNT);?>';
					  },
					  4000);
			  } else {
				  $("#coResult").html(json.message);
			  }
			  $('#myCCForm').removeClass('loading');
          }
      });
	  
  };
  var errorCallback = function(data) {
      if (data.errorCode === 200) {
          tokenRequest();
      } else {
		$.sticky(decodeURIComponent("<?php echo Lang::$word->PROCCESS_ERR1;?>"), {
			type: "error",
			title: "<?php echo Lang::$word->ERROR;?>"
		});
      }
  };

  var tokenRequest = function() {
      var args = {
          sellerId: "<?php echo $grows->extra;?>",
          publishableKey: "<?php echo $grows->extra3;?>",
          ccNo: $("#ccNo").val(),
          cvv: $("#cvv").val(),
          expMonth: $("#expMonth").val(),
          expYear: $("#expYear").val()
      };
      TCO.requestToken(successCallback, errorCallback, args);
  };
  $.getScript('https://www.2checkout.com/checkout/api/2co.min.js', function() {
      try {
          TCO.loadPubKey('<?php echo $grows->live ? 'production' : 'sandbox';?>');
          $("#myCCForm").submit(function(e) {
              tokenRequest();
              return false;
          });
      } catch (e) {
          alert(e.toSource());
      }
  });
</script> 