<?php
  /**
   * Reviews
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: reviews.tpl.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php $reviews = $content->getReviews(true);?>
<?php if($reviews):?>
<div class="wojo secondary segment" id="rewievs">
  <h3 class="wojo inverted header content-center"><?php echo Lang::$word->HOME_SUB3;?></h3>
  <div class="wojo tripple space divider"></div>
  <div class="columns">
    <div class="screen-10 phone-hide">&nbsp;</div>
    <div class="screen-80 phone-100">
      <div id="rcarousel" class="wojo carousel" data-slick='{"dots": false,"asNavFor": "#pcarousel","arrows":false,"centerMode": true,"mobileFirst":true,"responsive":[{"breakpoint":1024,"settings":{"slidesToShow": 3,"slidesToScroll": 1}},{ "breakpoint": 769, "settings":{"slidesToShow": 2,"slidesToScroll": 1}},{"breakpoint": 480,"settings":{ "slidesToShow": 1,"slidesToScroll": 1}}]}'>
        <?php foreach($reviews as $wrow):?>
        <div class="content-center"> <img src="<?php echo UPLOADURL;?>avatars/<?php echo ($wrow->avatar) ? $wrow->avatar : "blank.png";?>" alt="" class="wojo normal image circular">
          <div class="wojo space divider"></div>
          <h4><?php echo $wrow->name;?></h4>
          <?php if($wrow->twitter):?>
          <div class="wojo space divider"></div>
          <p class="wojo text dimmed"><i class="icon twitter"></i> <a target="_blank" href="http://twitter.com/<?php echo $wrow->twitter;?>" class="inverted"><?php echo $wrow->twitter;?></a></p>
          <?php endif;?>
        </div>
        <?php endforeach;?>
        <?php unset($wrow);?>
      </div>
      <div class="wojo space divider"></div>
      <div id="pcarousel" class="wojo carousel" data-slick='{"dots": true,"arrows":false,"fade":true,"asNavFor": "#rcarousel", "slidesToShow": 1,"slidesToScroll": 1}'>
        <?php foreach($reviews as $wrow):?>
        <div class="content-center">
          <blockquote>
            <p class="wojo secondary large text"><?php echo $wrow->content;?></p>
          </blockquote>
        </div>
        <?php endforeach;?>
        <?php unset($wrow);?>
      </div>
      <div class="wojo space divider"></div>
    </div>
    <div class="screen-10 phone-hide">&nbsp;</div>
  </div>
</div>
<?php endif;?>