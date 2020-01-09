<?php
  /**
   * System
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: system.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if (!Users::checkAcl("owner", "admin")) : print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="laptop icon"></i>
    <div class="content">
    <div class="header"> <?php echo Lang::$word->SYS_SUB;?></div>
    <p><?php echo str_replace("[NAME]", $core->wojon, Lang::$word->SYS_INFO);?></p>
    </div>
    <ul class="wojo tabs fixed clearfix">
      <li><a data-tab="#cms"><?php echo Lang::$word->SYS_CMS_INF;?></a></li>
      <li><a data-tab="#php"><?php echo Lang::$word->SYS_PHP_INF;?></a></li>
      <li><a data-tab="#server"><?php echo Lang::$word->SYS_SER_INF;?></a></li>
      <li><a data-tab="#dbtables" class="last"><?php echo Lang::$word->SYS_DBTABLE_INF;?></a></li>
    </ul>
  </div>
  
<div id="cms" class="wojo tab item">
    <table class="wojo two column table">
      <thead>
        <tr>
          <th colspan="2"><?php echo Lang::$word->SYS_CMS_INF;?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo Lang::$word->SYS_CMS_VER;?>:</td>
          <td>v<?php echo $core->wojov;?> <span id="version"> </span></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_ROOT_URL;?>:</td>
          <td><?php echo SITEURL;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_ROOT_PATH;?>:</td>
          <td><?php echo BASEPATH;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_UPL_URL;?>:</td>
          <td><?php echo UPLOADURL;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_UPL_PATH;?>:</td>
          <td><?php echo UPLOADS;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_DEF_LANG;?>:</td>
          <td><?php echo strtoupper($core->lang);?></td>
        </tr>
      </tbody>
    </table>
</div>
  <div id="php" class="wojo tab item">
    <table class="wojo two column table">
      <thead>
        <tr>
          <th colspan="2"><?php echo Lang::$word->SYS_PHP_INF;?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo Lang::$word->SYS_PHP_VER;?>:</td>
          <td><?php echo phpversion();?></td>
        </tr>
        <tr>
          <?php $gdinfo = gd_info();?>
          <td><?php echo Lang::$word->SYS_GD_VER;?>:</td>
          <td><?php echo $gdinfo['GD Version'];?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_MQR;?>:</td>
          <td><?php echo (ini_get('magic_quotes_gpc')) ? Lang::$word->ON : Lang::$word->OFF;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_LOG_ERR;?>:</td>
          <td><?php echo (ini_get('log_errors')) ? Lang::$word->ON : Lang::$word->OFF;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_MEM_LIM;?>:</td>
          <td><?php echo ini_get('memory_limit');?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_RG;?>:</td>
          <td><?php echo (ini_get('register_globals')) ? Lang::$word->ON : Lang::$word->OFF;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_SM;?>:</td>
          <td><?php echo (ini_get('safe_mode')) ? Lang::$word->ON : Lang::$word->OFF;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_UMF;?>:</td>
          <td><?php echo ini_get('upload_max_filesize'); ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_PMF;?>:</td>
          <td><?php echo ini_get('post_max_size');?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_SSP;?>:</td>
          <td><?php echo ini_get('session.save_path' );?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div id="server" class="wojo tab item">
    <table class="wojo two column table">
      <thead>
        <tr>
          <th colspan="2"><?php echo Lang::$word->SYS_SER_INF;?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo Lang::$word->SYS_SER_OS;?>:</td>
          <td><?php echo php_uname('s')." (".php_uname('r').")";?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_SER_API;?>:</td>
          <td><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_SER_DB;?>:</td>
          <td><?php echo $db->getAttribute(PDO::ATTR_CLIENT_VERSION);?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_DBV;?>:</td>
          <td><?php echo $db->getAttribute(PDO::ATTR_SERVER_VERSION);?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_MEMALO;?>:</td>
          <td><?php echo ini_get('memory_limit');?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_STS;?>:</td>
          <td><?php echo File::getSize(disk_free_space("."));?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div id="dbtables" class="wojo tab item"> <?php print dbTools::optimizeDb();?> </div>
</div>
