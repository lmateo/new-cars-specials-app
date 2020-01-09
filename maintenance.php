<?php
  /**
   * Maintenance
   *
   * @package CMS pro
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: maintenance.php, v4.00 2014-04-23 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
	  if(!$core->offline)
	  redirect_to(SITEURL);
?>
<!doctype html>
<head>
<meta charset="utf-8">
<title><?php echo $core->company;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="http://fonts.googleapis.com/css?family=Quicksand:300,400,700" rel="stylesheet" type="text/css">
<link href="<?php echo SITEURL;?>/assets/uc/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<!-- Start Maintenance-->
<div id="container">
  <div class="wrapper">
    <div class="logo"><?php echo ($core->logo) ? '<img src="'.SITEURL.'/uploads/'.$core->logo.'" alt="'.$core->company.'" />': $core->company;?></div>
    <h1><?php echo Lang::$word->UM_H1;?></h1>
    <h2 class="subtitle"><?php echo Lang::$word->UM_H2;?></h2>
    <div id="dashboard" class="row">
      <div class="col grid_6">
        <div class="dash weeks_dash">
          <div class="digit first">
            <div style="display:none" class="top">1</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <div class="digit last">
            <div style="display:none" class="top">3</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <span class="dash_title"><?php echo Lang::$word->_WEEKS;?></span> </div>
      </div>
      <div class="col grid_6">
        <div class="dash days_dash">
          <div class="digit first">
            <div style="display:none" class="top">0</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <div class="digit last">
            <div style="display:none" class="top">0</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <span class="dash_title"><?php echo Lang::$word->_DAYS;?></span> </div>
      </div>
      <div class="col grid_6">
        <div class="dash hours_dash">
          <div class="digit first">
            <div style="display:none" class="top">2</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <div class="digit last">
            <div style="display:none" class="top">3</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <span class="dash_title"><?php echo Lang::$word->_HOURS;?></span> </div>
      </div>
      <div class="col grid_6">
        <div class="dash minutes_dash">
          <div class="digit first">
            <div style="display:none" class="top">2</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <div class="digit last">
            <div style="display:none" class="top">9</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <span class="dash_title"><?php echo Lang::$word->_MINUTES;?></span> </div>
      </div>
    </div>
    <div class="info-box"> <?php echo Validator::cleanOut($core->offline_msg);?> </div>
  </div>
</div>
<?php 
  $d = explode("-",$core->offline_d); 
  $t = explode(":",$core->offline_t);
?>
<script src="<?php echo SITEURL; ?>/assets/jquery.js"></script>
<script src="<?php echo SITEURL;?>/assets/uc/script.js"></script>
<script type="text/javascript">
$(document).ready(function () {
	$('#dashboard').countDown({
		targetDate: {
			'day': <?php echo $d[2];?>,
			'month': <?php echo $d[1];?>,
			'year': <?php echo $d[0];?>,
			'hour': <?php echo $t[0];?>,
			'min': <?php echo $t[1];?>,
			'sec': 0
		}
	});
});
</script>
</body>
</html>