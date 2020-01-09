<?php
  /**
   * My Listings
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: mylistings.tpl.php, v1.00 2015-08-05 10:16:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if (!$auth->is_User())
      Url::redirect(Url::doUrl(URL_LOGIN));
	  
  $dataitems = $items->getUserItems($auth->uid);
?>
<div class="wojo-grid">
  <div class="wojo secondary segment">
    <div class="wojo huge fitted inverted header">
      <div class="content"> <?php echo Lang::$word->HOME_SUB12;?>
        <p class="subheader"><?php echo Lang::$word->HOME_SUB12P;?></p>
      </div>
    </div>
    <div id="userMenu">
      <div class="wojo labeled right icon fluid dropdown button"> <i class="angle down icon"></i> <span class="text"><?php echo Lang::$word->LISTINGS;?></span>
        <div class="menu"> 
        <a href="<?php echo Url::doUrl(URL_ACCOUNT);?>" class="item"><i class="icon note"></i><?php echo Lang::$word->PACKAGES;?></a> 
        <a class="item"><i class="icon car"></i><?php echo Lang::$word->LISTINGS;?></a> 
        <a href="<?php echo Url::doUrl(URL_ADDLISTING);?>" class="item"><i class="icon plus"></i><?php echo Lang::$word->LST_ADD;?></a> 
        <a href="<?php echo Url::doUrl(URL_MYSETTINGS);?>" class="item"><i class="icon cog"></i><?php echo Lang::$word->SETTINGS;?></a> 
        <a href="<?php echo Url::doUrl(URL_MYREVIEWS);?>" class="item"><i class="icon badge"></i><?php echo Lang::$word->SRW_ADD;?></a> </div>
      </div>
    </div>
  </div>
  <div class="wojo secondary bg">
    <div class="padding wojo tab item" id="listings">
      <p class="wojo basic message"><?php echo Lang::$word->HOME_SUB14P;?></p>
      <?php if(!$dataitems):?>
      <?php echo Message::msgSingleInfo(Lang::$word->HOME_SUB15P);?>
      <?php else:?>
      <div class="columns gutters">
        <?php foreach($dataitems as $row):?>
        <div class="screen-33 tablet-50 phone-100 row">
          <div class="wojo primary segment">
            <?php if($row->sold):?>
            <span class="wojo negative right ribbon label"><?php echo strtoupper(Lang::$word->SOLD);?></span>
            <?php endif;?>
            <div class="content-center"><a href="<?php echo UPLOADURL . 'listings/' . $row->thumb?>" data-title="<?php echo $row->title;?>" data-lightbox-gallery="true" data-lightbox="true"><img src="<?php echo UPLOADURL . 'listings/thumbs/' . $row->thumb;?>" alt=""></a></div>
            <div class="content"><a href="<?php echo Url::doUrl(URL_ITEM, $row->idx . '/' . $row->slug);?>"><?php echo Validator::truncate($row->title, 50);?></a>
              <div class="wojo fitted divider"></div>
              <div class="content-center">
                <div class="wojo label"><?php echo Lang::$word->_YEAR;?> <span class="detail"><?php echo $row->year;?></span></div>
                <div class="wojo label"><?php echo Lang::$word->PRICE;?> <span class="detail"><?php echo Utility::formatMoney($row->price);?></span></div>
                <div class="wojo label"><?php echo Lang::$word->ACTIVE;?> <span class="detail"><?php echo $row->status ? '<i class="icon small positive check"></i>' : '<i class="icon negative small circle ban"></i>' ;?></span></div>
              </div>
            </div>
            <div class="footer actions content-center clearfix">
              <?php if($row->sold):?>
              <span>-/-</span>
              <?php else:?>
              <a class="msold" data-set='{"title": "<?php echo Lang::$word->SOLD;?>", "parent": ".segment", "option": "soldItem", "id": <?php echo $row->id;?>, "name": "<?php echo $row->title;?>"}'><i class="icon positive long arrow left"></i> <?php echo Lang::$word->SOLD_M;?></a>
              <?php endif;?>
              <a class="delete" data-set='{"title": "<?php echo Lang::$word->LST_DELIST;?>", "parent": ".row", "option": "deleteItem", "id": <?php echo $row->id;?>, "name": "<?php echo $row->title;?>"}'><?php echo Lang::$word->LST_DELIST;?> <i class="icon negative long arrow right"></i></a> </div>
          </div>
        </div>
        <?php endforeach;?>
        <?php unset($row);?>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>