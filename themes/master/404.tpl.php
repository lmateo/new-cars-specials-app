<?php
  /**
   * 404
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: 404.tpl.php, v1.00 2015-08-05 10:16:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo-grid">
<div class="wojo tripple space divider"></div>
  <div class="wojo tabular segment two cells">
    <div class="wojo cell">
      <div class="wojo secondary form segment eq">
        <p class="wojo gigant secondary text content-center"> 404</p>
        <p class="wojo large secondary text content-center"><?php echo Lang::$word->ER_404;?></p>
        <div class="wojo double space divider"></div>
        <form id="admin_form" name="admin_form" a action="<?php echo Url::doUrl(URL_SEARCH);?>" method="get">
          <div class="field">
            <div class="wojo fluid action input">
              <input type="text" name="keyword" placeholder="<?php echo Lang::$word->SEARCH;?>">
              <button type="submit" class="wojo negative button"><?php echo Lang::$word->SEARCH;?></button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="wojo cell">
      <div class="wojo quaternary form inverted segment eq">
        <p class="wojo"><?php echo Lang::$word->ER_404_1;?></p>
        <p class="wojo"><?php echo Lang::$word->ER_404_2;?></p>
        <div class="wojo double space divider"></div>
        <ul class="wojo bulleted list">
          <li><?php echo Lang::$word->ER_404_L1;?></li>
          <li><?php echo Lang::$word->ER_404_L2;?></li>
          <li><?php echo Lang::$word->ER_404_L3;?></li>
          <li><?php echo str_replace("[NAME]", '<a href="' . SITEURL . '/">' . $core->company . '</a>', Lang::$word->ER_404_L4);?></li>
        </ul>
        <div class="wojo tripple space divider"></div>
        <?php if($core->fb or $core->twitter):?>
        <?php if($core->fb):?>
        &nbsp;<a href="http://twitter.com/<?php echo $core->fb;?>"><i class="icon rounded facebook link"></i></a>
        <?php endif;?>
        <?php if($core->twitter):?>
        &nbsp;<a href="http://facebook.com/<?php echo $core->twitter;?>"><i class="icon rounded twitter link"></i></a>
        <?php endif;?>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>