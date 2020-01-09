<?php
  /**
   * Import CSV file to mysql
   *
   * @package Quirk Digital Marketing
   * @author Lorenzo Mateo
   * @copyright 2017
   * @version $Id: csvnodups.php, v1.00 2018-22-10 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php if(!$row = $db->first(Users::aTable, null, array('id' => $auth->uid))) : Message::invalid("ID" . $auth->uid); return; endif;?>

<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="files icon"></i>
    <div class="content">
      <div class="header"> CSV Import/Export <small>/ <?php echo $row->username;?></small> </div>
      <p>CSV Import & Export allows you to import and export CSV files from and to a MYSQL database.</p>
    </div>
  </div>
 <div id="iframediv">
   <iframe id="iframe" src="<?php echo SITENEWCARSCSVURL;?>" frameborder="0" style="width: 100%; border: none;"></iframe>
  </div>
  </div>
 
<script src="<?php echo SITENEWCARSCSVURL;?>vendor/iframe-resizer/iframeResizer.min.js"></script>
<script>iFrameResize({log:true}, '#iframe')</script>


