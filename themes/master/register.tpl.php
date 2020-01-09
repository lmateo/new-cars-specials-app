<?php
  /**
   * Registration Page
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: register.tpl.php, v1.00 2015-08-05 10:16:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  if ($auth->is_User())
      Url::redirect(Url::doUrl(URL_ACCOUNT));
	    
  $datacountry = $content->getCountryList();
?>
<div class="wojo-grid">
  <div class="wojo secondary segment">
    <div class="wojo huge fitted inverted header"><i class="note icon"></i>
      <div class="content"> <?php echo Lang::$word->REG_TITLE;?>
        <p class="subheader"><?php echo Lang::$word->REG_SUB;?></p>
      </div>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="columns">
      <div class="screen-50 tablet-100 phone-100">
        <div class="wojo quaternary form inverted segment eq">
          <div class="field">
            <label><?php echo Lang::$word->USERNAME;?></label>
            <div class="wojo labeled icon input">
              <input type="text" placeholder="<?php echo Lang::$word->USERNAME;?>" name="username" required>
              <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label><?php echo Lang::$word->PASSWORD;?></label>
              <div class="wojo labeled icon input">
                <input name="password" type="password" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->PASSWORD_C;?></label>
              <div class="wojo labeled icon input">
                <input name="password2" type="password" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->EMAIL;?></label>
            <div class="wojo labeled icon input">
              <input name="email" placeholder="<?php echo Lang::$word->EMAIL;?>" type="text" required>
              <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label><?php echo Lang::$word->COMPANY;?></label>
              <input name="company" placeholder="<?php echo Lang::$word->COMPANY;?>" type="text">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->WEBSITE;?></label>
              <input name="url" placeholder="<?php echo Lang::$word->WEBSITE;?>" type="text">
            </div>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->CAPTCHA;?></label>
            <label class="input"><img src="<?php echo SITEURL;?>/captcha.php" alt="" class="captcha-append">
              <input name="captcha" placeholder="<?php echo Lang::$word->CAPTCHA;?>" type="text" required>
            </label>
          </div>
          <input data-geo="country" name="tmpcountry" type="hidden" value="">
        </div>
      </div>
      <div class="screen-50 tablet-100 phone-100">
        <div class="wojo secondary form segment eq">
          <div class="two fields">
            <div class="field">
              <label class="inverted"><?php echo Lang::$word->FNAME;?></label>
              <div class="wojo labeled icon input">
                <input name="fname" placeholder="<?php echo Lang::$word->FNAME;?>" type="text" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
            <div class="field">
              <label class="inverted"><?php echo Lang::$word->LNAME;?></label>
              <div class="wojo labeled icon input">
                <input name="lname" placeholder="<?php echo Lang::$word->LNAME;?>" type="text" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label class="inverted"><?php echo Lang::$word->ADDRESS;?></label>
              <div class="wojo labeled icon input">
                <input type="text" data-geo="name" placeholder="<?php echo Lang::$word->ADDRESS;?>" name="address" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
            <div class="field">
              <label class="inverted"><?php echo Lang::$word->CITY;?></label>
              <div class="wojo labeled icon input">
                <input type="text" data-geo="locality" placeholder="<?php echo Lang::$word->CITY;?>" name="city" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label class="inverted"><?php echo Lang::$word->STATE;?></label>
              <div class="wojo labeled icon input">
                <input type="text" data-geo="administrative_area_level_1" placeholder="<?php echo Lang::$word->STATE;?>" name="state" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
            <div class="field">
              <label class="inverted"><?php echo Lang::$word->ZIP;?></label>
              <div class="wojo labeled icon input">
                <input type="text" data-geo="postal_code" placeholder="<?php echo Lang::$word->ZIP;?>" name="zip" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
          </div>
          <div class="field">
            <label class="inverted"><?php echo Lang::$word->COUNTRY;?></label>
            <select name="country">
              <option value="">-- <?php echo Lang::$word->CNT_SELECT;?> --</option>
              <?php echo Utility::loopOptions($datacountry, "abbr", "name");?>
            </select>
          </div>
          <p class="wojo primary text content-center"><?php echo Lang::$word->REG_ADD_T;?></p>
          <div class="field content-center">
            <label>&nbsp;</label>
            <button type="button" data-action="register" name="dosubmit" class="wojo negative rounded button"><?php echo Lang::$word->REG_ACC;?></button>
          </div>
          <div class="wojo space divider"></div>
          <p class="content-center"><a href="<?php echo Url::doUrl(URL_LOGIN);?>"><i class="left long arrow icon"></i> <?php echo Lang::$word->BACKTOLOGIN;?></a></p>
        </div>
      </div>
    </div>
    <input data-geo="lat" name="lat" type="hidden" value="43.7000">
    <input data-geo="lng" name="lng" type="hidden" value="79.4000">
  </form>
</div>
<script src="//maps.googleapis.com/maps/api/js?key=<?php echo $core->mapapi;?>&libraries=places"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/geocomplete.js"></script> 
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function() {
    $("input[name=address]").geocomplete({
        details: "form",
        detailsAttribute: "data-geo",
        types: ["geocode", "establishment"]
    }).on("geocode:result", function(e, result) {
        var country = $("input[name=tmpcountry]").val()
		$('select[name=country] option:contains("' + country + '")').prop("selected", "selected")
		$('select[name=country]').selecter("update").val();
    });
});
// ]]>
</script> 