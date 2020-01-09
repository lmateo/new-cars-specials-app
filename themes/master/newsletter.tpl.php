<?php
  /**
   * Newsletter
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: newsletter.tpl.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<div id="home_nletter">
  <div class="content-center">
    <div class="wojo tripple space divider"></div>
    <h3 class="wojo header"><?php echo Lang::$word->HOME_SUB4;?></h3>
    <p><?php echo Lang::$word->HOME_SUB4P;?></p>
    <div class="wojo tripple space divider"></div>
  </div>
  <div class="columns">
    <div class="screen-20 phone-hide">&nbsp;</div>
    <div class="screen-60 phone-100">
      <div id="nlform" class="wojo form padding">
        <div class="two fields">
          <div class="field">
            <label class="input"><i class="icon-prepend icon user"></i>
              <input type="text" placeholder="<?php echo Lang::$word->EMN_NLN;?>" name="name">
            </label>
          </div>
          <div class="field">
            <label class="input"><i class="icon-prepend icon email"></i>
              <input type="text" placeholder="<?php echo Lang::$word->EMN_NLE;?>" name="email">
            </label>
          </div>
        </div>
        <div class="field">
          <div class="content-center">
            <div class="wojo space divider"></div>
            <a id="doNewsletter" class="wojo negative rounded button"><i class="icon paper plane"></i> <?php echo Lang::$word->EMN_BTS;?></a> </div>
        </div>
      </div>
    </div>
    <div class="screen-20 phone-hide">&nbsp;</div>
  </div>
  <div class="wojo tripple space divider"></div>
</div>