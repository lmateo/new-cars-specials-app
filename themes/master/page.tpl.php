<?php
  /**
   * Content Page
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: page.tpl.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo-grid">
  <div class="wojo primary top attached segment">
    <div class="wojo huge header">
      <div class="content"> <?php echo $data->result->title;?> </div>
    </div>
  </div>
  <?php if($data->result->contact):?>
  <?php include_once("contact.tpl.php");?>
  <?php elseif($data->result->faq):?>
  <?php include_once("faq.tpl.php");?>
  <?php else:?>
  <div class="wojo tertiary top bottom attached segment">
    <div class="padding"> <?php echo Validator::cleanOut($data->result->body);?> </div>
  </div>
  <?php endif;?>
  <?php $result = $items->getFooterBits();?>
  <?php if($result):?>
  <div class="wojo top bottom attached segment">
    <div class="double-padding">
      <h4><?php echo Lang::$word->HOME_SUB2P;?></h4>
      <div class="wojo double space divider"></div>
      <div class="columns half-gutters">
        <?php $makes = Utility::groupToLoop($result, "make_name");?>
        <?php foreach($makes as $make => $i):?>
        <div class="screen-25 tablet-33 phone-50"><a href="<?php echo Url::doUrl(URL_BRAND, Url::doSeo($make));?>"><img src="<?php echo UPLOADURL . 'brandico/' . str_replace(" ", "-", strtolower($make));?>.png" class="wojo avatar image" alt=""><?php echo $make;?> <span class="wojo bold negative text"><?php echo count($i);?></span></a></div>
        <?php endforeach;?>
      </div>
      <?php unset($i);?>
    </div>
  </div>
  <?php endif;?>
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
