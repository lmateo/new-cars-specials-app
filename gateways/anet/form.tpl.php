<?php
  /**
   * Authorize.Net Form
   *
   * @package Car Delaer Pro
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: form.tpl.php, v3.00 2015-03-20 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
  
  $months = array();
  
  for ($i = 1; $i <= 12; $i++) {
      $months[] = array(
		  'text' => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)), 
		  'value' => sprintf('%02d', $i)
	  );
  }
  
  $today = getdate();
  $year_expire = array();
  
  for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
      $year_expire[] = array(
		  'text' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)), 
		  'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
	  );
  }
?>
<div class="wojo segment">
  <form method="post" id="an_form" name="an_form">
    <div class="wojo inverted form">
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
            <input type="text" name="email" value="<?php echo $auth->email;?>" placeholder="Email Address">
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
      <div class="wojo divider"></div>
      <div class="two fields">
        <div class="field">
          <label>Select One</label>
          <div class="inline-group">
            <label class="radio">
              <input type="radio" name="cctype" value="V" >
              <i></i><img src="<?php echo SITEURL;?>/gateways/anet/vs.png" alt="" /></label>
            <label class="radio">
              <input type="radio" name="cctype" value="M">
              <i></i><img src="<?php echo SITEURL;?>/gateways/anet/mc.png" alt="" /></label>
            <label class="radio">
              <input type="radio" name="cctype" value="A" >
              <i></i><img src="<?php echo SITEURL;?>/gateways/anet/ae.png" alt="" /></label>
            <label class="radio">
              <input type="radio" name="cctype" value="D">
              <i></i><img src="<?php echo SITEURL;?>/gateways/anet/dc.png" alt="" /></label>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->STR_CCN;?></label>
          <label class="input"> <i class="icon-append icon asterisk"></i>
            <input type="text" name="ccn" placeholder="Card Number">
          </label>
        </div>
      </div>
      <div class="field">
        <label>Name On Card</label>
        <label class="input"> <i class="icon-append icon asterisk"></i>
          <input type="text" name="ccname" placeholder="Name On Card">
        </label>
      </div>
      <div class="three fields">
        <div class="field">
          <label><?php echo Lang::$word->STR_CCV;?> </label>
          <label class="input"> <i class="icon-prepend icon asterisk"></i> <i class="icon-append icon information" data-content="For Visa, MasterCard, and Discover cards, the card code is the last 3 digit number<br /> located on the back of your card on or above your signature line. For an American Express card, it is the 4 digits on the FRONT above the end of your card number"></i>
            <input type="text" name="cvv" placeholder="CVV">
          </label>
        </div>
        <div class="field">
          <label>Month</label>
          <select name="month">
            <?php foreach ($months as $month): ?>
            <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
            <?php endforeach;?>
          </select>
        </div>
        <div class="field">
          <label>Year</label>
          <select name="year">
            <?php foreach ($year_expire as $year):?>
            <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
            <?php endforeach;?>
          </select>
        </div>
      </div>
      <div class="wojo divider"></div>
      <div class="field">
        <button class="wojo secondary button" name="doauth" type="button"><?php echo Lang::$word->SUBMITP;?></button>
      </div>
    </div>
  </form>
  <div id="msgholder"></div>
</div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
	$('body [data-content]').tooltip({
		trigger: 'hover',
		placement: 'auto'
	});
	$("select").selecter();

	$('body').on('click', 'button[name=doauth]', function() {
		var $parent =  $(this).closest('.wojo.form');
		function showResponse(message) {
			$($parent).removeClass("loading");
			$(this).html(message);
		}

		function showLoader() {
			$($parent).addClass("loading");
		}
		var options = {
			target: "#msgholder",
			beforeSubmit: showLoader,
			success: showResponse,
			type: "post",
			url: SITEURL + "/gateways/anet/ipn.php",
			data: {
				doAnet: 1
			}
		};

		$('#an_form').ajaxForm(options).submit();
	});
});
// ]]>
</script><b></b>