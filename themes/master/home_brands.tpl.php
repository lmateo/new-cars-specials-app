<?php
  /**
   * Brands
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: home_brands.tpl.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php $brands = $items->getBrands();?>
<?php if($brands):?>
<div id="home_brands">
<div class="content-center">
  <div class="wojo tripple space divider"></div>
  <h3 class="wojo header"><?php echo Lang::$word->HOME_SUB2;?></h3>
  <p><?php echo Lang::$word->HOME_SUB2P;?></p>
  <div class="wojo tripple space divider"></div>
</div>
<div class="columns">
  <div class="screen-5 phone-hide">&nbsp;</div>
  <div class="screen-90 phone-100">
    <ul class="wojo block grid large-10 medium-5 small-2">
      <?php foreach($brands as $brow):?>
      <li>
        <div class="content"> <a href="<?php echo Url::doUrl(URL_BRAND, Url::doSeo($brow->name));?>"><img src="<?php echo UPLOADURL . 'brandico/' . strtolower(str_replace(" ", "-", $brow->name)) . '.png';?>" alt="<?php echo $brow->name;?>" class="hasfilter"></a> </div>
      </li>
      <?php endforeach;?>
      <?php unset($brow);?>
    </ul>
  </div>
  <div class="screen-5 phone-hide">&nbsp;</div>
</div>
<div class="wojo tripple space divider"></div>
<div class="content-center"> <a href="<?php echo Url::doUrl(URL_BRANDS);?>" class="wojo rounded secondary button"><i class="icon grid"></i><?php echo Lang::$word->HOME_BTN2;?></a> </div>
<div class="wojo tripple space divider"></div>
</div>
<?php endif;?>