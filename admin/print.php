<?php
  /**
   * Print Listing
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
<?php if(!$row = $items->getListingPreview()) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $featuredata = $content->getFeaturesById($row->features);?>
<?php $locdata = unserialize($row->location_name);?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title>Listing &rsaquo;<?php echo $row->title;?></title>
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
            <td align="center" valign="middle"><img src="<?php echo UPLOADURL . 'listings/thumbs/' . $row->thumb;?>" alt=""></td>
          </tr>
          <tr>
            <td style="border:0"><div style="clear:both">
                <?php if($row->gallery):?>
                <?php $gallerydata = unserialize($row->gallery);?>
                <?php foreach ($gallerydata as $grow):?>
                <div style="float:left; max-width:100px;margin:2px;"> <img src="<?php echo UPLOADURL.'/listings/pics' . Filter::$id . '/thumbs/' . $grow->photo;?>" alt="" style="width:100px;height:100px"> </div>
                <?php endforeach;?>
                <?php endif;?>
              </div></td>
          </tr>
        </table></td>
      <td width="100%" valign="top"><table width="100%">
          <thead>
            <tr>
              <td colspan="2"><strong><?php echo $row->year . ' ' .$row->name;?></strong></td>
            </tr>
          </thead>
          <tr>
            <td style="width:50%"><?php echo Lang::$word->LST_STOCK;?></td>
            <td><?php echo Validator::has($row->stock_id);?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_VIN;?></td>
            <td><?php echo Validator::has($row->vin);?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_COND;?></td>
            <td><?php echo $row->condition_name;?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_ODM;?></td>
            <td><?php echo Validator::has($row->mileage);?> <?php echo $core->odometer;?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_PRICE;?></td>
            <td><?php echo ($row->price_sale) ? '<span class="sale">' . Utility::formatMoney($row->price) . '</span>' : Utility::formatMoney($row->price);?></td>
          </tr>
          <?php if($row->price_sale):?>
          <tr>
            <td><?php echo Lang::$word->LST_DPRICE_S;?></td>
            <td><?php echo Validator::has(Utility::formatMoney($row->price_sale));?></td>
          </tr>
          <?php endif;?>
          <tr>
            <td><?php echo Lang::$word->LST_ROOM;?></td>
            <td><?php echo $locdata->name;?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->EMAIL;?></td>
            <td><?php echo $locdata->email;?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->CF_PHONE;?></td>
            <td><?php echo Validator::has($locdata->phone);?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_INTC;?></td>
            <td><?php echo Validator::has($row->color_i);?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_EXTC;?></td>
            <td><?php echo Validator::has($row->color_e);?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_DOORS;?></td>
            <td><?php echo Validator::has($row->doors);?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_ENGINE;?></td>
            <td><?php echo Validator::has($row->engine);?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_TRANS;?></td>
            <td><?php echo $row->trans_name;?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_FUEL;?>:</td>
            <td><?php echo $row->fuel_name;?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_TRAIN;?>:</td>
            <td><?php echo Validator::has($row->drive_train);?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_SPEED;?>:</td>
            <td><?php echo Validator::has($row->top_speed);?> <?php echo ($core->odometer == "km") ? 'km/h' : 'mph';?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_POWER;?></td>
            <td><?php echo Validator::has($row->horse_power);?></td>
          </tr>
          <tr>
            <td><?php echo Lang::$word->LST_TORQUE;?></td>
            <td><?php echo Validator::has($row->torque);?></td>
          </tr>
          <tr>
            <td style="border:0"><?php echo Lang::$word->LST_TOWING;?></td>
            <td style="border:0"><?php echo Validator::has($row->towing_capacity);?></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td colspan="2"><?php echo Validator::cleanOut($row->body);?></td>
    </tr>
    <tr>
      <td colspan="2" style="border:0"><?php if($featuredata):?>
        <?php 
		    $result = '';
			foreach($featuredata as $frow) {
				if(strlen($result) > 0) {
					$result .= ", ";
				}
				$result .= "&bull; " . $frow->name;
			}
			echo $result;
			unset($frow)
			?>
        <?php endif;?></td>
    </tr>
  </table>
</div>
</body>
</html>