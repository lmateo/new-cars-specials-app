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
<div id="home_search">
  <h3 class="wojo header reversed content-center"><?php echo Lang::$word->HOME_SUB;?></h3>
  <div class="columns">
    <div class="screen-5 tablet-5 phone-hide">&nbsp;</div>
    <div class="screen-90 tablet-90 phone-100">
      <div class="wojo form">
        <form method="get" id="sform" action="<?php echo Url::doUrl(URL_SEARCH);?>" name="sform">
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
              <select name="price">
                <option value="">-- <?php echo Lang::$word->LST_PRICE;?> --</option>
                <option value="4999">&lt; 5000</option>
                <option value="10000">5 000 - 10 000</option>
                <option value="20000">10 000 - 20 000</option>
                <option value="30000">20 000 - 30 000</option>
                <option value="50000">30 000 - 50 000</option>
                <option value="75000">50 000 - 75 000</option>
                <option value="100000">75 000 - 100 000</option>
                <option value="100001">100 000 +</option>
              </select>
            </div>
            <div class="field">
              <select name="miles">
                <option value="">-- <?php echo $core->odometer == "km" ? Lang::$word->KM : Lang::$word->MI;?> --</option>
                <?php //echo Utility::doRange($core->minkm, $core->maxkm, 5000);?>
                <option value="9999">&lt; 10000</option>
                <option value="30000">10 000 - 30 000</option>
                <option value="60000">30 000 - 60 000</option>
                <option value="100000">60 000 - 100 000</option>
                <option value="150000">100 000 - 150 000</option>
                <option value="200000">150 000 - 200 000</option>
                <option value="200001">200 000 +</option>
              </select>
            </div>
            <div class="field">
              <div class="two fields">
                <div class="field">
                  <select name="fuel">
                    <option value="">-- <?php echo Lang::$word->LST_FUEL;?> --</option>
                    <?php echo Utility::loopOptions(Utility::unserialToArray($core->fuel_list), "id", "name");?>
                  </select>
                </div>
                <div class="field">
                  <select name="year">
                    <option value="">-- <?php echo Lang::$word->LST_YEAR;?> --</option>
                    <?php echo Utility::doRange($core->minyear, $core->maxyear, 1);?>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="field content-center">
            <button type="submit" name="search" class="wojo negative rounded button"><i class="icon circle chevron right"></i><?php echo Lang::$word->HOME_BTN;?></button>
          </div>
        </form>
      </div>
    </div>
    <div class="screen-5 tablet-5 phone-hide">&nbsp;</div>
  </div>
</div>