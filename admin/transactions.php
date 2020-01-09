<?php
  /**
   * Transactions
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: transactions.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if(!Users::checkAcl("owner")): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php $data = $content->getPayments();?>
<?php $memdata = $content->getMemberships();?>
<div class="wojo secondary icon message"> <i class="money bag icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->TRX_TITLE;?></div>
    <p><?php echo Lang::$word->TRX_INFO;?></p>
  </div>
</div>
<div class="wojo quaternary segment">
  <div class="header"><?php echo Lang::$word->M_FILTER;?></div>
  <div class="content">
    <div class="wojo form">
      <form method="post" id="wojo_form" action="<?php echo Url::adminUrl("transactions");?>" name="wojo_form">
        <div class="three fields">
          <div class="field">
            <div class="wojo input"> <i class="icon-prepend icon calendar"></i>
              <input name="fromdate" type="text" id="fromdate" placeholder="<?php echo Lang::$word->FROM;?>" readonly data-link-field="true" data-date-format="dd, MM yyyy" data-link-format="yyyy-mm-dd">
            </div>
          </div>
          <div class="field">
            <div class="wojo action input"> <i class="icon-prepend icon calendar"></i>
              <input name="enddate" type="text" id="enddate" placeholder="<?php echo Lang::$word->TO;?>" readonly data-date-autoclose="true" data-min-view="2" data-start-view="2" data-date-today-btn="true" data-link-field="true" data-date-format="dd, MM yyyy" data-link-format="yyyy-mm-dd">
              <a id="doDates" class="wojo primary button"><?php echo Lang::$word->FIND;?></a> </div>
          </div>
          <div class="field">
            <div class="columns horizontal-gutters">
              <div class="all-50"><?php echo $pager->items_per_page();?></div>
              <div class="all-50"><?php echo $pager->jump_menu();?> </div>
            </div>
          </div>
        </div>
        <div class="three fields">
          <div class="field">
            <select name="mid" data-links="true">
              <option value="<?php echo Url::adminUrl("transactions");?>">--- <?php echo Lang::$word->TRX_RESET_FILTER;?> ---</option>
              <?php if($memdata):?>
              <?php foreach($memdata as $mrow):?>
              <?php $selected = ($mrow->id == Filter::$id) ? ' selected="selected"' : null;?>
              <option value="<?php echo Url::adminUrl("transactions", false, false,"?id=" . $mrow->id);?>"<?php echo $selected;?>><?php echo $mrow->title;?></option>
              <?php endforeach;?>
              <?php endif;?>
            </select>
          </div>
          <div class="field"></div>
          <div class="field">
            <div class="wojo icon input">
              <input type="text" name="transsearch" placeholder="<?php echo Lang::$word->SEARCH;?>" id="searchfield">
              <i class="find icon"></i>
              <div id="suggestions"> </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php if($data):?>
<div class="wojo segment">
  <div class="header clearfix"> <a data-showhide="true" data-set='{"el": "#chartdata"}' class="push-right"><i class="large chevron up icon"></i></a> <a class="wojo left pointing dropdown" data-select-range="true"> <i class="large apps alt icon"></i>
    <div class="menu">
      <div class="item" data-value="all"><?php echo Lang::$word->ALL;?></div>
      <div class="item" data-value="day"><?php echo Lang::$word->TODAY;?></div>
      <div class="item" data-value="week"><?php echo Lang::$word->THIS_WEEK;?></div>
      <div class="item" data-value="month"><?php echo Lang::$word->THIS_MONTH;?></div>
      <div class="item" data-value="year"><?php echo Lang::$word->THIS_YEAR;?></div>
    </div>
    </a> </div>
  <div class="content" id="chartdata">
    <div id="chart" style="height:400px;"></div>
  </div>
</div>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.flot.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/flot.resize.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/excanvas.min.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.flot.spline.js"></script> 
<script type='text/javascript'>
  function getStats(range) {
      $.ajax({
          type: 'GET',
          url: ADMINURL + "/helper.php?getTransStats=1&timerange=" + range,
          dataType: 'json'
      }).done(function(json) {
          var option = {
              series: {
                  lines: {
                      show: false
                  },
                  splines: {
                      show: true,
                      tension: 0.5,
                      lineWidth: 2,
                      fill: 0.3
                  },
                  shadowSize: 0
              },
              points: {
                  show: true,
              },
			  colors: ["#d25b5b", "#9ACA40"],
              grid: {
                  hoverable: true,
                  clickable: true,
                  borderColor: "rgba(0,0,0,0.1)",
                  borderWidth: 1,
                  labelMargin: 16,
                  backgroundColor: '#fff'
              },
              yaxis: {
                  color: "rgba(0,0,0,0.1)",
                  font: {
                      color: "#939599"
                  }
              },
              xaxis: {
                  color: "rgba(0,0,0,0.1)",
                  ticks: json.xaxis,
                  font: {
                      color: "#939599"
                  }
              },
              legend: {
                  backgroundColor: "#fff",
                  labelBoxBorderColor: "rgba(0,0,0,0.1)",
                  backgroundOpacity: .75,
                  noColumns: 1,
              }
          }

         plot = $.plot($('#chart'), [json.sales,json.amount], option);
      });

  }
  getStats('all');
  $("[data-select-range]").on('click', '.item', function() {
      v = $(this).data('value');
      getStats(v)
  });
  
  function showTooltip(x, y, contents) {
	  $('<div class="charts_tooltip">' + contents + '</div>').css({
		  position: 'absolute',
		  display: 'none',
		  top: y + 5,
		  left: x + 5
	  }).appendTo("body").fadeIn(200);
  }
  var previousPoint = null;
  
  $("#chart").on("plothover", function (event, pos, item) {
	  if (item) {
		  if (previousPoint != item.dataIndex) {
			  previousPoint = item.dataIndex;
			  $(".charts_tooltip").fadeOut("fast").promise().done(function () {
				  $(this).remove();
			  });
			  var x = item.datapoint[0].toFixed(2),
				  y = item.datapoint[1].toFixed(2);
			  i = item.series.xaxis.options.ticks[item.dataIndex][1]
			  showTooltip(item.pageX, item.pageY, item.series.label + " for " + i + " = " + y);
		  }
	  } else {
		  $(".charts_tooltip").fadeOut("fast").promise().done(function () {
			  $(this).remove();
		  });
		  previousPoint = null;
	  }
  });
</script> 
<?php endif;?>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->TRX_SUB;?></span>
    <a class="wojo large top right detached action label" data-content="<?php echo Lang::$word->TRX_EXPORTXLS;?>" href="<?php echo ADMINURL;?>/helper.php?exportTransactions" ><i class="icon table"></i></a>
  </div>
  <table class="wojo sortable table">
    <thead>
      <tr>
        <th data-sort="string"><?php echo Lang::$word->TRX_MEMNAME;?></th>
        <th data-sort="string"><?php echo Lang::$word->USERNAME;?></th>
        <th data-sort="string"><?php echo Lang::$word->AMOUNT;?></th>
        <th data-sort="int"><?php echo Lang::$word->TRX_PAYDATE;?></th>
        <th class="disabled"><?php echo Lang::$word->TRX_PROCESSOR;?></th>
        <th data-sort="int"><?php echo Lang::$word->ACTIVE;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="7"><?php echo Message::msgSingleAlert(Lang::$word->TRX_NOTRANS);?></td>
      </tr>
      <?php else:?>
      <?php foreach($data as $row):?>
      <tr>
        <td><a href="<?php echo Url::adminUrl("packages", "edit", false,"?id=" . $row->mid);?>"><?php echo $row->title;?></a></td>
        <td><a href="<?php echo Url::adminUrl("members", "edit", false,"?id=" . $row->uid);?>"><?php echo $row->username;?></a></td>
        <td><?php echo Utility::formatMoney($row->rate_amount, true);?></td>
        <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Utility::dodate("long_date", $row->created);?></td>
        <td><small class="wojo primary label"><?php echo $row->pp;?></small></td>
        <td data-sort-value="<?php echo $row->status;?>"><?php echo Utility::isActive($row->status);?></td>
        <td><a class="delete" data-set='{"title": "<?php echo Lang::$word->TRX_DELETE;?>", "parent": "tr", "option": "deleteTransaction", "id": <?php echo $row->id;?>, "name": "<?php echo $row->txn_id;?>"}'><i class="rounded outline icon negative trash link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
  <div class="footer">
    <div class="wojo tabular segment">
      <div class="wojo cell"> <?php echo $pager->display_pages();?></div>
      <div class="wojo cell right"> <?php echo Lang::$word->TOTAL.': '.$pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$pager->current_page.' '.Lang::$word->OF.' '.$pager->num_pages;?> </div>
    </div>
  </div>
</div>