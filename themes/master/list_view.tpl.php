<?php
  /**
   * List View
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: list_view.tpl.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php if(!$data->result):?>
<?php echo Message::msgSingleInfo(Lang::$word->NOLISTFOUND);?>
<?php else:?>
<?php foreach($data->result as $row):?>
<div class="wojo segment<?php echo $row->featured ? " featured" : null;?>">
  <div class="columns horizontal-gutters">
    <div class="screen-35 tablet-100 phone-100">
      <?php if($row->sold):?>
      <span class="wojo negative ribbon label"><?php echo strtoupper(Lang::$word->SOLD);?></span>
      <?php endif;?>
      <div class="content-center"><a href="<?php echo Url::doUrl(URL_ITEM, $row->idx . '/' . $row->slug);?>" class="wojo block shine"><img src="<?php echo UPLOADURL . 'listings/thumbs/' . $row->thumb;?>" alt=""></a></div>
    </div>
    <div class="screen-65 tablet-100 phone-100">
      <div class="wojo header"><a href="<?php echo Url::doUrl(URL_ITEM, $row->idx . '/' . $row->slug);?>" class="wojo bold inverted text"><?php echo $row->year . ' ' . $row->nice_title;?></a>
        <?php if($row->price_sale <> 0):?>
        <span class="wojo strike negative label"><?php echo Utility::formatMoney($row->price, true);?></span> <span class="wojo positive label"><?php echo Utility::formatMoney($row->price_sale, true);?></span>
        <?php else:?>
        <span class="wojo positive label"><?php echo Utility::formatMoney($row->price, true);?></span>
        <?php endif;?>
      </div>
      <div class="wojo medium dimmed text"><?php echo Validator::truncate($row->body, 150);?></div>
      <div class="wojo space divider"></div>
      <div class="content-left">
        <div class="wojo small divided horizontal list">
          <div class="item"><?php echo $row->condition_name;?></div>
          <div class="item"><?php echo $row->trans_name;?></div>
          <div class="item"><?php echo $row->category_name;?></div>
          <div class="item"><?php echo $row->fuel_name;?></div>
          <div class="item"><?php echo Utility::formatNumber($row->mileage);?> <?php echo $core->odometer;?></div>
          <div class="item"><?php echo Utility::doDate("short_date", $row->created);?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endforeach;?>
<?php unset($row);?>
<div class="wojo space divider"></div>
<div class="wojo tabular segment">
  <div class="wojo cell"> <?php echo $pager->display_pages();?></div>
  <div class="wojo cell right"> <?php echo Lang::$word->TOTAL.': '.$pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$pager->current_page.' '.Lang::$word->OF.' '.$pager->num_pages;?> </div>
</div>
<div class="wojo space divider"></div>
<?php endif;?>