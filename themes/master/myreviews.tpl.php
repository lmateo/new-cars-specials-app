<?php
  /**
   * My Reviews
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: myreviews.tpl.php, v1.00 2015-08-05 10:16:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if (!$auth->is_User())
      Url::redirect(Url::doUrl(URL_LOGIN));
?>
<div class="wojo-grid">
  <div class="wojo secondary segment">
    <div class="wojo huge fitted inverted header">
      <div class="content"> <?php echo Lang::$word->HOME_SUB12;?>
        <p class="subheader"><?php echo Lang::$word->HOME_SUB12P;?></p>
      </div>
    </div>
    <div id="userMenu">
      <div class="wojo labeled right icon fluid dropdown button"> <i class="angle down icon"></i> <span class="text"><?php echo Lang::$word->SRW_ADD;?></span>
        <div class="menu"> <a href="<?php echo Url::doUrl(URL_ACCOUNT);?>" class="item"><i class="icon note"></i><?php echo Lang::$word->PACKAGES;?></a> <a href="<?php echo Url::doUrl(URL_MYLISTINGS);?>" class="item"><i class="icon car"></i><?php echo Lang::$word->LISTINGS;?></a> <a href="<?php echo Url::doUrl(URL_ADDLISTING);?>" class="item"><i class="icon plus"></i><?php echo Lang::$word->LST_ADD;?></a> <a href="<?php echo Url::doUrl(URL_MYSETTINGS);?>" class="item"><i class="icon cog"></i><?php echo Lang::$word->SETTINGS;?></a> <a class="item"><i class="icon badge"></i><?php echo Lang::$word->SRW_ADD;?></a> </div>
      </div>
    </div>
  </div>
  <div class="wojo secondary bg">
    <div class="padding">
      <p class="wojo basic message"><?php echo Lang::$word->HOME_SUB20P;?></p>
      <div class="wojo form">
        <form method="post" id="wojo_form" name="wojo_form">
          <div class="field">
            <label><?php echo Lang::$word->SRW_DESC;?></label>
            <textarea name="content"></textarea>
          </div>
          <div class="field">
            <label>Twitter ID</label>
            <label class="input">
              <input type="text" name="twitter">
            </label>
          </div>
          <div class="wojo fitted divider"></div>
          <div class="wojo footer">
            <button type="button" data-action="addReview" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->SRW_SUBMIT;?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>