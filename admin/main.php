<?php
  /**
   * Main
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: main.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
	  $data = Stats::getSalesStats();
	  $counter = Stats::mainCounters();
	  $calendar = Calendar::instance();
?>
<div class="wojo secondary icon message"> <i class="home icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->DASH_TITLE;?></div>
    <p><?php echo Lang::$word->DASH_INFO;?></p>
  </div>
</div>
<div class="wojo segment">
  <div class="columns gutters">
    <div class="screen-20 tablet-20 phone-100">
      <div class="content-center">
        <h6 class="half-bottom-space"><i class="icon credit card"></i> <?php echo Lang::$word->DASH_SUB1;?></h6>
        <p class="wojo big text"><?php echo $data['totalsales'];?></p>
        <span class="sparkline" dataType="bar" values="<?php echo $data['sales_str'];?>" dataBarColor="#9ACA40" dataHeight="50" databarWidth="10" databarSpacing="2"></span>
        <p class="wojo small text top-space bottom-space"><?php echo str_replace("[NUMBER]", '<b>' . $data['totalsales'] . '</b>', Lang::$word->DASH_INFO1);?></p>
        <span class="sparkline" 
                    dataType="line" 
                    values="<?php echo $data['sales_str'];?>" 
                    datalineColor="#1ca8dd" 
                    datafillColor="rgba(28,168,221,0.30)" 
                    dataspotColor="#2f323a" 
                    dataminSpotColor="#2f323a" 
                    datamaxSpotColor="#2f323a" 
                    datahighlightSpotColor="#2f323a" 
                    datahighlightLineColor="#2f323a" 
                    dataspotRadius="5" 
                    dataHeight="100" 
                    datawidth="100%" 
                    datalineWidth="3"></span> </div>
    </div>
    <div class="screen-60 tablet-60 phone-100">
      <h6 class="half-bottom-space"><i class="icon line chart"></i> <?php echo Lang::$word->DASH_SUB3;?></h6>
      <div id="chart" style="height:300px;"></div>
    </div>
    <div class="screen-20 tablet-20 phone-100">
      <div class="content-center">
        <h6 class="half-bottom-space"><i class="icon credit card"></i> <?php echo Lang::$word->DASH_SUB2;?></h6>
        <p class="wojo big text"><?php echo Utility::formatMoney($data['totalsum'], false);?></p>
        <span class="sparkline" dataType="bar" values="<?php echo $data['amount_str'];?>" dataBarColor="#1ca8dd" dataHeight="50" databarWidth="10" databarSpacing="2"></span>
        <p class="wojo small text top-space bottom-space"><?php echo str_replace("[NUMBER]", '<b>' . Utility::formatMoney($data['totalsum'], true) . '</b>', Lang::$word->DASH_INFO2);?></p>
        <span class="sparkline" 
                    dataType="line" 
                    values="<?php echo $data['amount_str'];?>" 
                    datalineColor="#9ACA40" 
                    datafillColor="rgba(154,202,64,0.30)" 
                    dataspotColor="#2f323a" 
                    dataminSpotColor="#2f323a" 
                    datamaxSpotColor="#2f323a" 
                    datahighlightSpotColor="#2f323a" 
                    datahighlightLineColor="#2f323a" 
                    dataspotRadius="5" 
                    dataHeight="100" 
                    datawidth="100%" 
                    datalineWidth="3"></span> </div>
    </div>
  </div>
</div>
<div class="wojo segment">
  <div id="chart2" style="height:300px;"></div>
</div>
<div class="wojo segment">
  <div class="columns double-horizontal-gutters phone-vertical-gutters">
    <div class="screen-50 tablet-50 phone-100">
      <h6 class="half-bottom-space"><i class="icon pie chart"></i> <?php echo Lang::$word->DASH_SUB4;?></h6>
      <div id="chart3" style="height:380px;"></div>
    </div>
    <div class="screen-50 tablet-50 phone-100">
      <h6 class="half-bottom-space"><i class="icon pie chart"></i> <?php echo Lang::$word->DASH_SUB4;?></h6>
      <p id="tvisits" class="wojo big text bottom-space"><?php echo Lang::$word->TRX_TOTALYEAR;?> <small></small></p>
      <div id="chart4"> </div>
    </div>
  </div>
</div>
<div class="columns gutters">
  <div class="screen-50 tablet-100 phone-100">
    <div class="wojo tertiary segment">
      <div class="header clearfix"><span><?php echo Lang::$word->DASH_SUB9 . ' / ' . Utility::dodate("MMMM", date('M'));?> - <?php echo date('Y');?></span> </div>
      <div class="wojo thin divider"></div>
      <div id="calendar">
        <?php if($counter->listings):?>
        <?php foreach($counter->listings as $i => $lrow):?>
        <?php if($i == 4) :?>
        <?php $calendar->addEvent("...", $lrow->expires);?>
        <?php break;?>
        <?php endif;?>
        <?php $calendar->addEvent('<a href="' . Url::adminUrl("items", "edit", false,"?id=" . $lrow->id) . '">' . Validator::truncate($lrow->title, 20) . '</a>', $lrow->expires);?>
        <?php endforeach;?>
        <?php unset($lrow);?>
        <?php endif;?>
        <?php $calendar->show();?>
      </div>
    </div>
  </div>
  <div class="screen-50 tablet-100 phone-100">
    <div class="wojo mall positive icon message"> <i class="icon users"></i>
      <div class="content">
        <div class="header wojo big text"> <?php echo $counter->users;?> </div>
        <p><?php echo Lang::$word->DASH_SUB5;?></p>
      </div>
    </div>
    <div class="wojo black icon message"> <i class="icon user add"></i>
      <div class="content">
        <div class="header wojo big text"> <?php echo $counter->week;?> </div>
        <p><?php echo Lang::$word->DASH_SUB10;?></p>
      </div>
    </div>
    <div class="wojo primary icon message"> <i class="icon car"></i>
      <div class="content">
        <div class="header wojo big text"> <?php echo $counter->active;?> </div>
        <p><?php echo Lang::$word->DASH_SUB6;?></p>
      </div>
    </div>
    <div class="wojo negative icon message"> <i class="icon car"></i>
      <div class="content">
        <div class="header wojo big text"> <?php echo $counter->expire;?> </div>
        <p><?php echo Lang::$word->DASH_SUB7;?></p>
      </div>
    </div>
    <div class="wojo alert icon message"> <i class="icon car"></i>
      <div class="content">
        <div class="header wojo big text"> <?php echo $counter->pending;?> </div>
        <p><a href="<?php echo Url::adminUrl("pending");?>" class="inverted"><?php echo Lang::$word->DASH_SUB8;?></a></p>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.sparkline.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.flot.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/flot.resize.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/excanvas.min.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.flot.spline.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.flot.orderbar.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.flot.pie.js"></script> 
<script type="text/javascript" src="<?php echo ADMINURL;?>/assets/js/dashboard.js"></script> 