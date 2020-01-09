<?php
  /**
   * Search
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: search.tpl.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo-grid">
  <div class="wojo secondary segment">
    <div class="wojo huge fitted inverted header">
      <div class="content"> <?php echo Lang::$word->SEARCHR;?>
        <p class="subheader"><?php echo str_replace("[NUMBER]", '<b>' . $pager->items_total . '</b>', Lang::$word->HOME_SUB11P);?></p>
      </div>
    </div>
  </div>
  <div class="wojo primary bg">
    <div class="wojo segment bottom attached form">
      <form id="wojo_form" name="wojo_form" method="get">
        <div class="three fields">
          <div class="field">
            <select name="make_id">
              <option value="">-- <?php echo Lang::$word->LST_MAKE;?> --</option>
              <?php echo Utility::loopOptions(Utility::jSonToArray($core->make_list), "id", "name");?>
            </select>
          </div>
          <div class="field">
            <select name="model_id">
              <option value="">-- <?php echo Lang::$word->LST_MODEL;?> --</option>
            </select>
          </div>
          <div class="field">
            <select name="transmission">
              <option value="">-- <?php echo Lang::$word->LST_TRANS;?> --</option>
              <?php echo Utility::loopOptions(Utility::jSonToArray($core->trans_list), "id", "name");?>
            </select>
          </div>
        </div>
        <div class="three fields">
          <div class="field">
            <select name="color">
              <option value="">-- <?php echo Lang::$word->LST_EXTC;?> --</option>
              <?php foreach(Utility::jSonToArray($core->color) as $color):?>
              <option value="<?php echo $color->color_e;?>"><?php echo $color->color_e;?></option>
              <?php endforeach;?>
            </select>
          </div>
          <div class="field">
            <select name="category">
              <option value="">-- <?php echo Lang::$word->LST_CAT;?> --</option>
              <?php echo Utility::loopOptions($content->getCategories(), "id", "name");?>
            </select>
          </div>
          <div class="field">
            <select name="condition">
              <option value="">-- <?php echo Lang::$word->LST_COND;?> --</option>
              <?php echo Utility::loopOptions(Utility::unserialToArray($core->cond_list_alt), "id", "name");?>
            </select>
          </div>
        </div>
        <div class="three fields">
          <div class="field">
            <select name="transmission">
              <option value="">-- <?php echo Lang::$word->LST_DOORS;?> --</option>
              <?php echo Utility::doRange(2, 6, 1);?>
            </select>
          </div>
          <div class="field">
            <select name="fuel">
              <option value="">-- <?php echo Lang::$word->LST_FUEL;?> --</option>
              <?php echo Utility::loopOptions(Utility::unserialToArray($core->fuel_list), "id", "name");?>
            </select>
          </div>
          <div class="field">
            <div class="two fields">
              <div class="field">
                <button type="button" class="wojo fluid button" onclick="location.href='<?php echo SITEURL . '/' . $core->_urlParts . '/';?>'"><?php echo Lang::$word->RESET;?></button>
              </div>
              <div class="field">
                <button type="submit" name="search" value="true" class="wojo negative fluid button"><?php echo Lang::$word->HOME_BTN;?></button>
              </div>
            </div>
          </div>
        </div>
        <div class="three fields">
          <div class="field">
            <?php if($core->minyear):?>
            <label><?php echo Lang::$word->PRICE;?></label>
            <input type="text" class="double range" name="price_range" value="<?php echo $core->minprice . ';' . $core->maxprice;?>">
            <?php endif;?>
          </div>
          <div class="field">
            <?php if($core->minyear):?>
            <label><?php echo Lang::$word->_YEAR;?></label>
            <input type="text" class="double range" name="year_range" value="<?php echo $core->minyear . ';' . $core->maxyear;?>">
            <?php endif;?>
          </div>
          <div class="field">
            <?php if($core->maxkm):?>
            <label><?php echo $core->odometer == "km" ? Lang::$word->KM : Lang::$word->MI;?></label>
            <input type="text" class="double range" name="km_range" value="<?php echo $core->minkm . ';' . $core->maxkm;?>">
            <?php endif;?>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="wojo top bottom attached segment">
    <div class="wojo divided horizontal link list">
      <div class="disabled item"> <?php echo Lang::$word->SORTING_O;?> </div>
      <a href="<?php echo Url::doUrl(URL_SEARCH);?>" class="item<?php echo Url::setActive("order", false);?>"> <?php echo Lang::$word->DEFAULT;?> </a> <a href="<?php echo Url::buildUrl("order", "year/DESC", "sorting");?>" class="item<?php echo Url::setActive("order", "year");?>"><?php echo Lang::$word->_YEAR;?></a> <a href="<?php echo Url::buildUrl("order", "price/DESC", "sorting");?>" class="item<?php echo Url::setActive("order", "price");?>"><?php echo Lang::$word->PRICE;?></a> <a href="<?php echo Url::buildUrl("order", "make/DESC", "sorting");?>" class="item<?php echo Url::setActive("order", "make");?>"><?php echo Lang::$word->LST_MAKE;?></a> <a href="<?php echo Url::buildUrl("order", "model/DESC", "sorting");?>" class="item<?php echo Url::setActive("order", "model");?>"><?php echo Lang::$word->LST_MODEL;?></a>
      <div class="item" data-content="ASC/DESC"><a href="<?php echo Url::sortItems(Url::doUrl(URL_SEARCH), "order");?>"><i class="icon unfold more link"></i></a> </div>
    </div>
    <div class="push-right"> <a <?php if(isset($_GET['list'])) echo 'href="' . Url::buildUrl("list", null, "view") . '"';?> class="wojo small icon button<?php if(!isset($_GET['list'])) echo ' secondary active';?>"><i class="icon grid"></i></a> <a <?php if(!isset($_GET['list'])) echo 'href="' . Url::buildUrl("list", "true", "view") . '"';?> class="wojo small icon button<?php if(isset($_GET['list'])) echo ' secondary active';?>"><i class="icon reorder"></i></a> </div>
  </div>
  <?php if(isset($_GET['list']) and $_GET['list'] == "true"):?>
  <?php include (THEME . "/list_view.tpl.php");?>
  <?php else:?>
  <?php include (THEME . "/grid_view.tpl.php");?>
  <?php endif;?>
  <div class="wojo double space divider"></div>
  <?php $result = $items->getFooterBits();?>
  <?php if($result):?>
  <div class="wojo secondary segment">
    <h4 class="wojo inverted header"><?php echo Lang::$word->HOME_SUB7P;?></h4>
    <div class="wojo double space divider"></div>
    <div class="columns half-gutters">
      <?php $categories = Utility::groupToLoop($result, "category_name");?>
      <?php foreach($categories as $category => $i):?>
      <div class="screen-25 tablet-33 phone-50"><a href="<?php echo Url::doUrl(URL_BODY, Url::doSeo($category));?>" class="wojo medium text caps"><img src="<?php echo UPLOADURL . 'catico/' . str_replace(" ", "-", strtolower($category));?>.png" class="wojo overflown image" alt=""><?php echo $category;?> <span class="wojo secondary text"><?php echo count($i);?></span></a></div>
      <?php endforeach;?>
    </div>
    <?php unset($i);?>
  </div>
  <?php endif;?>
</div>