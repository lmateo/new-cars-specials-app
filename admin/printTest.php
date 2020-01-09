<?php
  /**
   * Print Webspecials
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: print.php, v1.00 2014-10-05 10:12:05 gewa Exp $
   */
  define("_WOJO", true);
  require_once("init.php");
  
  if (!$auth->is_Admin())
      exit;
?>
<?php $row2 = $wSpecials->getWebspecialsDate() ?>
<?php var_dump($row2) ?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<style type="text/css">
body {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13px;
  margin: 14px;
  background-color: #FFF;
}
.display {
  border: 2px solid #C9C9C9
}
.display tr td {
  border-bottom: 1px solid #C9C9C9;
  padding: 4px;
}
</style>
</head>
<body>
<div class="block-content">
  <table class="display">
    <tr>
      <td align="center" valign="top"><table width="100%">
       
        <tr data-id="<?php echo $row2->webspecials_id;?>">
           <td align="center" valign="middle"><img src="<?php echo $row2->vehicle_image;?>" alt="<?php echo $row2->vehicle_image;?>"></td>
          </tr>
          
		   <?php unset($row2);?>
          </table></td>
  </div>
</body>
</html>