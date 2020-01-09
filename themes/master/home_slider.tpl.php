<?php
  /**
   * Home Slider
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: home_slider.tpl.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php if($core->show_slider):?>
<?php $sliderdata = $content->getSlider();?>
<?php if($sliderdata):?>
<div id="slider">
  <div class="wojo carousel" data-slick='{"dots":true, "fade": true,"autoplay": true, "autoplaySpeed": 5000, "appendDots":"#sdots", "arrows":false, "lazyLoad":"ondemand", "slidesToShow":1}'>
    <?php foreach ($sliderdata as $srow):?>
    <section>
      <aside>
        <div class="inner">
          <h2><?php echo $srow->caption;?></h2>
          <div class="content"><?php echo $srow->body;?></div>
        </div>
      </aside>
      <article><img src="<?php echo UPLOADURL . 'slider/' . $srow->thumb;?>" alt=""></article>
    </section>
    <?php endforeach;?>
  </div>
  <div id="sdots"></div>
</div>
<?php endif;?>
<?php endif;?>