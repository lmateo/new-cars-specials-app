<?php
  /**
   * My Settings
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: mysettings.tpl.php, v1.00 2015-08-05 10:16:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if (!$auth->is_User())
      Url::redirect(Url::doUrl(URL_LOGIN));
	  
  $mrow = $user->getUserPackage();
  $datacountry = $content->getCountryList();
?>
<div class="wojo-grid">
  <div class="wojo secondary segment">
    <div class="wojo huge fitted inverted header">
      <div class="content"> <?php echo Lang::$word->HOME_SUB12;?>
        <p class="subheader"><?php echo Lang::$word->HOME_SUB12P;?></p>
      </div>
    </div>
    <div id="userMenu">
      <div class="wojo labeled right icon fluid dropdown button"> <i class="angle down icon"></i> <span class="text"><?php echo Lang::$word->SETTINGS;?></span>
        <div class="menu"> 
        <a href="<?php echo Url::doUrl(URL_ACCOUNT);?>" class="item"><i class="icon note"></i><?php echo Lang::$word->PACKAGES;?></a> 
        <a href="<?php echo Url::doUrl(URL_MYLISTINGS);?>" class="item"><i class="icon car"></i><?php echo Lang::$word->LISTINGS;?></a> 
        <a href="<?php echo Url::doUrl(URL_ADDLISTING);?>" class="item"><i class="icon plus"></i><?php echo Lang::$word->LST_ADD;?></a> 
        <a class="item"><i class="icon cog"></i><?php echo Lang::$word->SETTINGS;?></a> 
        <a href="<?php echo Url::doUrl(URL_MYREVIEWS);?>" class="item"><i class="icon badge"></i><?php echo Lang::$word->SRW_ADD;?></a> </div>
      </div>
    </div>
  </div>
  <div class="wojo secondary bg">
    <div class="padding wojo tab item" id="settings">
      <p class="wojo basic message"><?php echo Lang::$word->HOME_SUB16P;?></p>
      <div class="wojo form">
        <form method="post" id="wojo_form" name="wojo_form">
          <div class="two fields">
            <div class="field">
              <label><?php echo Lang::$word->USERNAME;?></label>
              <label class="input">
                <input type="text" value="<?php echo $mrow->username;?>" disabled="disabled" name="username">
              </label>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->EMAIL;?></label>
              <label class="input"><i class="icon-append icon asterisk"></i>
                <input type="text" value="<?php echo $mrow->email;?>" name="email">
              </label>
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label><?php echo Lang::$word->FNAME;?></label>
              <label class="input"><i class="icon-append icon asterisk"></i>
                <input type="text" value="<?php echo $mrow->fname;?>" name="fname">
              </label>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LNAME;?></label>
              <label class="input"><i class="icon-append icon asterisk"></i>
                <input type="text" value="<?php echo $mrow->lname;?>" name="lname">
              </label>
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label><?php echo Lang::$word->COMPANY;?></label>
              <input type="text" value="<?php echo $mrow->company;?>" name="company">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->ADDRESS;?></label>
              <label class="input"><i class="icon-append icon asterisk"></i>
                <input type="text" value="<?php echo $mrow->address;?>" name="address">
              </label>
            </div>
          </div>
          <div class="four fields">
            <div class="field">
              <label><?php echo Lang::$word->CITY;?></label>
              <label class="input"><i class="icon-append icon asterisk"></i>
                <input type="text" value="<?php echo $mrow->city;?>" name="city">
              </label>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->STATE;?></label>
              <label class="input"><i class="icon-append icon asterisk"></i>
                <input type="text" value="<?php echo $mrow->state;?>" name="state">
              </label>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->ZIP;?></label>
              <label class="input"><i class="icon-append icon asterisk"></i>
                <input type="text" value="<?php echo $mrow->zip;?>" name="zip">
              </label>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->CF_PHONE;?></label>
              <label class="input">
                <input type="text" value="<?php echo $mrow->phone;?>" name="phone">
              </label>
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label><?php echo Lang::$word->WEBSITE;?></label>
              <label class="input">
                <input type="text" value="<?php echo $mrow->url;?>" name="url">
              </label>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->PASSWORD;?><i class="icon pin" data-content="<?php echo Lang::$word->M_PASS_T;?>"></i></label>
              <input type="text" name="password">
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label><?php echo Lang::$word->COUNTRY;?></label>
              <select name="country">
                <option value="">-- <?php echo Lang::$word->CNT_SELECT;?> --</option>
                <?php echo Utility::loopOptions($datacountry, "abbr", "name", $mrow->country);?>
              </select>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->AVATAR;?></label>
              <input type="file" name="avatar" data-type="image" data-exist="<?php echo ($mrow->avatar) ? UPLOADURL . 'avatars/' . $mrow->avatar : UPLOADURL . 'avatars/blank.png';?>" accept="image/png, image/jpeg">
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label><?php echo Lang::$word->LASTLOGIN;?></label>
              <label class="input">
                <input name="last_active" type="text" disabled value="<?php echo Utility::dodate("long_date", $mrow->last_active);?>" readonly>
              </label>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LASTIP;?></label>
              <label class="input">
                <input name="lastip" type="text" disabled value="<?php echo $mrow->lastip;?>" readonly>
              </label>
            </div>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->M_ABOUT;?> <i class="icon pin" data-content="<?php echo Lang::$word->M_ABOUT_T;?>"></i></label>
            <textarea name="about"><?php echo $mrow->about;?></textarea>
          </div>
          <div class="wojo fitted divider"></div>
          <div class="wojo footer">
            <button type="button" data-action="updateProfile" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->M_UPDATE;?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>