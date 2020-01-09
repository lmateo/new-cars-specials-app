<?php
  /**
   * F.A.Q.
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: faq.tpl.php, v1.00 2015-08-05 10:16:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  $faqrow = $content->getFaq();
?>
<?php if($faqrow):?>
<div class="half-padding wojo secondary bg">
  <?php foreach ($faqrow as $row):?>
  <div class="wojo segment">
    <h3 class="wojo header"><?php echo $row->question;?></h3>
    <div class="answer clearfix"><?php echo Validator::cleanOut($row->answer);?></div>
  </div>
  <?php endforeach;?>
  <?php unset($row);?>
</div>
<?php endif;?>