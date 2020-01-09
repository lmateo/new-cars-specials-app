<?php
  /**
   * Sidebar Filter
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: sidebar_filter.tpl.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo segment">
  <div class="wojo form">
    <?php if(isset($_GET['filter'])):?>
    <div class="field">
      <?php if(isset($_GET['make_name']) and $pager->items_total):?>
      <a href="<?php echo Url::buildUrl("make_name", null);?>" class="wojo negative icon label"><?php echo Url::doSeo($_GET['make_name']);?> <i class="close icon"></i></a>
      <?php endif;?>
      <?php if(isset($_GET['color']) and $pager->items_total):?>
      <a href="<?php echo Url::buildUrl("color", null);?>" class="wojo negative icon label"><?php echo Validator::sanitize($_GET['color'], "db");?> <i class="close icon"></i></a>
      <?php endif;?>
      <?php if(isset($_GET['condition']) and $pager->items_total):?>
      <a href="<?php echo Url::buildUrl("condition", null);?>" class="wojo negative icon label"><?php echo Validator::sanitize($_GET['condition'], "db");?> <i class="close icon"></i></a>
      <?php endif;?>
      <?php if(isset($_GET['body']) and $pager->items_total):?>
      <a href="<?php echo Url::buildUrl("body", null);?>" class="wojo negative icon label"><?php echo Url::doSeo($_GET['body']);?> <i class="close icon"></i></a>
      <?php endif;?>
      <?php if(isset($_GET['sale'])):?>
      <a href="<?php echo Url::buildUrl("sale", null);?>" class="wojo negative icon label"><?php echo Lang::$word->SPECIAL;?> <i class="close icon"></i></a>
      <?php endif;?>
    </div>
    <?php endif;?>
    <div class="field">
      <label><?php echo Lang::$word->LST_COND;?></label>
      <div class="wojo divided list"> <a rel="nofollow" href="<?php echo Url::buildUrl("sale", "true");?>" class="inverted item<?php echo Url::setActive("sale", "true");?>">
        <div class="content"> <?php echo Lang::$word->HOME_SPCL;?> </div>
        </a>
        <?php if($core->cond_list):?>
        <?php foreach(Utility::jSonToArray($core->cond_list) as $cond):?>
        <a rel="nofollow" href="<?php echo Url::buildUrl("condition", $cond->condition_name);?>" class="inverted item<?php echo Url::setActive("condition", $cond->condition_name);?>">
        <div class="right floated content">
          <div class="wojo secondary rounded label"><?php echo $cond->total;?></div>
        </div>
        <div class="content"> <?php echo $cond->condition_name;?> </div>
        </a>
        <?php endforeach;?>
        <?php endif;?>
      </div>
    </div>
    <?php if($core->makes):?>
    <div class="field">
      <label><?php echo Lang::$word->LST_MAKE;?></label>
      <div class="wojo divided list">
        <?php foreach(Utility::jSonToArray($core->makes) as $makes):?>
        <a rel="nofollow" href="<?php echo Url::buildUrl("make_name", Url::doSeo($makes->make_name));?>" class="inverted item<?php echo Url::setActive("make_name", Url::doSeo($makes->make_name));?>">
        <div class="right floated content">
          <div class="wojo secondary rounded label"><?php echo $makes->total;?></div>
        </div>
        <div class="content"> <?php echo $makes->make_name;?> </div>
        </a>
        <?php endforeach;?>
      </div>
    </div>
    <?php endif;?>
    <?php if($core->color):?>
    <div class="field">
      <label><?php echo Lang::$word->LST_EXTC;?></label>
      <div class="wojo divided list">
        <?php foreach(Utility::jSonToArray($core->color) as $color):?>
        <a rel="nofollow" href="<?php echo Url::buildUrl("color", $color->color_e);?>" class="inverted item<?php echo Url::setActive("color", $color->color_e);?>">
        <div class="right floated content">
          <div class="wojo secondary rounded label"><?php echo $color->total;?></div>
        </div>
        <div class="content"> <?php echo $color->color_e;?> </div>
        </a>
        <?php endforeach;?>
      </div>
    </div>
    <?php endif;?>
    <?php if($core->category_list):?>
    <div class="field">
      <label><?php echo Lang::$word->LST_CAT;?></label>
      <div class="wojo middle aligned divided list">
        <?php foreach(Utility::jSonToArray($core->category_list) as $category):?>
        <a rel="nofollow" href="<?php echo Url::buildUrl("body", Url::doSeo($category->category_name));?>" class="inverted item<?php echo Url::setActive("body", Url::doSeo($category->category_name));?>">
        <div class="right floated content">
          <div class="wojo secondary rounded label"><?php echo $category->total;?></div>
        </div>
        <div class="content"> <?php echo $category->category_name;?> </div>
        </a>
        <?php endforeach;?>
      </div>
    </div>
    <?php endif;?>
    <form id="wojo_form" name="wojo_form" method="get">
      <div class="wojo small double fitted divider"></div>
      <?php if($core->minyear):?>
      <div class="field">
        <label><?php echo Lang::$word->PRICE;?></label>
        <input type="text" class="double range" name="price_range" value="<?php echo $core->minprice . ';' . $core->maxprice;?>">
      </div>
      <?php endif;?>
      <?php if($core->minyear):?>
      <div class="field">
        <label><?php echo Lang::$word->_YEAR;?></label>
        <input type="text" class="double range" name="year_range" value="<?php echo $core->minyear . ';' . $core->maxyear;?>">
      </div>
      <?php endif;?>
      <?php if($core->maxkm):?>
      <div class="field">
        <label><?php echo $core->odometer == "km" ? Lang::$word->KM : Lang::$word->MI;?></label>
        <input type="text" class="double range" name="km_range" value="<?php echo $core->minkm . ';' . $core->maxkm;?>">
      </div>
      <?php endif;?>
      <div class="wojo small fitted divider"></div>
      <div class="field content-center">
        <button class="wojo mini primary rounded button" type="submit"><?php echo Lang::$word->FIND;?></button>
      </div>
      <input type="hidden" name="range_search" value="1">
    </form>
  </div>
</div>