<?php
  /**
   * Login
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: login.php, v1.00 2014-10-20 10:12:05 gewa Exp $
   */
  define("_WOJO", true);
  require_once("init.php");
?>
<?php
  if ($auth->is_Admin())
      Url::redirect(ADMINURL);

  if (isset($_POST['submit'])):
      if ($auth->loginAdmin($_POST['username'], $_POST['password'])):
          Url::redirect(ADMINURL);
      endif;
  endif;
  
  $data = Auth::getUserCookies();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $core->company;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="shortcut icon" type="image/x-icon" href="http://qinventory.quirkspecials.com/assets/favicons/qfavicon.ico">
<link href="<?php echo ADMINURL;?>/assets/css/base.css" rel="stylesheet" type="text/css" />
<link href="<?php echo ADMINURL;?>/assets/css/icon.css" rel="stylesheet" type="text/css" />
<link href="<?php echo ADMINURL;?>/assets/css/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/global.js"></script>
</head>
<body>
<div id="container">
  <header>
    <h1>QUIRK CARS</h1>
    <br/>
    <h3>NEW CARS <br> WEB SPECIALS/INVENTORY MANAGEMENT</h3>
    <br/>
    <div><?php echo Utility::changeVehicleImg();?></div>
    <br/>
    <h5><?php echo Utility::sayHello();?> <?php echo $data['name'] ? $data['name'] : Lang::$word->GUEST;?>!</h5>
    <br/>
    <br/>
    </header>
  <form id="admin_form" name="admin_form" method="post" class="loginform">
    <div id="loginform">
      <div class="col">
        <label class="input"> <i class="icon-prepend icon user"></i>
          <input placeholder="<?php echo Lang::$word->USERNAME;?>" <?php echo $data['username'] ? 'value="' . $data['username'] . '"' : null;?> name="username">
        </label>
      </div>
      <div class="col">
        <label class="input"> <i class="icon-prepend icon lock"></i>
          <input placeholder="++++++++" type="password" name="password">
        </label>
      </div>
      <div class="col">
        <button name="submit" class="button-login"><?php echo Lang::$word->LOGIN;?></button>
      </div>
      <div class="col center">
        <label> <a id="passreset"><?php echo Lang::$word->PASSWORD_L;?>?</a></label>
      </div>
    </div>
    <div id="passform" style="display:none">
      <div class="col">
        <label class="input"> <i class="icon-prepend icon user"></i>
          <input placeholder="<?php echo Lang::$word->USERNAME;?>" <?php echo $data['username'] ? 'value="' . $data['username'] . '"' : null;?> name="pusername">
        </label>
      </div>
      <div class="col">
        <label class="input"> <i class="icon-prepend icon email"></i>
          <input placeholder="<?php echo Lang::$word->EMAIL;?>"  name="email">
        </label>
      </div>
      <div class="col">
        <button id="dopass" type="button" name="dopass" class="button-login alt"><?php echo Lang::$word->PASSWORD_RES;?></button>
      </div>
      <div class="col center">
        <label> <a id="backto"><?php echo Lang::$word->BACKTOLOGIN;?></a></label>
      </div>
    </div>
  </form>
  <footer>Copyright &copy;<?php echo date('Y').' '.$core->company;?></footer>
  <div id="message-box"><?php print Message::$showMsg;?> </div>
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
            url: "<?php echo SITEURL;?>/ajax/passreset.php",
            data: {
                'passReset': 1,
                'email': email,
                'uname': uname
            },
            dataType: "json",
            success: function(json) {
                if (json.type === "success") {
                    $("#loginform").slideUp("slow");
                    $("#passform").slideDown("slow")
                }
                $("#message-box").html(json.message);
                $btn.removeClass('loading');
            }
        });
    });
});
</script>
</body>
</html>