<?php
  /**
   * Brands
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: brands.tpl.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo-grid">
  <div class="wojo secondary segment">
    <div class="wojo huge fitted inverted header">
      <div class="content"> <?php echo Lang::$word->HOME_SUB2;?>
        <p class="subheader"><?php echo Lang::$word->HOME_SUB5P;?></p>
      </div>
    </div>
  </div>
  <?php if(!$data->result):?>
  <?php echo Message::msgSingleInfo(Lang::$word->NOLISTFOUND);?>
  <?php else:?>
  <?php $dataset = Utility::groupToLoop($data->result, "make_name");?>
  <?php foreach($dataset as $name => $rows):?>
  <h3 class="wojo header"><img src="<?php echo UPLOADURL . 'brandico/' . strtolower(str_replace(" ", "-", $name)) . '.png';?>" class="wojo small avatar image"><?php echo $name;?></h3>
  <div class="columns gutters">
    <?php foreach($rows as $row):?>
    <div class="screen-33 tablet-50 phone-100">
      <div class="wojo tertiary segment">
        <?php if($row->sold):?>
        <span class="wojo negative right ribbon label"><?php echo strtoupper(Lang::$word->SOLD);?></span>
        <?php endif;?>
        <div class="header"><a href="<?php echo Url::doUrl(URL_ITEM, $row->idx . '/' . $row->slug);?>" class="white"><?php echo $row->year . ' ' . $row->nice_title;?></a>
          <p>
            <?php if($row->price_sale <> 0):?>
            <span class="wojo strike negative label"><?php echo Utility::formatMoney($row->price, true);?></span> <span class="wojo positive label"><?php echo Utility::formatMoney($row->price_sale, true);?></span>
            <?php else:?>
            <span class="wojo positive label"><?php echo Utility::formatMoney($row->price, true);?></span>
            <?php endif;?>
          </p>
        </div>
        <div class="content-center"><a href="<?php echo Url::doUrl(URL_ITEM, $row->idx . '/' . $row->slug);?>" class="wojo block shine"><img src="<?php echo UPLOADURL . 'listings/thumbs/' . $row->thumb;?>" alt=""></a></div>
        <div class="footer">
          <div class="content-center">
            <div class="wojo small divided horizontal list">
              <div class="item"><?php echo $row->condition_name;?></div>
              <div class="item"><?php echo $row->trans_name;?></div>
              <div class="item"><?php echo $row->category_name;?></div>
              <div class="item"><?php echo Utility::doDate("short_date", $row->created);?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
  <?php endforeach;?>
  <?php unset($rows);?>
  <?php unset($row);?>
  <?php endif;?>
</div>
