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
<?php if(!$row = $wSpecials->getWebspecialsPreview()) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $data = $wSpecials->getWebSpecialsAlert();?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title>Web Special &rsaquo;<?php echo $row->title_ws;?></title>
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
        <tr>
           <td align="center" valign="middle"><img src="<?php echo $row->vehicle_image;?>" alt=""></td>
          </tr>
          <tr>
          <td align="center" valign="middle"><img src="<?php echo UPLOADURL;?>showrooms/<?php echo ($row->logo) ? $row->logo : "blank.png";?>" alt=""></td>
          </tr>
          </table></td>
      <td width="100%" valign="top"><table width="100%">
           <thead>
        <tr>
	        <th align="left"></th>
	        <th align="left"><?php echo Lang::$word->WSALERT_FIELD;?></th>
	        <th><?php echo Lang::$word->WSALERT_CH_NEW;?></th>
	        <th><?php echo Lang::$word->WSALERT_CH_OLD;?></th>
	        <th><?php echo Lang::$word->WSALERT_CH_BY;?></th>
	        <th><?php echo Lang::$word->WSALERT_CH_ON;?></th>
        </tr>
      </thead>
         </tr>
       <?php if(!$data):?>
		<tr>
	    <td colspan="7"><?php echo Message::msgSingleAlert("No Web Specials Alerts at this Time.");?></td>
	    </tr>
	    <?php else:?>
	    <?php foreach ($data as $row2):?>
		 <?php if (Utility::dodate("short_date", $row2->modified) == Utility::dodate("short_date",Utility::today())):?>
			      <tr>
			       <td  align="left" style="background-color:yellow">(New Update)</td>
			        <td  align="left" style="background-color:yellow"><?php echo $row2->col;?></td>
			        <td  align="center" style="background-color:yellow"><?php echo $row2->changeFrom;?></td>
			        <td  align="center" style="background-color:yellow"><?php echo $row2->changeTo;?></td>
			        <td  align="center" style="background-color:yellow"><?php echo $row2->username;?></td>
			        <td  align="center" style="background-color:yellow"><?php echo Utility::dodate("short_date", $row2->modified);?></td>
			      </tr>
			       <?php else:?>
			       <tr>
			        <td  align="left"></td>
			        <td align="left"><?php echo $row2->col;?></td>
			        <td align="center"><?php echo $row2->changeFrom;?></td>
			        <td align="center"><?php echo $row2->changeTo;?></td>
			        <td align="center"><?php echo $row2->username;?></td>
			        <td align="center"><?php echo Utility::dodate("short_date", $row2->modified);?></td>
			      </tr>
			      <?php endif;?>	     
			      <?php endforeach;?>
			      <?php unset($row2);?>
			      <?php endif;?>
     			 <tr>   
    </table>
  </div>
</body>
</html>