<?php
  /**
   * Index
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: index.php, v1.00 2014-10-05 10:12:05 gewa Exp $
   */
  define("_WOJO", true);

  if (is_dir("../setup"))
      : die("<div style='text-align:center;margin-top:20px'>" 
		  . "<span style='padding: 10px; border: 1px solid #999; background-color:#EFEFEF;" 
		  . "font-family: Verdana; font-size: 12px; margin-left:auto; margin-right:auto;color:red'>" 
		  . "<b>Warning:</b> Please delete setup directory!</span></div>");
  endif;
    
  require_once("init.php");
  
  if (!$auth->is_Admin())
      Url::redirect(ADMINURL . '/login.php');
?>
<?php include("header.php");?>
<?php if (Users::checkAcl("owner","admin")):?>
<div class="wojo-grid">
  <?php (isset($core->_url[1]) and !in_array($core->_url[1], $exclude) and file_exists($core->_url[1].".php")) ? include($core->_url[1].".php") : include("locations.php");?>
</div>
<?php else: ?>
<div class="wojo-grid">
  <?php (isset($core->_url[1]) and !in_array($core->_url[1], $exclude) and file_exists($core->_url[1].".php")) ? include($core->_url[1].".php") : include("webspecials.php");?>
</div>
<?php endif;?>
<?php include("footer.php");?>