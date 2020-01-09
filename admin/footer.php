<?php
  /**
   * Footer
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: footer.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<!-- Footer -->
<footer> Copyright &copy;<?php echo date('Y').' '.$core->company;?> <br> Powered by: Quirk Digital Marketing Web-Inventory<?php echo $core->wojov;?> </footer>
<?php Debug::displayInfo();?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/admin/assets/js/master.js"></script> 
<script src="<?php echo SITEURL;?>/assets/fullscreen.js"></script> 
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
});
// ]]>
</script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/masonry.js"></script> 
</body></html>