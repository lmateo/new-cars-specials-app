<?php
  /**
   * Configuration
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: configuration.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if(!Users::checkAcl("owner", "admin")): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->CF_SUB;?></div>
      <p><?php echo Lang::$word->CF_INFO . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_COMPANY;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $core->company;?>" name="company" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_DIR;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $core->site_dir;?>" name="site_dir">
          <div class="wojo corner label"> </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_EMAIL;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $core->site_email;?>" name="site_email" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label>Web Specials Email</label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $core->webspecials_email;?>" name="webspecials_email">
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="three fields">
     <div class="field">
        <label><?php echo Lang::$word->ADDRESS;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $core->address;?>" name="address" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CITY;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $core->city;?>" name="city" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STATE;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $core->state;?>" name="state" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
     
    </div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->ZIP;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $core->zip;?>" name="zip" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->COUNTRY;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $core->country;?>" name="country" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_PHONE;?></label>
        <input type="text" value="<?php echo $core->phone;?>" name="phone">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_FAX;?></label>
        <input type="text" value="<?php echo $core->fax;?>" name="fax">
      </div>
    </div>
    <div class="wojo fitted article divider"></div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_FBID;?></label>
        <input type="text" value="<?php echo $core->fb;?>" name="fb">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_TWID;?></label>
        <input type="text" value="<?php echo $core->twitter;?>" name="twitter">
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_THEME;?></label>
        <select name="theme">
          <?php File::getThemes(BASEPATH."/themes", $core->theme);?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_VINAPI;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_VINAPI_T;?>"></i></label>
        <input type="text" value="<?php echo $core->vinapi;?>" name="vinapi">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_MAPAPI;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_MAPAPI_T;?>"></i></label>
        <input type="text" value="<?php echo $core->mapapi;?>" name="mapapi">
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_LOGO;?></label>
        <label class="input">
          <input type="file" name="logo" id="logo" class="filefield">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_LOGOI;?></label>
        <label class="input">
          <input type="file" name="logoi" id="logoi" class="filefield">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
      <div class="two fields fitted">
      <div class="field">
        <label><?php echo Lang::$word->CF_SHORTDATE;?></label>
        <select name="short_date">
          <?php echo Utility::getShortDate($core->short_date);?>
        </select>
        </div>
        <div class="field">
         <label><?php echo Lang::$word->CF_TIME;?></label>
        <select name="time_format">
          <?php echo Utility::getTimeFormat($core->time_format);?>
        </select>
        </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_LONGDATE;?></label>
        <select name="long_date">
          <?php echo Utility::getLongDate($core->long_date);?>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_DTZ;?></label>
        <select name="dtz">
          <?php echo Utility::getTimezones();?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_LOCALES;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_LOCALES_T;?>"></i></label>
        <select name="locale">
          <?php echo Utility::localeList($core->locale);?>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_WEEKSTART;?></label>
        <select name="weekstart">
          <?php echo Utility::weekList(true, true, $core->weekstart);?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_LANG;?></label>
        <select name="lang">
          <?php foreach(Lang::fetchLanguage() as $langlist):?>
          <option value="<?php echo $langlist;?>"<?php if($core->lang == $langlist) echo ' selected="selected"';?>><?php echo strtoupper($langlist);?></option>
          <?php endforeach;?>
        </select>
      </div>
    </div>
    <div class="wojo fitted article divider"></div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_INVI;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_INVI_T;?>"></i></label>
        <textarea class="altpost" name="inv_info"><?php echo $core->inv_info;?></textarea>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_INVF;?></label>
        <textarea class="altpost" name="inv_note"><?php echo $core->inv_note;?></textarea>
      </div>
    </div>
    <div class="wojo fitted article divider"></div>
    <div class="four fields">
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_OFFLINE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="offline" value="1" onclick="$('.offline-data').slideDown();" <?php Validator::getChecked($core->offline, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="offline" value="0" onclick="$('.offline-data').slideUp();" <?php Validator::getChecked($core->offline, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_EUCOOKIE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="eucookie" value="1" <?php Validator::getChecked($core->eucookie, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="eucookie" value="0" <?php Validator::getChecked($core->eucookie, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_PERPAGE;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_PERPAGE_T;?>"></i></label>
        <input class="wojo range slider" type="range" min="10" max="50" step="2" name="perpage" value="<?php echo $core->perpage;?>">
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_PSIZE;?></label>
        <select name="pagesize">
          <option value="A4" <?php if ($core->pagesize == "A4") echo "selected=\"selected\"";?>>A4</option>
          <option value="LETTER" <?php if ($core->pagesize == "LETTER") echo "selected=\"selected\"";?>>LETTER</option>
        </select>
      </div>
    </div>
    <div class="two fields offline-data"<?php echo ($core->offline) ? "" : " style=\"display:none\""; ?>>
      <div class="field">
        <label><?php echo Lang::$word->CF_OFFLINE_DATE;?></label>
        <div class="wojo labeled icon input"><i class="icon-prepend icon calendar"></i>
          <input type="text" data-datepicker="true" value="<?php echo (strtotime($core->offline_d) === false) ? date('Y-m-d') : $core->offline_d;?>" name="offline_d">
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        <div class="half-top-space"></div>
        <label><?php echo Lang::$word->CF_OFFLINE_TIME;?></label>
        <div class="wojo labeled icon input"><i class="icon-prepend icon clock"></i>
          <input type="text" data-timepicker="true" value="<?php echo ($core->offline_t === "00:00:00") ? date('H:i:s') : $core->offline_t;?>" name="offline_t">
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_OFFLINE_MSG;?></label>
        <textarea class="altpost" name="offline_msg"><?php echo $core->offline_msg;?></textarea>
      </div>
    </div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_CURRENCY;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_CURRENCY_T;?>"></i></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $core->currency;?>" name="currency" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_TAX;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_TAX_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="tax" value="1" <?php Validator::getChecked($core->tax, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="tax" value="0" <?php Validator::getChecked($core->tax, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_FEATURED;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_FEATURED_T;?>"></i></label>
        <input class="wojo range slider" type="range" min="5" max="50" step="1" name="featured" value="<?php echo $core->featured;?>">
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_SPERPAGE;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_SPERPAGE_T;?>"></i></label>
        <input class="wojo range slider" type="range" min="10" max="100" step="1" name="sperpage" value="<?php echo $core->sperpage;?>">
      </div>
    </div>
    <div class="wojo fitted article divider"></div>
    <div class="four fields">
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_NOTIFY;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_NOTIFY_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="notify_admin" value="1" <?php Validator::getChecked($core->notify_admin, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="notify_admin" value="0" <?php Validator::getChecked($core->notify_admin, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_NOTIFY_EMAIL;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_NOTIFY_EMAIL_T;?>"></i></label>
        <input type="text" value="<?php echo $core->notify_email;?>" name="notify_email">
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_SOLD;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_SOLD_T;?>"></i></label>
        <input class="wojo range slider" type="range" min="1" max="20" step="1" name="number_sold" value="<?php echo $core->number_sold;?>">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_SPEAD;?></label>
        <select name="odometer">
          <option value="km"<?php if ($core->odometer == "km") echo " selected=\"selected\"";?>><?php echo Lang::$word->KM;?></option>
          <option value="mi"<?php if ($core->odometer == "mi") echo " selected=\"selected\"";?>><?php echo Lang::$word->MI;?></option>
        </select>
      </div>
    </div>
    <div class="four fields">
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_SHOWHOME;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_SHOWHOME_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="show_home" value="1" <?php Validator::getChecked($core->show_home, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="show_home" value="0" <?php Validator::getChecked($core->show_home, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_SHOWSLIDER;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_SHOWSLIDER_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="show_slider" value="1" <?php Validator::getChecked($core->show_slider, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="show_slider" value="0" <?php Validator::getChecked($core->show_slider, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_SHOWNEWS;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_SHOWNEWS_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="show_news" value="1" <?php Validator::getChecked($core->show_news, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="show_news" value="0" <?php Validator::getChecked($core->show_news, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->CF_AUTOAPP;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_AUTOAPP_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="autoapprove" value="1" <?php Validator::getChecked($core->autoapprove, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="autoapprove" value="0" <?php Validator::getChecked($core->autoapprove, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo fitted article divider"></div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_MAILER;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_MAILER_T;?>"></i></label>
        <select name="mailer" id="mailerchange" class="selectbox">
          <option value="PHP" <?php if ($core->mailer == "PHP") echo "selected=\"selected\"";?>>PHP Mailer</option>
          <option value="SMAIL" <?php if ($core->mailer == "SMAIL") echo "selected=\"selected\"";?>>Sendmail</option>
          <option value="SMTP" <?php if ($core->mailer == "SMTP") echo "selected=\"selected\"";?>>SMTP Mailer</option>
        </select>
      </div>
      <div class="field showsmail">
        <label><?php echo Lang::$word->CF_SMAILPATH;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_SMAILPATH_T;?>"></i></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $core->sendmail;?>" name="sendmail">
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="showsmtp">
      <div class="two fields">
        <div class="field">
          <label><?php echo Lang::$word->CF_SMTP_HOST;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_SMTP_HOST_T;?>"></i></label>
          <div class="wojo labeled icon input">
            <input type="text" value="<?php echo $core->smtp_host;?>" name="smtp_host">
            <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CF_SMTP_USER;?></label>
          <div class="wojo labeled icon input">
            <input type="text" value="<?php echo $core->smtp_user;?>" name="smtp_user">
            <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
          </div>
        </div>
      </div>
      <div class="three fields">
        <div class="field">
          <label><?php echo Lang::$word->CF_SMTP_PASS;?></label>
          <div class="wojo labeled icon input">
            <input type="text" value="<?php echo $core->smtp_pass;?>" name="smtp_pass">
            <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CF_SMTP_PORT;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_SMTP_PORT_T;?>"></i></label>
          <div class="wojo labeled icon input">
            <input type="text" value="<?php echo $core->smtp_port;?>" name="smtp_port">
            <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CF_SMTP_SSL;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_SMTP_SSL_T;?>"></i></label>
          <div class="inline-group">
            <label class="radio">
              <input name="is_ssl" type="radio" value="1" <?php Validator::getChecked($core->is_ssl, 1); ?>>
              <i></i><?php echo Lang::$word->YES;?></label>
            <label class="radio">
              <input name="is_ssl" type="radio" value="0" <?php Validator::getChecked($core->is_ssl, 0); ?>>
              <i></i> <?php echo Lang::$word->NO;?> </label>
          </div>
        </div>
      </div>
    </div>
    <div class="wojo fitted article divider"></div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_GA;?><i class="icon pin" data-content="<?php echo Lang::$word->CF_GA_T;?>"></i></label>
        <textarea name="analytics"><?php echo $core->analytics;?></textarea>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_METAKEY;?></label>
        <textarea name="metakeys"><?php echo $core->metakeys;?></textarea>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_METADESC;?></label>
        <textarea name="metadesc"><?php echo $core->metadesc;?></textarea>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processConfig" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->CF_UPDATE;?></button>
    </div>
  </form>
</div>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function () {
     var res2 = '<?php echo $core->mailer;?>';
     (res2 == "SMTP") ? $('.showsmtp').slideDown() : $('.showsmtp').slideUp();
     $('#mailerchange').change(function () {
         var res = $("#mailerchange option:selected").val();
         (res == "SMTP") ? $('.showsmtp').slideDown() : $('.showsmtp').slideUp();
     });

     (res2 == "SMAIL") ? $('.showsmail').slideDown() : $('.showsmail').slideUp();
     $('#mailerchange').change(function () {
         var res = $("#mailerchange option:selected").val();
         (res == "SMAIL") ? $('.showsmail').slideDown() : $('.showsmail').slideUp();
     });
});
// ]]>
</script>