<?php
  /**
   * Listings
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: listings.tpl.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo-grid">
  <div class="wojo secondary segment">
    <div class="wojo huge fitted inverted header">
      <div class="content"> <?php echo Lang::$word->HOME_SUB6;?>
        <p class="subheader"><?php echo str_replace("[N]", '<span class="wojo black label">' . $pager->items_total . '</span>', Lang::$word->HOME_SUB6P);?></p>
      </div>
    </div>
  </div>
  <div class="wojo bottom attached segment">
    <div class="wojo divided horizontal link list">
      <div class="disabled item"> <?php echo Lang::$word->SORTING_O;?> </div>
      <a href="<?php echo Url::doUrl(URL_LISTINGS);?>" class="item<?php echo Url::setActive("order", false);?>"> <?php echo Lang::$word->DEFAULT;?> </a> <a href="<?php echo Url::buildUrl("order", "year/DESC", "sorting");?>" class="item<?php echo Url::setActive("order", "year");?>"><?php echo Lang::$word->_YEAR;?></a> <a href="<?php echo Url::buildUrl("order", "price/DESC", "sorting");?>" class="item<?php echo Url::setActive("order", "price");?>"><?php echo Lang::$word->PRICE;?></a> <a href="<?php echo Url::buildUrl("order", "make/DESC", "sorting");?>" class="item<?php echo Url::setActive("order", "make");?>"><?php echo Lang::$word->LST_MAKE;?></a> <a href="<?php echo Url::buildUrl("order", "model/DESC", "sorting");?>" class="item<?php echo Url::setActive("order", "model");?>"><?php echo Lang::$word->LST_MODEL;?></a>
      <div class="item" data-content="ASC/DESC"><a href="<?php echo Url::sortItems(Url::doUrl(URL_LISTINGS), "order");?>"><i class="icon unfold more link"></i></a> </div>
    </div>
    <div class="push-right"> <a <?php if(isset($_GET['list'])) echo 'href="' . Url::buildUrl("list", null, "view") . '"';?> class="wojo small icon button<?php if(!isset($_GET['list'])) echo ' secondary active';?>"><i class="icon grid"></i></a> <a <?php if(!isset($_GET['list'])) echo 'href="' . Url::buildUrl("list", "true", "view") . '"';?> class="wojo small icon button<?php if(isset($_GET['list'])) echo ' secondary active';?>"><i class="icon reorder"></i></a> </div>
  </div>
  <div class="columns gutters">
    <div class="screen-30 tablet-40 phone-100">
      <?php include (THEME . "/sidebar_filter.tpl.php");?>
    </div>
    <div class="screen-70 tablet-60 phone-100">
      <?php if(isset($_GET['list']) and $_GET['list'] == "true"):?>
      <?php include (THEME . "/list_view.tpl.php");?>
      <?php else:?>
      <?php include (THEME . "/grid_view.tpl.php");?>
      <?php endif;?>
    </div>
  </div>
</div>