<?php
  /**
   * Home Search
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: home_search.tpl.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo form">
  <form method="get" id="sform" action="<?php echo SITEURL . '/' . $core->_urlParts . '/';?>" name="sform">
    <div class="three fields">
      <div class="field">
        <select name="make_id">
          <?php if($core->_url[0] == URL_BRAND):?>
          <option value="">-- <?php echo $data->result->name;?> --</option>
          <?php else:?>
          <option value="">-- <?php echo Lang::$word->LST_MAKE;?> --</option>
          <?php echo Utility::loopOptions($content->getMakes(false), "id", "name", Validator::get('make_id'));?>
          <?php endif;?>
        </select>
      </div>
      <div class="field">
        <select name="model_id">
          <option value="">-- <?php echo Lang::$word->LST_MODEL;?> --</option>
          <?php if($core->_url[0] == URL_BRAND):?>
          <?php echo Utility::loopOptions($content->getModelList($data->result->id), "id", "name", Validator::get('model_id'));?>
          <?php endif;?>
        </select>
      </div>
      <div class="field">
        <select name="transmission">
          <option value="">-- <?php echo Lang::$word->LST_TRANS;?> --</option>
          <?php echo Utility::loopOptions(Utility::jSonToArray($core->trans_list), "id", "name", Validator::get('transmission'));?>
        </select>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <select name="price">
          <option value="">-- <?php echo Lang::$word->LST_PRICE;?> --</option>
          <option value="10">&lt; 5000</option>
          <option value="20">5 000 - 10 000</option>
          <option value="30">10 000 - 20 000</option>
          <option value="40">20 000 - 30 000</option>
          <option value="50">30 000 - 50 000</option>
          <option value="60">50 000 - 75 000</option>
          <option value="70">75 000 - 100 000</option>
          <option value="80">100 000 +</option>
        </select>
      </div>
      <div class="field">
        <select name="year">
          <option value="">-- <?php echo Lang::$word->LST_YEAR;?> --</option>
          <?php echo Utility::doRange($core->minyear, $core->maxyear, 1, Validator::get('year'));?>
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
  </form>
</div>
