<?php
  /**
   * Account Statistics
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: accstats.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if(!Users::checkAcl("owner", "admin")): print Message::msgError(Lang::$word->NOACCESS); return; endif; 
?>
<div class="wojo secondary icon message"> <i class="line chart icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->M_TITLE3;?> </div>
    <p><?php echo Lang::$word->M_INFO3;?></p>
  </div>
</div>
<div class="wojo quaternary segment">
  <div class="header clearfix"> <a class="wojo left pointing dropdown" data-select-range="true"> <i class="large apps alt icon"></i>
    <div class="menu">
      <div class="item" data-value="all"><?php echo Lang::$word->ALL;?></div>
      <div class="item" data-value="day"><?php echo Lang::$word->TODAY;?></div>
      <div class="item" data-value="week"><?php echo Lang::$word->THIS_WEEK;?></div>
      <div class="item" data-value="month"><?php echo Lang::$word->THIS_MONTH;?></div>
      <div class="item" data-value="year"><?php echo Lang::$word->THIS_YEAR;?></div>
    </div>
    </a> </div>
  <div class="content">
    <div id="chart" style="height:600px;overflow:hidden"></div>
  </div>
</div>
<div class="wojo quaternary segment">
  <div class="header"><?php echo Lang::$word->M_MAP_OVERLAY;?></div>
  <div class="content">
    <div id="map">wait...</div>
  </div>
</div>
<script type='text/javascript' src='<?php echo Url::protocol();?>://www.google.com/jsapi'></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.flot.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/flot.resize.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/excanvas.min.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.flot.spline.js"></script> 
<script type='text/javascript'>
  google.load('visualization', '1', {
      'packages': ['geochart']
  });
  google.setOnLoadCallback(drawRegionsMap);

  function drawRegionsMap() {
      $.ajax({
          url: ADMINURL + "/helper.php?getAccCountries",
          dataType: "json"
      }).done(function(result) {
          var data = new google.visualization.DataTable();
          data.addColumn('string', '<?php echo Lang::$word->COUNTRY;?>');
          data.addColumn('number', '<?php echo Lang::$word->POPULARITY;?>');
          for (i = 0; i < result.country_name.length; i++)
              data.addRow([result.country_name[i], result.hits[i]]);

          var chart = new google.visualization.GeoChart(
              document.getElementById('map'));
          chart.draw(data, options);
          var geochart = new google.visualization.GeoChart(
              document.getElementById('map'));
          var options = {
              width: "auto",
              height: 600,
			  backgroundColor:"transparent",
              colorAxis: {
                  colors: ['#D3DCDC', '#A8C1C7']
              } // Map Colors 
          };
          geochart.draw(data, options);
      });
  };

  function getRegistration(range) {
      $.ajax({
          type: 'GET',
          url: ADMINURL + "/helper.php?getAccStats=1&timerange=" + range,
          dataType: 'json'
      }).done(function(json) {
          var option = {
              series: {
                  lines: {
                      show: false
                  },
                  splines: {
                      show: true,
                      tension: 0.4,
                      lineWidth: 1,
                      fill: 0.3
                  },
                  shadowSize: 0
              },
              points: {
                  show: true,
              },
              grid: {
                  hoverable: true,
                  clickable: true,
                  borderColor: "rgba(0,0,0,0.1)",
                  borderWidth: 1,
                  labelMargin: 10,
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
                  labelBoxBorderColor: "",
                  backgroundOpacity: .75,
                  noColumns: 1,
              }
          }

          $.plot($('#chart'), [json.regs], option);
      });

  }
  getRegistration('all');
  $("[data-select-range]").on('click', '.item', function() {
      v = $(this).data('value');
      getRegistration(v)
  });
</script> 