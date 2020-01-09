<?php
  /**
   * Header
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: header.tpl.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  $data = $core->Meta();
?>
<!doctype html>
<html>
<head>
<?php echo $data->meta;?>
<script type="text/javascript">
var SITEURL = "<?php echo SITEURL; ?>";
var ADMINURL = "<?php echo ADMINURL; ?>";
</script>
<link href="<?php echo THEMEU . '/cache/' . Cache::cssCache(array('css/base.css','css/button.css','css/image.css','css/icon.css','css/flags.css','css/breadcrumb.css','css/tooltip.css','css/form.css','css/input.css','css/table.css','css/label.css','css/segment.css','css/message.css','css/divider.css','css/dropdown.css','css/list.css','css/header.css','css/menu.css','css/datepicker.css','css/editor.css','css/feed.css','css/utility.css','css/style.css'),'css');?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/global.js"></script>
</head>
<body class="<?php echo $data->bodyClass ? $data->bodyClass : "mainbody";?>">
<div class="wojo-grid">
  <header>
    <div class="columns">
      <div class="screen-40 tablet-50 phone-100"><a href="<?php echo SITEURL;?>/" class="logo"><?php echo ($core->logo) ? '<img src="' . SITEURL . '/uploads/' . $core->logo . '" alt="' . $core->company . '">': '<i class="wojo icon"></i>';?></a></div>
      <div class="screen-60 tablet-50 phone-100">
        <div class="wojo secondary menu"> <a class="item" href="<?php echo SITEURL;?>/"> <?php echo Lang::$word->HOME;?> </a> <a class="item" href="<?php echo Url::doUrl(URL_LISTINGS);?>"> <?php echo Lang::$word->BROWSE;?> </a> <a class="item" href="<?php echo Url::doUrl(URL_SEARCH);?>"> <?php echo Lang::$word->SEARCH;?></a>
          <div class="right menu">
            <?php if($auth->is_User()):?>
            <div class="wojo item custom dropdown">
              <div class="text"><img src="<?php echo UPLOADURL;?>avatars/<?php echo ($auth->avatar) ? $auth->avatar : "blank.png";?>" alt="" class="wojo basic image small avatar"><?php echo Lang::$word->HELLO;?> <?php echo $auth->username;?>! </div>
              <div class="wojo menu"> <a class="item" href="<?php echo Url::doUrl(URL_ACCOUNT);?>"><i class="icon note"></i><?php echo Lang::$word->HOME_SUB12;?></a> <a class="item" href="<?php echo SITEURL;?>/logout.php"><i class="icon power"></i><?php echo Lang::$word->LOGOUT;?></a> </div>
            </div>
            <?php else:?>
            <a class="item" href="<?php echo Url::doUrl(URL_LOGIN);?>"> <?php echo Lang::$word->LOGIN;?> <i class="icon lock"></i></a> <a class="item" href="<?php echo Url::doUrl(URL_REGISTER);?>"> <?php echo Lang::$word->SIGNUP;?> <i class="icon note"></i></a>
            <?php endif;?>
            
            <!--/* Lang Switch Start */-->
            <div class="wojo item dropdown"> <?php echo Lang::$word->LANGUAGE;?> <i class="icon triangle down"></i>
              <div class="menu" id="langmenu">
                <?php foreach(Lang::fetchLanguage() as $lang):?>
                <?php if(Core::$language == $lang):?>
                <div class="item active" data-text="<?php echo strtoupper($lang);?>"><span class="flag icon <?php echo $lang;?>"></span><?php echo strtoupper($lang);?></div>
                <?php else:?>
                <a href="<?php echo SITEURL . '/' . str_replace("url=","",$_SERVER['QUERY_STRING']);?>" class="item" data-lang="<?php echo $lang;?>"><span class="flag icon <?php echo $lang;?>"></span> <?php echo strtoupper($lang);?></a>
                <?php endif;?>
                <?php endforeach;?>
              </div>
            </div>
            <!--/* Lang Switch Ends */--> 
            
            <!--/* Menu Start */-->
            <div class="wojo item custom dropdown"> <?php echo Lang::$word->MENU;?> <i class="icon apps"></i> 
              <div class="menu">
                <?php if($data->menus):?>
                <?php foreach($data->menus as $mrow):?>
                <?php if($mrow->home_page):?>
                <?php continue;?>
                <?php elseif($mrow->content_type == 'web'):?>
                <a href="<?php echo $mrow->link;?>" class="item" target="<?php echo $mrow->target;?>"><?php echo $mrow->name;?></a>
                <?php else:?>
                <a href="<?php echo Url::doUrl(URL_PAGE, $mrow->slug);?>" class="item"><?php echo $mrow->name;?></a>
                <?php endif;?>
                <?php endforeach;?>
                <?php endif;?>
              </div>
            </div>
            <!--/* Menu Ends */--> 
          </div>
        </div>
      </div>
    </div>
  </header>
</div>
