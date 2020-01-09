<?php
  /**
   * Login
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: login.tpl.php, v1.00 2015-08-05 10:16:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  if ($auth->is_User())
      Url::redirect(Url::doUrl(URL_ACCOUNT));

  if (isset($_POST['doLogin'])):
      if ($auth->loginUser($_POST['username'], $_POST['password'])):
		  Url::redirect(Url::doUrl(URL_ACCOUNT));
      endif;
  endif;
   
  $udata = Auth::getUserCookies();
?>
<div class="wojo-grid">
  <div class="wojo secondary segment">
    <div class="wojo huge fitted inverted header"><i class="lock icon"></i>
      <div class="content"> <?php echo Lang::$word->LOG_TITLE;?>
        <p class="subheader"><?php echo Lang::$word->LOG_SUB;?></p>
      </div>
    </div>
  </div>
  <div class="wojo tabular segment two cells">
    <div class="wojo cell center">
      <div class="wojo secondary segment eq">
        <div class="avatar"><img src="<?php echo UPLOADURL;?>avatars/<?php echo $udata['avatar'] ? $udata['avatar'] : "blank.png";?>" alt="" class="wojo medium basic image"></div>
        <p class="wojo secondary text half-top-space"><?php echo Utility::sayHello();?> <?php echo $udata['name'] ? $udata['name'] : Lang::$word->GUEST;?>!</p>
        <div class="wojo space divider"></div>
        <div class="wojo secondary text"><span class="wojo huge text"><?php echo Utility::dodate("MMMM", strftime("%B"));?></span>&nbsp;&nbsp;, <span class="wojo huge text"><?php echo strftime("%Y");?></span>
          <p class="wojo large text primary thin"><?php echo Utility::doTime(strftime("%r"));?></p>
        </div>
      </div>
    </div>
    <div class="wojo cell">
      <div class="wojo quaternary form inverted segment eq">
        <form id="admin_form" name="admin_form" method="post" class="loginform">
          <div id="loginform">
            <div class="field relaxed">
              <label><?php echo Lang::$word->USERNAME;?></label>
              <label class="input"><i class="icon-prepend icon user"></i> <i class="icon-append icon asterisk"></i>
                <input name="username" placeholder="<?php echo Lang::$word->USERNAME;?>" type="text">
              </label>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->PASSWORD;?></label>
              <label class="input"><i class="icon-prepend icon lock"></i> <i class="icon-append icon asterisk"></i>
                <input placeholder="++++++++" type="password" name="password">
              </label>
            </div>
            <div class="wojo double space divider"></div>
            <div class="field">
              <button type="submit" name="doLogin" class="wojo primary fluid button"><?php echo Lang::$word->LOGIN;?></button>
            </div>
            <div class="wojo space divider"></div>
            <p class="content-center"><a id="passreset" class="inverted"><i class="unlock icon"></i> <?php echo Lang::$word->PASSWORD_L;?></a></p>
          </div>
          <div id="passform" style="display:none">
            <div class="field relaxed">
              <label><?php echo Lang::$word->USERNAME;?></label>
              <label class="input"> <i class="icon-prepend icon user"></i> <i class="icon-append icon asterisk"></i>
                <input type="text" placeholder="<?php echo Lang::$word->USERNAME;?>" <?php echo $udata['username'] ? 'value="' . $udata['username'] . '"' : null;?> name="pusername">
              </label>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->EMAIL;?></label>
              <label class="input"> <i class="icon-prepend icon email"></i> <i class="icon-append icon asterisk"></i>
                <input  type="text" placeholder="<?php echo Lang::$word->EMAIL;?>"  name="email">
              </label>
            </div>
            <div class="wojo double space divider"></div>
            <div class="field">
              <button id="dopass" type="button" name="dopass" class="wojo negative fluid button"><?php echo Lang::$word->PASSWORD_L;?></button>
            </div>
            <div class="wojo space divider"></div>
            <p class="content-center"><a class="inverted" id="backto"><i class="left long arrow icon"></i> <?php echo Lang::$word->BACKTOLOGIN;?></a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div id="message-box"><?php print Message::$showMsg;?></div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $("#backto").on('click', function() {
        $("#passform").slideUp("slow");
        $("#loginform").slideDown("slow");
    });
    $("#passreset").on('click', function() {
        $("#loginform").slideUp("slow");
        $("#passform").slideDown("slow");
    });
    $("#dopass").on('click', function() {
        var $btn = $(this);
        $btn.addClass('loading');
        var email = $("input[name=email]").val();
        var uname = $("input[name=pusername]").val();
        $.ajax({
            type: 'post',
            url: "<?php echo SITEURL;?>/ajax/controller.php",
            data: {
				'action': "passReset",
                'email': email,
                'uname': uname
            },
            dataType: "json",
            success: function(json) {
                if (json.type == "success") {
                    $("#passform").html("<p class=\"wojo positive message\">" + json.message + "</p>");
                } else {
					$("input[name=email], input[name=pusername]").closest('.field').addClass('error');
				}
                $btn.removeClass('loading');
            }
        });
    });
});
</script>