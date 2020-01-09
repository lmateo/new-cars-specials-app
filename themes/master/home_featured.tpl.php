<?php
  /**
   * Featured
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: featured.tpl.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php $catdata = $content->getCategoryCounters();?>
<?php $featdata = $items->getFeatured();?>
<?php if($catdata):?>
<div class="wojo white tripple space divider"></div>
<div class="wojo fitted segment">
  <div class="wojo space divider"></div>
  <h3 class="wojo header content-center top-space"><?php echo Lang::$word->HOME_SUB1;?></h3>
  <div class="content-center">
    <div class="wojo horizontal relaxed list" id="catnav">
      <?php foreach($catdata as $crow):?>
      <a class="item wojo bold text inverted" data-id="<?php echo $crow->id;?>"><?php echo $crow->name;?>
      <div class="center floated content-center"><span class="wojo circular black label"><?php echo $crow->listings;?></span></div>
      </a>
      <?php endforeach;?>
    </div>
  </div>
  <div class="wojo space divider"></div>
</div>
<?php endif;?>
<?php if($featdata):?>
<div id="featured" class="wojo fitted segment loading">
  <div class="wojo space divider"></div>
  <div id="fcarousel" class="wojo carousel" data-slick='{"dots": false,"arrows":true,"mobileFirst":true,"lazyLoad": "ondemand","responsive":[{"breakpoint":1024,"settings":{"slidesToShow": 3,"slidesToScroll": 2}},{ "breakpoint": 769, "settings":{"slidesToShow": 2,"slidesToScroll": 2}},{"breakpoint": 480,"settings":{ "slidesToShow": 1,"slidesToScroll": 1}}]}'>
    <?php foreach($featdata as $frow):?>
    <div class="wojo segment divided content-center"> <a href="<?php echo Url::doUrl(URL_ITEM, $frow->idx . '/' . $frow->slug);?>"><img src="<?php echo UPLOADURL . 'listings/' . $frow->thumb;?>" data-lazy="<?php echo UPLOADURL . 'listings/' . $frow->thumb;?>" alt=""></a>
      <h4 class="wojo header"><a href="<?php echo Url::doUrl(URL_ITEM, $frow->idx . '/' . $frow->slug);?>" class="inverted"><?php echo $frow->nice_title;?></a></h4>
      <p class="wojo negative bold text"><?php echo Utility::FormatMoney($frow->price);?></p>
    </div>
    <?php endforeach;?>
    <?php unset($frow);?>
  </div>
  <div class="wojo tripple space divider"></div>
</div>
<div class="content-center"> <a id="sbutton" href="<?php echo Url::doUrl(URL_LISTINGS);?>" class="wojo negative rounded button"><i class="icon circle chevron right"></i><?php echo Lang::$word->HOME_BTN1;?></a> </div>
<?php endif;?>