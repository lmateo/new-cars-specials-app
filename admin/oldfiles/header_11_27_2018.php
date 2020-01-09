<?php
  /**
   * Header
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: header.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title><?php echo $core->company;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="shortcut icon" type="image/x-icon" href="<?php echo SITEURL;?>/assets/favicons/qfavicon.ico">
<script type="text/javascript">
var SITEURL = "<?php echo SITEURL; ?>";
var ADMINURL = "<?php echo ADMINURL; ?>";
</script>
<link href="<?php echo THEMEU . '/cache/' . Cache::cssCache(array('css/base.css','css/button.css','css/image.css','css/icon.css','css/flags.css','css/breadcrumb.css','css/tooltip.css','css/form-new.css','css/input.css','css/table.css','css/label.css','css/segment.css','css/message.css','css/divider.css','css/dropdown.css','css/list.css','css/header.css','css/menu.css','css/datepicker.css','css/editor.css','css/feed.css','css/utility.css','css/style.css'),'css');?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/global.js"></script>
</head>
<body>
<header class="clearfix">
  <div class="topnav">
    <div class="navitem"><a href="<?php echo ADMINURL;?>/" class="logo"><?php echo ($core->logo) ? '<img src="' . SITEURL . '/uploads/' . $core->logo . '" alt="'.$core->company . '">': '<i class="wojo icon"></i>';?></a> <a class="mnav"><i class="icon reorder"></i></a>
      <nav>
        <ul>
        	<?php if (Users::checkAcl("owner","admin")):?>
        	<li><a <?php if (in_array("locations", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("locations");?>">Dealership</a></li>
           <?php endif;?>
          <!--  <li><a<?php if (count($core->_url) == 1) echo ' class="active"';?> href="<?php echo ADMINURL;?>"><span><?php echo Lang::$word->DASH;?></span></a></li>
          <li><a <?php if (in_array("myaccount", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("transactions");?>"><span><?php echo Lang::$word->AMD_PAYS;?></span></a></li>-->
          <li class="normal"><a class="submenu<?php if (Url::isPartSet(1, array('myaccount', 'accstats', 'roles', 'staff', 'groups', 'members'))) echo ' active';?>"><span><?php echo Lang::$word->AMD_ACC;?></span></a>
            <ul>
              <li><a <?php if (in_array("myaccount", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("myaccount");?>"><?php echo Lang::$word->MY_ACCOUNT;?></a></li>
              <?php if (Users::checkAcl("owner")):?>
              <li><a <?php if (in_array("accstats", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("accstats");?>"><?php echo Lang::$word->M_TITLE3;?></a></li>
              <?php endif;?>
              <?php if (Users::checkAcl("owner")):?>
              <li><a <?php if (in_array("roles", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("roles");?>"><?php echo Lang::$word->M_TITLE4;?></a></li>
              <?php endif;?>
              <?php if (Users::checkAcl("owner", "admin")):?>
              <li><a <?php if (in_array("staff", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("staff");?>"><?php echo Lang::$word->M_TITLE6;?></a></li>
              <?php endif;?>
              <?php if (Users::checkAcl("owner")):?>
              <li><a <?php if (in_array("members", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("members");?>"><?php echo Lang::$word->CL_TITLE;?></a></li>
              <?php endif;?>
            </ul>
          </li>
         <?php if (Users::checkAcl("owner")):?>
          <li class="normal"><a class="submenu<?php if (Url::isPartSet(1, array('items', 'makes', 'models', 'features', 'categories', 'conditions', 'fuel', 'qinvupload', 'transmissions'))) echo ' active';?>"><span><?php echo Lang::$word->AMD_ITEMS;?></span></a>
            <ul>
              <li><a <?php if (in_array("items", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("items");?>"><?php echo Lang::$word->AMD_INV;?></a></li>
              <?php if(Auth::hasPrivileges('manage_approval')):?>
              <li><a <?php if (in_array("pending", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("pending");?>"><?php echo Lang::$word->LST_TITLE5;?></a></li>
              <?php endif;?>
              <?php if(Auth::hasPrivileges('manage_cats')):?>
              <li><a <?php if (in_array("categories", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("categories");?>"><?php echo Lang::$word->CAT_TITLE;?></a></li>
              <?php endif;?>
              <?php if(Auth::hasPrivileges('manage_makes')):?>
              <li><a <?php if (in_array("makes", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("makes");?>"><?php echo Lang::$word->AMD_MAKES;?></a></li>
              <?php endif;?>
              <?php if(Auth::hasPrivileges('manage_models')):?>
              <li><a <?php if (in_array("models", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("models");?>"><?php echo Lang::$word->AMD_MODELS;?></a></li>
              <?php endif;?>
              <?php if(Auth::hasPrivileges('manage_features')):?>
              <li><a <?php if (in_array("features", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("features");?>"><?php echo Lang::$word->AMD_FEAT;?></a></li>
              <?php endif;?>
              <?php if(Auth::hasPrivileges('manage_conditions')):?>
              <li><a <?php if (in_array("conditions", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("conditions");?>"><?php echo Lang::$word->AMD_COND;?></a></li>
              <?php endif;?>
              <?php if(Auth::hasPrivileges('manage_fuel')):?>
              <li><a <?php if (in_array("fuel", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("fuel");?>"><?php echo Lang::$word->AMD_FUEL;?></a></li>
              <?php endif;?>
              <?php if(Auth::hasPrivileges('manual_inventory')):?>
              <li><a <?php if (in_array("qinvupload", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("qinvupload");?>"><?php echo Lang::$word->AMD_MI;?></a></li>
              <?php endif;?>
              <?php if(Auth::hasPrivileges('manage_trans')):?>
              <li><a <?php if (in_array("transmissions", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("transmissions");?>"><?php echo Lang::$word->AMD_TRANS;?></a></li>
              <?php endif;?>
            </ul>
          </li>
          <?php endif;?>
          <li class="normal"><a class="submenu<?php if (Url::isPartSet(1, array('webspecials', 'makes', 'models', 'bodystyle', 'year', 'deal', 'lease','webspecialsemails'))) echo ' active';?>"><span>Web Specials</span></a>
            <ul>
              <li><a <?php if (in_array("webspecials", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("webspecials");?>">Manage Web Specials</a></li>
              <?php if(Auth::hasPrivileges('manage_makes')):?>
              <li><a <?php if (in_array("makes", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("makes");?>"><?php echo Lang::$word->AMD_MAKES;?></a></li>
              <?php endif;?>
              <?php if(Auth::hasPrivileges('manage_models')):?>
              <li><a <?php if (in_array("models", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("models");?>"><?php echo Lang::$word->AMD_MODELS;?></a></li>
              <?php endif;?>
              <?php if(Auth::hasPrivileges('manage_bodystyle')):?>
              <li><a <?php if (in_array("bodystyle", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("bodystyle");?>"><?php echo Lang::$word->BS_TITLE;?></a></li>
              <?php endif;?>
              <?php if(Auth::hasPrivileges('manage_year')):?>
              <li><a <?php if (in_array("year", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("year");?>"><?php echo Lang::$word->YEAR_TITLE;?></a></li>
              <?php endif;?>
              <?php if(Auth::hasPrivileges('manage_dealtype')):?>
              <li><a <?php if (in_array("deal", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("deal");?>"><?php echo Lang::$word->DEAL_TITLE;?></a></li>
              <?php endif;?>
              <?php if(Auth::hasPrivileges('manage_zerosingle')):?>
              <li><a <?php if (in_array("lease", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("lease");?>"><?php echo Lang::$word->LEASE_TITLE;?></a></li>
              <?php endif;?>
              <?php if (Users::checkAcl("owner", "admin")):?>
              <li><a <?php if (in_array("webspecialsemails", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("webspecialsmailer");?>">Web Specials Email</a></li>
              <?php endif;?>
            </ul>
          </li>
           <?php if (Users::checkAcl("owner")):?>
          <li class="normal"><a class="submenu<?php if (Url::isPartSet(1, array('etemplates', 'lmanager', 'pages', 'menus', 'faq', 'newsletter', 'coupons', 'slider', 'news'))) echo ' active';?>"><span><?php echo Lang::$word->AMD_CON;?></span></a>
            <ul>
              <li><a <?php if (in_array("pages", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("pages");?>"><?php echo Lang::$word->PAG_TITLE;?></a></li>
              <li><a <?php if (in_array("menus", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("menus");?>"><?php echo Lang::$word->MENU_TITLE;?></a></li>
              <?php if(Auth::hasPrivileges('manage_coupons')):?>
              <li><a <?php if (in_array("coupons", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("coupons");?>"><?php echo Lang::$word->DC_TITLE;?></a></li>
              <?php endif;?>
              <li><a <?php if (in_array("faq", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("faq");?>"><?php echo Lang::$word->AMD_FAQ;?></a></li>
              <?php if(Auth::hasPrivileges('manage_slider')):?>
              <li><a <?php if (in_array("slider", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("slider");?>"><?php echo Lang::$word->SLD_TITLE;?></a></li>
              <?php endif;?>
              <?php if(Auth::hasPrivileges('manage_reviews')):?>
              <li><a <?php if (in_array("reviews", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("reviews");?>"><?php echo Lang::$word->SRW_TITLE;?></a></li>
              <?php endif;?>
              <?php if(Auth::hasPrivileges('manage_news')):?>
              <li><a <?php if (in_array("news", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("news");?>"><?php echo Lang::$word->NWA_TITLE;?></a></li>
              <?php endif;?>
              <li><a <?php if (in_array("etemplates", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("etemplates");?>"><?php echo Lang::$word->ET_TITLE;?></a></li>
              <?php if (Users::checkAcl("owner", "admin")):?>
              <li><a <?php if (in_array("newsletter", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("mailer");?>"><?php echo Lang::$word->AMD_NEWS;?></a></li>
              <?php endif;?>
              <li><a <?php if (in_array("lmanager", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("lmanager");?>"><?php echo Lang::$word->LMG_TITLE;?></a></li>
            </ul>
          </li>
           <?php endif;?>
          <?php if (Users::checkAcl("owner")):?>
          <li class="normal"><a class="submenu<?php if (Url::isPartSet(1, array('configuration', 'countries', 'bans', 'gateways', 'packages', 'locations'))) echo ' active';?>"><span><?php echo Lang::$word->AMD_CONF;?></span></a>
            <ul>
              <li><a <?php if (in_array("configuration", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("configuration");?>"><?php echo Lang::$word->CF_TITLE;?></a></li>
              <li><a <?php if (in_array("countries", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("countries");?>"><?php echo Lang::$word->CNT_TITLE;?></a></li>
              <li><a <?php if (in_array("gateways", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("gateways");?>"><?php echo Lang::$word->GW_TITLE;?></a></li>
             <li><a <?php if (in_array("packages", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("packages");?>"><?php echo Lang::$word->MSM_TITLE;?></a></li>
              <li><a <?php if (in_array("bans", $core->_url)) echo ' class="active"';?> href="<?php echo Url::adminUrl("bans");?>"><?php echo Lang::$word->BL_TITLE;?></a></li>
            </ul>
          </li>
          <?php endif;?>
        </ul>
      </nav>
    </div>
    <div class="navitem">
      <div class="wojo separator"></div>
      <div class="wojo top right pointing dropdown">
        <div class="text"><img src="<?php echo UPLOADURL;?>avatars/<?php echo ($auth->avatar) ? $auth->avatar : "blank.png";?>" alt="" class="wojo basic image avatar"><?php echo Lang::$word->HELLO;?> <?php echo $auth->username;?>! <i class="icon chevron down"></i></div>
        <div class="wojo vertical menu"> <?php if (Users::checkAcl("owner")):?> <a class="item" href="<?php echo Url::adminUrl("system");?>"><i class="icon laptop"></i><?php echo Lang::$word->AMD_SYS;?></a> <a class="item" href="<?php echo Url::adminUrl("backup");?>"><i class="icon database"></i><?php echo Lang::$word->AMD_BCK;?></a><?php endif;?> <a class="item" href="<?php echo ADMINURL;?>/logout.php"><i class="icon power"></i><?php echo Lang::$word->LOGOUT;?></a>
          <div class="wojo divider"></div>
          <div id="langmenu">
            <?php foreach(Lang::fetchLanguage() as $lang):?>
            <?php if(Core::$language == $lang):?>
            <div class="item active" data-text="<?php echo strtoupper($lang);?>"><span class="flag icon <?php echo $lang;?>"></span><?php echo strtoupper($lang);?></div>
            <?php else:?>
            <a href="<?php echo SITEURL . '/' . str_replace("url=","",$_SERVER['QUERY_STRING']);?>" class="item" data-lang="<?php echo $lang;?>"><span class="flag icon <?php echo $lang;?>"></span> <?php echo strtoupper($lang);?></a>
            <?php endif;?>
            <?php endforeach;?>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
<div id="crumbs" class="clearfix"> <span class="wojo primary label"><?php echo Core::$language;?></span>
  <div class="wojo breadcrumb">
    <?php include_once("crumbs.php");?>
  </div>
</div>