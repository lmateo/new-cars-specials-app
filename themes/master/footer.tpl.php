<?php
  /**
   * Footer
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: footer.tpl.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo-grid">
  <?php if($core->show_home and $core->_url[0] == "index"):?>
  <!--/* Subfooter Section Start */-->
   <?php echo $core->home_content;?>
  <!--/* Subfooter Section Ends */-->
  <?php endif;?>
  <footer> <a id="scrollUp" class="shadow"><i class="icon chevron up"></i></a>
    <div class="columns horizontal-gutters">
      <div class="screen-60 tablet-60 phone-100">
        <p><i class="icon phone"></i> <?php echo $core->phone;?></p>
        <p><?php echo $core->address;?>, <?php echo $core->city;?>, <?php echo $core->state;?> <?php echo $core->zip;?>
          <?php if($core->fb):?>
          &nbsp;<a href="http://facebook.com/<?php echo $core->fb;?>"><i class="icon facebook white link"></i></a>
          <?php endif;?>
          <?php if($core->twitter):?>
          &nbsp;<a href="http://twitter.com/<?php echo $core->twitter;?>"><i class="icon white twitter link"></i></a>
          <?php endif;?>
        </p>
      </div>
      <div class="screen-40 tablet-40 phone-100">
        <div class="content-right">
          <p>Copyright &copy;<?php echo date('Y').' '.$core->company;?></p>
          <p>Powered by: Car Dealer Pro v<?php echo $core->wojov;?></p>
        </div>
      </div>
    </div>
  </footer>
</div>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor.js"></script> 
<script src="<?php echo SITEURL;?>/assets/fullscreen.js"></script> 
<script type="text/javascript" src="<?php echo THEMEU;?>/js/master.js"></script>
<?php Debug::displayInfo();?>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function() {
    $.Master({
        weekstart: <?php echo($core-> weekstart);?>,
        lang: {
            button_text: "<?php echo Lang::$word->BROWSE;?>",
			mbutton_text: "<?php echo Lang::$word->BROWSEM;?>",
            empty_text: "<?php echo Lang::$word->NOFILE;?>",
            monthsFull: [ <?php echo Utility::monthList(false);?> ],
            monthsShort: [ <?php echo Utility::monthList(false, false);?> ],
            weeksFull: [ <?php echo Utility::weekList(false); ?> ],
            weeksShort: [ <?php echo Utility::weekList(false, false);?> ],
			weeksMed: [ <?php echo Utility::weekList(false, false, true);?> ],
            today: "<?php echo Lang::$word->TODAY;?>",
            clear: "<?php echo Lang::$word->CLEAR;?>",
            delBtn: "<?php echo Lang::$word->DELETE_REC;?>",
			invImage: "<?php echo Lang::$word->LST_IMAGE_ERR1;?>",
            delMsg1: "<?php echo Lang::$word->DELCONFIRM1;?>",
            delMsg2: "<?php echo Lang::$word->DELCONFIRM2;?>",
            working: "<?php echo Lang::$word->WORKING;?>"
        }
    });
	<?php if($core->eucookie):?>
    $("body").acceptCookies({
        position: 'top',
        notice: '<?php echo Lang::$word->EU_NOTICE;?>',
        accept: '<?php echo Lang::$word->EU_ACCEPT;?>',
        decline: '<?php echo Lang::$word->EU_DECLINE;?>',
        decline_t: '<?php echo Lang::$word->EU_DECLINE_T;?>',
        whatc: '<?php echo Lang::$word->EU_W_COOKIES;?>'
    })
	<?php endif;?>
	<?php if($core->show_news):?>
	$.ajax({
		type: 'get',
		url: SITEURL + "/ajax/controller.php",
		dataType: 'json',
		data: {getNews:1},
		success: function (json) {
			if(json.status == "success") {
				$("<div class=\"wojo attached segment\">" + json.html + "</div>").insertAfter($("header"));
			}
		}
	});
	<?php endif;?>
});
<?php if($core->analytics):?>
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $core->analytics;?>']);
  _gaq.push(['_trackPageview']);

  (function() {
      var ga = document.createElement('script');
      ga.type = 'text/javascript';
      ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(ga, s);
  })();
<?php endif;?>
// ]]>
</script>
</body></html>